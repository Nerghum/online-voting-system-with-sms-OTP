<?php include 'admin/includes/conn.php'; ?>
<?php
  	session_start();
  	if(isset($_SESSION['admin'])){
    	header('location: admin/home.php');
  	}

    if(isset($_SESSION['voter'])){
      header('location: home.php');
    } 

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">

	<script>


  function countDownClock(time , id){
    var countDownDate = new Date(time).getTime();
    var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById("timer"+id).innerHTML = days.toString() + "Day " + hours.toString() + "Hour "
      + minutes.toString() + "Minute " + seconds.toString() + "Seconds ";
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("timer"+id).innerHTML = "Ongoing";
      }
    }, 1000);
  }                
</script>


<div class="container">
  <div class="raw">
    <div class="col-md-8">
      <div class="login-box">
    <div class="login-logo">
      <b>Voting System</b>
    </div>
  
    <div class="login-box-body">
      <p class="login-box-msg">Sign in to vote</p>

      <form action="login.php" method="POST">
          <div class="form-group has-feedback">
            <input type="text" id="vid" class="form-control" name="votar" placeholder="ID number / Phone " required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback" id="otpSection" hidden>
            <input type="text" class="form-control" name="otp" id="otp" placeholder="OTP" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
          <div class="col-xs-4">
                <button type="submit" id="login-submit" data-step="1" class="btn btn-primary btn-block btn-flat" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
            </div>
          </div>
      </form>
    </div>
    
    <div class='callout callout-danger text-center mt20' id="error-votar-id" style="display:none;">
      <p>Your ID is incorrect / Phone. Please try again with correct ID</p> 
    </div>

    <div class='callout callout-danger text-center mt20' id="sms_api_error" style="display:none;">
      <p>Network error. Please try again</p> 
    </div>

    <div class='callout callout-danger text-center mt20' id="otp_error" style="display:none;">
      <p>OTP is not correct. Please enter correct OTP</p> 
    </div>
</div>
    </div>

    <div class="col-md-4" style="min-height: 110px;">
          <!-- small box -->
        <div class="small-box bg-yellow" style="min-height: 110px;">
          <div class="inner">
            <h3>Upcoming Elections</h3>             
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
        </div>
        <?php

          $sql = "SELECT * FROM election WHERE status = 'active'";
          $query = $conn->query($sql);
          $election_data = $query->fetch_all(MYSQLI_ASSOC);

          date_default_timezone_set('Asia/Dhaka');
          $index = 0;
          foreach($election_data as $data){ 
            $start_time = date(strtotime($data['start_time']));
            $current_time = time();
            $css = ($index % 2 == 0) ? "bg-blue" : "bg-green";
            if($start_time > $current_time){
              echo '
                <div class="small-box '.$css.'" style="min-height: 110px;">
                  <div class="inner">
                    <h4>'.$data['title'].' - <span>'.unserialize($data['district'])[1].'</span></h4> 
                    <hr style="margin: 0 0 0 0;">
                    <div id="timer'.$data["id"].'" style="font-weight: bold;margin-top: 10px;font-size: 20px;"></div>            
                  </div>
                </div>
                <script>
                  countDownClock("'.date("M d, Y H:i:s", $start_time).'","'.$data["id"].'");
                </script>
              ';
            }
            $index++;
          }

        ?>
    </div>
  </div>
</div>

<!-- Login validation -->
<script>
  document.getElementById("login-submit").addEventListener("click", function(event){
    event.preventDefault();

    document.getElementById('error-votar-id').style.display = 'none';
    document.getElementById('sms_api_error').style.display = 'none';
    document.getElementById('otp_error').style.display = 'none';


    var vid = document.getElementById("vid").value;
    var otp = document.getElementById("otp").value;
    if(vid.length < 6){
      document.getElementById('error-votar-id').style.display = 'block';
    } else {
        var submitBtn = document.getElementById("login-submit");
        if(submitBtn.getAttribute("data-step") == 1){
          alert("An OTP will be sent to your registered mobile");
          var http = new XMLHttpRequest();
          var url = 'sms.php';
          var params = 'votarId='+vid;
          http.open('POST', url, true);
          http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          if(http.send(params)){console.log('ok');}
          http.onreadystatechange = function() {//Call a function when the state changes.
              if(http.readyState == 4 && http.status == 200) {
                  if(http.responseText == 'send_ok'){
                    submitBtn.setAttribute("data-step", 2);
                    document.getElementById('vid').disabled = true;
                    document.getElementById('otpSection').removeAttribute("hidden"); 
                  }
                  if(http.responseText == 'sms_api_error'){
                    document.getElementById('sms_api_error').style.display = 'block';
                  }

                  if(http.responseText == 'votar_id_error'){
                    document.getElementById('error-votar-id').style.display = 'block';
                  }
                  if(http.responseText == 'vote_done'){
                    alert('You have already participated in this election');
                  }

              }
          }

        } else {
          if(otp == ""){
            document.getElementById('otp_error').style.display = "block";
          } else {
              var http = new XMLHttpRequest();
              var url = 'login.php';
              var params = 'otp='+otp+'&nid='+vid;
              http.open('POST', url, true);
              http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              if(http.send(params)){console.log('ok');}
              http.onreadystatechange = function() {//Call a function when the state changes.
                  if(http.readyState == 4 && http.status == 200) {
                      if(http.responseText == 'login'){
                        window.open("index.php","_self");
                      }
                      if(http.responseText == 'otp-error'){
                        document.getElementById('otp_error').style.display = "block";
                      }

                      if(http.responseText == 'nid-error'){
                        document.getElementById('error-votar-id').style.display = 'block';
                      }

                      if(http.responseText == 'all-error'){
                        document.getElementById('error-votar-id').style.display = 'block';
                        document.getElementById('otp_error').style.display = "block";
                      }
                      if(http.responseText == 'vote_done'){
                        alert('You have already participated in this election');
                      }

                  }
              }
          }
            
        }

    }

    

  });
</script>

</body>
</html>