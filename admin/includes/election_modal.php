<?php
include 'conn.php';
$sql = "SELECT * FROM district";
$district_html = '';
$query = $conn->query($sql);
  while($row = $query->fetch_assoc()){
    $district_html .=  '<option data-district-title="'.$row['name'].'" value="'.$row['id'].'">'.$row['name'].'</option>';  
     
  }

?>

<!-- Add District-->
<div class="modal fade" id="addnewElection">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Create new election </b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="district_add.php">
                <div class="form-group">
                    <label for="voat-name" class="col-sm-3 control-label">Election name </label>

                  <div class="col-sm-9">
                      <input type="text" class="form-control" id="voat-name" name="voat-name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="select-district" class="col-sm-3 control-label">Select the state</label>

                    <div class="col-sm-9">
                      <select name="select-district" id="select-district" class="col-sm-12 form-select form-select-sm" aria-label=".form-select-sm example">
                        <option>Select</option>
                        <?php echo $district_html; ?>
                      </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ward-district" class="col-sm-3 control-label">Select the Area</label>

                    <div class="col-sm-9">
                      <select name="ward-district" id="ward-district" class="col-sm-12 form-select form-select-sm" multiple aria-label=".form-select-sm">
                        
                      </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="start-time" class="col-sm-3 control-label">Start Time:</label>

                  <div class="col-sm-9">
                      <input type="datetime-local" class="form-control" id="start-time" name="start-time" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="end-time" class="col-sm-3 control-label">End Time: </label>

                  <div class="col-sm-9">
                      <input type="datetime-local" class="form-control" id="end-time" name="end-time" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Stop</button>
              <button type="submit" class="btn btn-primary btn-flat" name="addNewElection"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
        <div class="notic"></div>
    </div>
</div>
