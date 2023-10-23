<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Election
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Election</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnewElection" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New Election</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Name </th>
                  <th>State</th>
                  <th>Area</th>
                  <th>Start</th>
                  <th>End</th>
                  <th></th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT * FROM election";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      $wardList = unserialize($row['ward']);
                      $htmlWard = '';
                      
                      for($i = 0; $i < count($wardList) ; $i++ ){
                        $htmlWard .= $wardList[$i]['name'] . ',';
                      }
                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>".$row['title']."</td>
                          <td>".unserialize($row['district'])[1]."</td>
                          <td>".$htmlWard."</td>
                          <td>".$newDateTime = date('d/m/Y - h:i A', strtotime($row['start_time']))."</td>
                          <td>".$newDateTime = date('d/m/Y - h:i A', strtotime($row['end_time']))."</td>
                          <td>
                            <a class='btn btn-info btn-sm btn-flat' href=single_election.php?election=".$row['id']." ><i class='fa fa-edit'></i> Details</a>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button>
                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Delete</button>
                          </td>

                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/election_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    alert('Deleting elections');
    var id = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: 'includes/ajax_handel.php',
      data: {'action':'removeElection','id':id},
      dataType: 'html',
      success: function(response){
        if(response== 'success'){
          alert('Election deleted');
          location.reload();
        } else {
          alert(response);
        }
        }
      });  
  });

});


$('#select-district').change(function(){
  var getDistrictWord = $('#select-district').val();
  $.ajax({
  type: 'POST',
  url: 'includes/ajax_handel.php',
  data: {'getDistrictWord':getDistrictWord},
  dataType: 'html',
  success: function(response){
    $('#ward-district').html(response);
    }
  }); 
});


$(document).on('click', '[name=addNewElection]', function(e){
    e.preventDefault();
    var voteName = $('#voat-name').val();
    var district = $('#select-district').val();
    var districtTitle = $('#select-district option:selected').data('district-title');

    var wardName = Array.from(document.querySelector('#ward-district').options).filter(o => o.selected).map(o => o.dataset.ward);
    var voatWard = $('#ward-district').val();
    var startTime = $('#start-time').val();
    var endTime = $('#end-time').val();

    $.ajax({
      type: 'POST',
      url: 'includes/ajax_handel.php',
      data: {'voatWard':voatWard, 'district_id':district, 'voteName':voteName, 'startTime':startTime,'endTime':endTime, 'districtTitle':districtTitle, 'wardName':wardName },
      dataType: 'html',
      success: function(response){
        if(response== 'success'){
          alert('A new election is created');
          location.reload();
        } else {
          alert(response);
        }
      }
    });
    
});




</script>
</body>
</html>
