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
        Positions
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Positions</li>
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
              <a href="#addnewDistrict" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add new State</a>

              <a href="#addnewWard" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add new Area</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Description</th>
                  <th>Maximum Vote</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT * FROM district";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      
                      $id = $row['id'];
                      $ward_name = '';
                      
                      $ward_sql = "SELECT * FROM ward WHERE district_id = '$id'";
                      $wardquery = $conn->query($ward_sql);
                      
                      while($ward = $wardquery->fetch_assoc()){
                        $ward_name .= $ward['name']. ", "; 
                      } 

                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>".$row['name']."</td>
                          <td>".$ward_name."</td>
                          <td>
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
  <?php include 'includes/area_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  // add district
  $(document).on('click', '[name=addDistrict]', function(e){
    e.preventDefault();
    var district = $('#district').val();
      $.ajax({
        type: 'POST',
        url: 'includes/ajax_handel.php',
        data: {'district':district},
        dataType: 'html',
        success: function(response){
          console.log(response);
          if(response == 'success'){
             $('#addnewDistrict .notic').html(`<div class="alert alert-success">
              State has been successfully added
            </div>`);
             setTimeout(function() {
              $('#addnewDistrict .notic').html();
              $( "[data-dismiss=modal]" ).click();
              location.reload();
             }, 2000);

          }
        }
      });
  });

  $(document).on('click', '[name=addWard]', function(e){
    e.preventDefault();
    var districtId = $('#ward-district').find(":selected").val();
    var ward = $('#ward').val();
      $.ajax({
        type: 'POST',
        url: 'includes/ajax_handel.php',
        data: {'districtId':districtId, 'ward':ward},
        dataType: 'html',
        success: function(response){
          console.log(response);
          if(response == 'success'){
             $('#addnewWard .notic').html(`<div class="alert alert-success">
              Area has been successfully added
            </div>`);
             setTimeout(function() {
              $('#addnewWard .notic').html();
              $( "[data-dismiss=modal]" ).click();
             }, 2000);
             location.reload();
          }
        }
      });
  });
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'positions_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.id').val(response.id);
      $('#edit_description').val(response.description);
      $('#edit_max_vote').val(response.max_vote);
      $('.description').html(response.description);
    }
  });
}

$(function(){

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    alert('The area will be deleted');
    $('#delete').modal('show');
    var id = $(this).data('id');

      $.ajax({
        type: 'POST',
        url: 'includes/ajax_handel.php',
        data: {'action':'delete-area', 'id':id},
        dataType: 'html',
        success: function(response){
          if(response== 'success'){
            alert('The area has been deleted');
            location.reload();
          } else {
            alert(response);
          }
        }
      });
  });

});



</script>
</body>
</html>
