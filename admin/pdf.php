<?php include 'includes/session.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

  <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

  <script type="text/javascript">
  
  function show_result(data , candidate){

    data = JSON.parse(data);
    
   
    var ward_id = Object.keys(data);
    
    for(var i = 0; i < Object.keys(data).length ; i++){
      
      $("#candidate-"+candidate+ " #candidate-ward-"+ward_id[i]).html(data[ward_id[i]]);
    
    } 


  }
</script>

</head>
<body>

<section id="content">
  <div class="border"></div>

<!-- single result -->
<?php
if(isset($_GET['action']) && $_GET['action'] == 'candidade'){

$election_id = $_GET['election_id'];
$candidate = $_GET['candidate_id'];
$ward_id =  $_GET['candidate'];

//election query
$sql = "SELECT * FROM election WHERE id = '$election_id'";
$query = $conn->query($sql);
$data = $query->fetch_assoc();
$district = unserialize($data['district'])[0];

//meta data query
$meta_sql = "SELECT * FROM election_meta WHERE election_id = '$election_id' AND candidate_id = '$candidate'";
$meta_query = $conn->query($meta_sql);
$meta_data = $meta_query->fetch_assoc();

$candidate_data = unserialize($meta_data['meta']);

$candidate_wards = unserialize(unserialize($meta_data['meta'])['ward_name']);
$candidate_vote_wards = unserialize($meta_data['vote_meta']);

$ward_name = '';
foreach($candidate_wards as $ward){
 $ward_name .= $ward . ',';
}


$ward = unserialize($data['ward']);

$ward_result = '<tr><td>Area Name</td>';
for($i = 0; $i < count($ward); $i++){
  for($j = 0; $j < count($candidate_vote_wards); $j++ ){
    if(array_keys($candidate_vote_wards)[$j] == $ward[$i]['id']){
      $ward_result .= '<td>' . $ward[$i]['name'] . '</td>';
    }
  }
}
$ward_result .= '<td>Total votes received</td></tr>';

$ward_result .= '<tr><td>Total votes</td>';
for($i = 0; $i < count($ward); $i++){
  for($j = 0; $j < count($candidate_vote_wards); $j++ ){
    if(array_keys($candidate_vote_wards)[$j] == $ward[$i]['id']){
      $ward_result .= '<td>' . $candidate_vote_wards[$ward[$i]['id']] . '</td>';
    }
  }
}
$ward_result .= '<td>'.$meta_data['vote_count'].'</td></tr>';
?>

<div class="content">
  <h1 class="title"><?= $data['title'] ?></h1>

<table style="width:60%;margin:auto;">
  <tr>
    <th>State </th>
    <td><?= unserialize($data['district'])[1] ?></td>
    <th>Area: </th>
    <td><?=  substr($ward_name, 0, -1) ?></td>
  </tr>
  <tr>
    <th>Candidade name: </th>
    <td><?= $candidate_data['candidade_name'] ?></td>
    <th>Position: </th>
    <td><?= unserialize($meta_data['meta'])['position_title'] ?></td>
  </tr>
</table>

<p style="text-align: center;font-size: 18px;font-weight: bold;color: #555555;">Time: <?=  date('d/m/Y', strtotime($data['start_time'])) ?></p>
<hr>
<h2>Result</h2>

<table class="data-table">
  <?= $ward_result ?>
</table>
</div>

<?php } ?>



<!-- single result -->
<?php

if(isset($_GET['action']) && $_GET['action'] == 'position'){

$election_id = $_GET['election_id'];
$candidate = $_GET['candidate_id'];
$ward_id =  $_GET['candidate'];

//election query
$sql = "SELECT * FROM election WHERE id = '$election_id'";
$query = $conn->query($sql);
$data = $query->fetch_assoc();
$district = unserialize($data['district'])[0];

//meta data query
$meta_sql = "SELECT * FROM election_meta WHERE election_id = '$election_id' AND candidate_id = '$candidate'";
$meta_query = $conn->query($meta_sql);
$meta_data = $meta_query->fetch_assoc();

$candidate_data = unserialize($meta_data['meta']);

$candidate_wards = unserialize(unserialize($meta_data['meta'])['ward_name']);
$candidate_vote_wards = unserialize($meta_data['vote_meta']);

$ward_name = '';
foreach($candidate_wards as $ward){
 $ward_name .= $ward . ',';
}


$ward = unserialize($data['ward']);

$ward_result = '<tr><td>Area Name</td>';
for($i = 0; $i < count($ward); $i++){
  for($j = 0; $j < count($candidate_vote_wards); $j++ ){
    if(array_keys($candidate_vote_wards)[$j] == $ward[$i]['id']){
      $ward_result .= '<td>' . $ward[$i]['name'] . '</td>';
    }
  }
}
$ward_result .= '<td>Total votes received</td></tr>';

$ward_result .= '<tr><td>Total votes</td>';
for($i = 0; $i < count($ward); $i++){
  for($j = 0; $j < count($candidate_vote_wards); $j++ ){
    if(array_keys($candidate_vote_wards)[$j] == $ward[$i]['id']){
      $ward_result .= '<td>' . $candidate_vote_wards[$ward[$i]['id']] . '</td>';
    }
  }
}
$ward_result .= '<td>'.$meta_data['vote_count'].'</td></tr>';
?>

<div class="content">
  <h1 class="title"><?= $data['title'] ?></h1>

<table style="width:60%;margin:auto;">
  <tr>
    <th>State: </th>
    <td><?= unserialize($data['district'])[1] ?></td>
    <th>Area: </th>
    <td><?=  substr($ward_name, 0, -1) ?></td>
  </tr>
  <tr>
    <th>cCandidade Name: </th>
    <td><?= $candidate_data['candidade_name'] ?></td>
    <th>Position:</th>
    <td><?= unserialize($meta_data['meta'])['position_title'] ?></td>
  </tr>
</table>

<p style="text-align: center;font-size: 18px;font-weight: bold;color: #555555;">Time: <?=  date('d/m/Y', strtotime($data['start_time'])) ?></p>
<hr>
<h2>Result</h2>

<table class="data-table">
  <?= $ward_result ?>
</table>
</div>

<?php } ?>





<!-- position result -->
<?php

if(isset($_GET['action']) && $_GET['action'] == 'all'){

$election_id = $_GET['election_id'];

//election query
$sql = "SELECT * FROM election WHERE id = '$election_id'";
$query = $conn->query($sql);
$data = $query->fetch_assoc();
$district = unserialize($data['district'])[0];

//meta data query
$meta_sql = "SELECT * FROM election_meta WHERE election_id = '$election_id'";
$meta_query = $conn->query($meta_sql);
$meta_data = $meta_query->fetch_all(MYSQLI_ASSOC);


//ward query
$ward_sql = "SELECT * FROM ward WHERE district_id = '$district'";
$ward_query = $conn->query($ward_sql);
$ward_data = $ward_query->fetch_all(MYSQLI_ASSOC);

foreach($ward_data as $single_ward){
  $show_ward .= $single_ward['name'].',';
  $show_ward_table .= '<th data-ward-id="'.$single_ward['id'].'">'.$single_ward['name'].'</th>';
  $show_ward_result .= '<th id="candidate-ward-'.$single_ward['id'].'"> </th>';
}



foreach($meta_data as $candidate){

  $show_candidate_data[unserialize($candidate['meta'])['position_title']] .= '
  <tr id="candidate-'.$candidate['candidate_id'].'">
    <th>'.unserialize($candidate['meta'])['candidade_name'].' <span class="position-details">'.unserialize($candidate['meta'])['position_title'].'</span></th>
    '.$show_ward_result.'
    <th>'.$candidate['vote_count'].'</th>
  </tr>

  <script>show_result(\''.json_encode(unserialize($candidate['vote_meta'])).'\', '.$candidate['candidate_id'].');
  </script>
  ';

}


?>

<div class="content">
  <h1 class="title"><?= $data['title'] ?></h1>

  <table style="width:60%;margin:auto;">
    <tr>
      <th>State: </th>
      <td><?= unserialize($data['district'])[1] ?></td>
      <th>Area: </th>
      <td><?= substr($show_ward, 0, -1) ?></td>
    </tr>
    <tr>
      <th>Start: </th>
      <td><?= date('d/m/Y', strtotime($data['start_time']))?></td>
      <th>End: </th>
      <td><?= date('d/m/Y', strtotime($data['end_time'])) ?></td>
    </tr>
  </table>

<hr>
<h2>Result</h2>

<table class="data-table">
  <tr>
    <th>Name</th>
    <?= $show_ward_table ?>
    <th>Total</th>
  </tr>
  <?php

foreach($show_candidate_data as $single){
  echo $single;
}




  ?>

</table>
</div>

</div>





<?php } ?>




<div class="border mt-20"></div>
</section>
<style type="text/css">
  table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

.data-table tr:nth-child(even) {
  background-color: #c8d6e5;
}

.data-table tr:nth-child(odd) {
  background-color: #bbbbbb;
}
.data-table tr:nth-child(1) {
  background-color: #2980b9;
}
t.data-table r:nth-child(1) th{
  color: #fff ;
}

#content{
  border-left: 5px solid #3498db;
}

.border{
  width: 100%;
  height: 30px;
  background-color: #3498db;
}
.content{
  margin-left: 20px;
}
.mt-10{
  margin-top: 10px;
}
.mt-20{
  margin-top: 20px;
}
.title{
  text-align: center;
  color: #22a6b3;
  font-size: 35px;
  text-shadow: 1px 1px 3px #ccc;
}
.position-details{
  float: right;
  background: #fff;
  padding: 2px 5px;
  border-radius: 5px;
  color: #34495e;
}
.myButton {
  box-shadow: 0px 10px 14px -7px #276873;
  background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
  background-color:#599bb3;
  border-radius:8px;
  display:inline-block;
  cursor:pointer;
  color:#ffffff;
  font-family:Arial;
  font-size:20px;
  font-weight:bold;
  padding:13px 32px;
  text-decoration:none;
  text-shadow:0px 1px 0px #3d768a;
}
.myButton:hover {
  background:linear-gradient(to bottom, #408c99 5%, #599bb3 100%);
  background-color:#408c99;
}
.myButton:active {
  position:relative;
  top:1px;
}
</style>


<div style="display: block;text-align: center;margin: 20px 0 0 0;"><a href="#" onclick="downloads(); return false;" class="myButton">Download</a></div>


<script type="text/javascript">
  function downloads(){
    const element = document.getElementById('content');
    html2pdf().from(element).save();
  }
</script>


</body>
</html>
