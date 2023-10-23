<?php include 'includes/session.php'; ?>

<?php include 'includes/header.php'; ?>
<?php

$id = $_SESSION['voter'];
$position_tracker = array();

//votar query
$voater_sql = "SELECT * FROM voters WHERE id='$id'";
$voater_query = $conn->query($voater_sql);
$voater_data = $voater_query->fetch_assoc();

 $image = (!empty($voater_data['photo'])) ? './images/'.$voater_data['photo'] : './images/profile.jpg';

function getCandidateImage($id){
	include 'includes/session.php';
	$sql = "SELECT photo FROM candidates WHERE id = '$id'";
	$query = $conn->query($sql);
	$data = $query->fetch_assoc();
	return $data['photo'];
}

//election query
$sql = "SELECT * FROM election WHERE status = 'active'";
$query = $conn->query($sql);
$data = $query->fetch_all(MYSQLI_ASSOC);
$election_id = $data[0]["id"];

//position query
$position_sql = "SELECT * FROM positions";
$position_query = $conn->query($position_sql);
$position_data = $position_query->fetch_all(MYSQLI_ASSOC);


foreach($data as $single){
	if($single['id'] != $voater_data['submited_vote']){
		if(unserialize($single['district'])[0] == $voater_data['district_id']){
			
			//change default timezone 
			//date_default_timezone_set('Asia/Dhaka');  

			$start_time = date(strtotime($single['start_time']));
			$end_time = date(strtotime($single['end_time']));
			$current_time = time();
			if($start_time < $current_time){
				foreach(unserialize($single['ward']) as $single_ward){
					if($single_ward['id'] == $voater_data['ward_id']){
						
						$election_id = $single["id"];
						
						//meta query
						$meta_sql = "SELECT * FROM election_meta WHERE election_id=$election_id";
						$meta_query = $conn->query($meta_sql);
						$meta_data = $meta_query->fetch_all(MYSQLI_ASSOC);
						
						foreach($meta_data as $single_meta){
							
							foreach(unserialize($single_meta['ward_id']) as $single_ward){
								if($single_ward == $voater_data['ward_id']){
									$meta = unserialize($single_meta['meta']);
									$wardList = '';
									foreach(unserialize($meta['ward_name']) as $singleWard){
										$wardList .= '<li class="list-group-item">'.$singleWard.'</li>';
									}

									for($i = 0; $i < count($position_data); $i++){

										if($position_data[$i]['id'] == $single_meta['position_id'] ) {
											$title = $position_data[$i]['description'];
											

											$htmlOut[$title] .= '<div class="row single pos-'.$position_data[$i]['description'].'" style="border-left: 1px solid #d1d1d1;">
							        		<div class="col-md-4 text-right">
							        			<img style="height: 200px;width:200px" src="./images/'.getCandidateImage($single_meta['candidate_id']).'" class="img-thumbnail" alt="...">
							        		</div>
							        		<div class="col-md-5">
							        			<h2 style="margin-top:0;">'.$meta["candidade_name"].'</h2>
							        			<ul class="list-group">
												  '.$wardList.'
												</ul>
							        		</div>
							        		<div class="col-md-3">
							        			    <input type="radio" value="'.$single_meta['candidate_id'].'" class="vote-click position-'.$position_data[$i]['id'].'" name="vote-'.$position_data[$i]['id'].'">
							        			    <p style="font-size: 12px;">Click here to vote</p>
							        		</div>
							        </div>';
										}
									}

								}
							}
						}
					}
				}
			}
		}
	}
}

?>




<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
	<?php include 'includes/navbar.php'; ?> 
  <div class="content-wrapper">


	<div class="container">
    	<section class="content">
      	<div class="row bg-yellow">
      		<div class="col-md-4">
      		<img style="padding: 20px;height:300px;width: 300px;" src="<?= $image ?>">	
      		</div>
      		<div class="col-md-8">
      			
      			<h1><?= $voater_data['firstname'] ?> <?= $voater_data['lastname'] ?></h1>
      			<table id="" class="table table-bordered">
      				<thead>
				        <tr>
				            <th></th>
				            <th></th>
				        </tr>
				    </thead>
      				<tbody>
      					<tr>
      						<th>Phone:  </th>
      						<td><?= $voater_data['phone'] ?></td>
      					</tr>

      					<tr>
      						<th>Voter ID: </th>
      						<td><?= $voater_data['voters_id'] ?></td>
      					</tr>

      					<tr>
      						<th>State: </th>
      						<td><?= unserialize($voater_data['meta'])['district_name'] ?></td>
      					</tr>

      					<tr>
      						<th>Location: </th>
      						<td><?= unserialize($voater_data['meta'])['ward_name'] ?></td>
      					</tr>
      				</tbody>
      			</table>
      		</div>
      	</div>

    	</section>
     
	</div>

    <div class="container">

      <!-- Main content -->
      <section class="content">
      	
      	<?php
		for($i = 0; $i< count($htmlOut); $i++){
			echo "<div class='col-md-6'>";
			$key = array_keys($htmlOut)[$i];
			echo '<h1 style=" margin: 20px 0 0px 0px;" class="page-header text-center title"><b>'. $key .'</b></h1>';
			print_r($htmlOut[$key]);
			echo "</div>";
					
		}

      	?>

      <div class="row">
      	<div class="col-md-12">
      		<br>
      		<button type="button" onclick="voatSubmit();" class="btn btn-success btn-lg btn-block">Cast your vote</button>
      	</div>
      </div>

      </section>
     
    </div>
  </div>
  
  	<?php include 'includes/footer.php'; ?>
  
</div>

<?php include 'includes/scripts.php'; ?>
<script>


function voatSubmit(){
	if (confirm("You are voting") == true) {
		var votedata = [];
		var type = <?= json_encode($position_data) ?>;
		var flag = 0;
		var electionId = <?= $election_id ?>;

		for(var i = 0 ; i< type.length;i++){
			if($('.position-'+type[i]['id']+':checked').val() == undefined && $('.pos-'+type[i]['description']).length != 0 ){
			 	alert(type[i]['description']+' -Tick next to it and submit' );
			 	console.log();
			 	flag = 1;
			} else {
				votedata.push($('.position-'+type[i]['id']+':checked').val());
			}
		}


		if(flag == 0){
			$.ajax({
		      type: 'POST',
		      url: 'includes/ajax_handel.php',
		      data: {'action':'submit_voat', 'data':votedata, 'election_id': electionId},
		      dataType: 'html',
		      success: function(response){
		        if(response == 'success'){
		          alert('You have successfully voted. A confirmation message has been sent to your phone');
		          
		        	setTimeout(location.reload(), 2000)
		          
		        } else {
		        	alert('Try again' + response);
		        }
		        
		      }
		    });
		}
	}

}



</script>
<style type="text/css">
	.vote-click{
	height: 70px;
    width: 70px;
    margin-top: 35px !important;
    }

    .vote-click:checked{
  		background-color: green;
	}
	.single{
		background: white;
    	padding: 10px;
	}

</style>
</body>
</html>