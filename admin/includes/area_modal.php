<?php
include 'conn.php';
$sql = "SELECT * FROM district";
$district_html = '';
$query = $conn->query($sql);
  while($row = $query->fetch_assoc()){
    $district_html .=  '<option value="'.$row['id'].'">'.$row['name'].'</option>';  
     
  }

?>

<!-- Add District-->
<div class="modal fade" id="addnewDistrict">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add new state</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="district_add.php">
                <div class="form-group">
                    <label for="district" class="col-sm-3 control-label">State Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="district" name="district" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="addDistrict"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
        <div class="notic"></div>
    </div>
</div>

<!-- Add Ward -->
<div class="modal fade" id="addnewWard">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add new area</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="district_add.php">
                <div class="form-group">
                    <label for="district" class="col-sm-3 control-label">Select State</label>

                    <div class="col-sm-9">
                      <select name="ward-district" id="ward-district" class="col-sm-12 form-select form-select-sm" aria-label=".form-select-sm example">
                        <?php echo $district_html; ?>
                      </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="district" class="col-sm-3 control-label">Area Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="ward" name="ward" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="addWard"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
        <div class="notic"></div>
    </div>
</div>
