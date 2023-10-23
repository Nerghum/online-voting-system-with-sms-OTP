<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$id = $_GET['election'];




//election query
$sql = "SELECT * FROM election WHERE id = '$id'";
$query = $conn->query($sql);
$data = $query->fetch_assoc();
$district = unserialize($data['district'])[0];

//meta data query
$meta_sql = "SELECT * FROM election_meta WHERE election_id = '$id'";
$meta_query = $conn->query($meta_sql);
$meta_data = $meta_query->fetch_all(MYSQLI_ASSOC);

//total voter count
$sql = "SELECT id FROM voters WHERE submited_vote=$id";
$queryResult = $conn->query($sql);
$count = count($queryResult->fetch_all(MYSQLI_ASSOC));

//position query
$position_sql = "SELECT * FROM positions";
$position_query = $conn->query($position_sql);
$position_data = $position_query->fetch_all(MYSQLI_ASSOC);

//candidates query
$candidate_sql = "SELECT * FROM candidates";
$candidate_query = $conn->query($candidate_sql);
$candidate_data = $candidate_query->fetch_all(MYSQLI_ASSOC);

//ward query
$ward_sql = "SELECT * FROM ward WHERE district_id = '$district'";
$ward_query = $conn->query($ward_sql);
$ward_data = $ward_query->fetch_all(MYSQLI_ASSOC);

?>

<body class="hold-transition skin-blue sidebar-mini">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Election details
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Candidates</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          <div class="box">
	            <div class="box-body">
	            	<div class="row">
	            		<div class ="col-md-7"><h2><?= $data['title'] ?></h2></div>
	            		<div class="col-md-5" style="text-align: right;margin-top: 20px;font-size: 18px;">
	            			<a href="pdf.php?election_id=<?=$id ?>&action=all" target="_blank" class="all-download-button">Download</a>
	            			<span>Total votes received: <?= $count ?></span> ||
	            			<span>Status:</span>
				          	<select id="election-status">
				          		<option value="active">Active</option>
				          		<option value="done">Done</option>
				          		<option value="stop">Stop</option>
				          	</select>
	            		</div>


	            	</div>
	            	
	            	<div class="row">
	            		<div class="col-md-6">
	            			<div class="small-box bg-aqua">
					            <div class="inner">
					              <h3>State</h3>
					              <h4><?= unserialize($data['district'])[1] ?></h4>
					            </div>
					            <div class="icon">
					              <i class="fa fa-tasks"></i>
					            </div>
				        	</div>
	            		</div>
	            		<div class="col-md-6">
	            			<div class="small-box bg-primary">
					            <div class="inner">
					              <h3>Area</h3>
					              <h4><ul><?php
					               foreach(unserialize($data['ward']) as $ward){
					            		echo '<li>'.$ward['name'].'</li>';
					               }

					           		?></ul></h4>
					            </div>
					            <div class="icon">
					              <i class="fa fa-tasks"></i>
					            </div>
				        	</div>
	            		</div>
	            	</div>

	            	<div class="row">
	            		<div class="col-md-6">
	            			<div class="small-box bg-success">
					            <div class="inner">
					              <h4>Start:  <?=  date('d/m/Y - h:i A', strtotime($data['start_time'])) ?></h4>
					            </div>
				        	</div>
	            		</div>
	            		<div class="col-md-6">
	            			<div class="small-box bg-danger">
					            <div class="inner">
					               <h4>End:  <?=  date('d/m/Y - h:i A', strtotime($data['start_time'])) ?></h4>
					            </div>
				        	</div>
	            		</div>
	            	</div>


	            	<div class="row">
	            		<div class="col-md-12"><h2 class="bg-black text-center" style="padding:10px;">Candidate</h2></div>
	            		<div class="box-header with-border">
			              <a href="#addNewCandidate" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Select the candidate</a>
			            </div>
	            		 
			            <?php
			            
			            	foreach($position_data as $single){
			            		 $list = '';
			            		foreach($meta_data as $single_meta){										
											$ward_result_list = '';
											
											$single_candidate_ward = unserialize($single_meta['meta']);
											$single_candidate_vote = unserialize($single_meta['vote_meta']);
											
  			if($single['id'] == $single_meta['position_id']){

  				$list .= '<li class="list-group-item list-group-item-success ward-result-list col-md-12">
  				<div class="candidate-name">'.unserialize($single_meta['meta'])['candidade_name'].'</div>
  				'.$ward_single_result.
  				'<div  class="voat-receive">'
  				.$single_meta['vote_count'].
  				'</div><div class="download-pdf"><a target="_blank" href="pdf.php?election_id='.$id.'&candidate_id='.$single_meta['candidate_id'].'&action=candidade">Download</a></div></li>';

  			}


			            		}
			            		

			            	echo '<div class="col-md-6">
			            			<div class="small-box bg-yellow">
							            <div class="inner">
							              <h3>'.$single['description'].'<a href="pdf.php?election_id='.$id.'&position_id='.$single['id'].'&action=position" target="_blank" class="position-download-button">Download</a></h3>
							              <hr>

							              <ul class="list-group">
							              <li class="list-group-item list-group-item-success ward-result-list col-md-12" style="font-weight: bold;"><div class="candidate-name">Name</div>
							              '.$ward_result_list.'
							              <div class="voat-receive">Votes received</div> <div class="download-pdf">Download</div></li>
							              	'.$list.'
							              </ul>
							            </div>
						        	</div>
			            		</div>';
			            		

			            	}

			            ?>     

	            	</div>

	            </div>
	        </div>
	    </div>


<style type="text/css">
	.ward-result-list{
		display: table-cell;
		width: 100%;
	}
	.ward-result-list div{
		padding: 5px 10px;
		display: table-cell;
		min-width: 20px;
		border: 1px solid #fff;
	}
	.candidate-name {
    width: 150px;
  }

.result-ward {
    width: 50px;
    text-align: center;
}
.voat-receive {
    width: 100px;
}

.position-download-button{
	float: right;
  overflow: hidden;
  font-size: 18px;
  font-weight: revert;
  color: #337ab7;
  background: #fff;
  padding: 10px;
  border-radius: 10px;
  margin-top: 2px;
}

.all-download-button{
  font-size: 18px;
  font-weight: revert;
  color: #fff;
  background: #337ab7;
  padding: 10px 20px;
  border-radius: 10px;
}
</style>

  <?php
	include 'includes/footer.php';
   ?>

   <?php include 'includes/scripts.php'; ?>
</div>



<!-- assign modal -->
<div class="modal fade" id="addNewCandidate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add new Area</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="district_add.php">
								<div class="form-group">
                    <label for="select-candidade" class="col-sm-3 control-label">Select the candidate</label>

                    <div class="col-sm-9">
                      <select name="select-candidade" id="select-candidade" class="col-sm-12 form-select form-select-sm" aria-label=".form-select-sm example">
                        <?php 
                        	foreach($candidate_data as $single){
                        		echo '<option value="'.$single['id'].'" data-candidate-name="'.$single['firstname'].' '.$single['lastname'].'">'.$single['firstname'].' '.$single['lastname'].'</option>';
                        	}

                        ?>
                      </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="select-position" class="col-sm-3 control-label">Select Possession</label>

                    <div class="col-sm-9">
                      <select name="select-position" id="select-position" class="col-sm-12 form-select form-select-sm" aria-label=".form-select-sm example">
                        <?php 
                        	foreach($position_data as $single){
                        		echo '<option value="'.$single['id'].'" data-position-name="'.$single['description'].'">'.$single['description'].'</option>';
                        	}

                        ?>
                      </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="select-ward" class="col-sm-3 control-label">Select Area</label>

                    <div class="col-sm-9">
                      <select name="select-ward" multiple id="select-ward" class="col-sm-12 form-select form-select-sm" aria-label=".form-select-sm example">
                        <?php 
                        	foreach($ward_data as $single){
                        		echo '<option value="'.$single['id'].'" data-ward="'.$single['name'].'">'.$single['name'].'</option>';
                        	}

                        ?>
                      </select>
                    </div>
                </div> 

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="assign-candidade"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
        <div class="notic"></div>
    </div>
</div>


<script type="text/javascript">
	
$(document).on('click', '[name=assign-candidade]', function(e){
    e.preventDefault();
    var candidadeId = $('#select-candidade').val();
    var candidadeName = $('#select-candidade option:selected').data('candidate-name');
    var positionId = $('#select-position').val();
    var positionName = $('#select-position option:selected').data('position-name');    
    var wardId = $('#select-ward').val();
    var wardName = Array.from(document.querySelector('#select-ward').options).filter(o => o.selected).map(o => o.dataset.ward);
    var electionId = '<?= $_GET['election'] ?>';
    var districtId = '<?= $district ?>' ;
    var districtName = '<?= unserialize($data['district'])[1] ?>';



    $.ajax({
      type: 'POST',
      url: 'includes/ajax_handel.php',
      data: {'action': 'assign-election-post', 'candidadeId':candidadeId, 'positionId':positionId, 'wardId':wardId, 'electionId':electionId,'districtId':districtId, 'candidadeName':candidadeName, 'positionName': positionName,'wardName': wardName,'districtName':districtName},
      dataType: 'html',
      success: function(response){
        if(response== 'success'){
          alert('Candidate has been added');
          location.reload();
        } else {
          alert(response);
        }
      }
    });
    
});

//status
$("#election-status").change(function(){
    var status = $("#election-status").val();
    $.ajax({
    type: 'POST',
    url: 'includes/ajax_handel.php',
    data: {'action':'status_change','status':status, 'id':<?= $id ?>},
    dataType: 'html',
    success: function(response){
      if(response== 'success'){
        alert('Status updated');
        location.reload();
      } else {
        console.log(response);
      }
    }
  });
});

$('#election-status option[value="<?= $data['status'] ?>"]').prop('selected', true);
</script>


</body>
</html>

