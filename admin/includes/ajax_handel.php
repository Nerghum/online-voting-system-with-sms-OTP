<?php
include 'conn.php';

// add district
if(isset($_POST['district'])){
	$district = $_POST['district'];
	$sql = "INSERT INTO district (name) VALUES ('$district')";
	if($conn->query($sql)){
		echo 'success';
	}
	else{
		echo $conn->error;
	}
}

// add word

if(isset($_POST['districtId']) && isset($_POST['ward'])){
	$districtId = $_POST['districtId'];
	$ward = $_POST['ward'];
	$sql = "INSERT INTO ward (name, district_id) VALUES ('$ward', '$districtId')";
	if($conn->query($sql)){
		echo 'success';
	}
	else{
		echo $conn->error;
	}
}

// word query from district
if(isset($_POST['getDistrictWord'])){
	$id = $_POST['getDistrictWord'];
	$ward_sql = "SELECT * FROM ward WHERE district_id = '$id'";
	$wardquery = $conn->query($ward_sql);
  	$output = "<option>Select</option>";
	while($ward = $wardquery->fetch_assoc()){
    	$ward_name .= $ward['name']. ", "; 

    	$output .= '<option data-ward="'.$ward["name"].'" value="'.$ward["id"].'">'.$ward["name"].'</option>';
	} 
	echo $output;
}

// create new election
if(isset($_POST['voteName']) && isset($_POST['district_id']) && isset($_POST['voatWard']) &&  isset($_POST['startTime']) &&  isset($_POST['endTime']) ){
	$title = $_POST['voteName'];
	$district = $_POST['district_id'];
	$districtTitle = $_POST['districtTitle'];
	$ward = $_POST['voatWard'];
	$start_time = $_POST['startTime'];
	$end_time = $_POST['endTime'];
	$status = 'active';
	$wardName = $_POST['wardName'];
	for($i = 0; $i < count($ward) ; $i++){
		$ward_data[$i]['id'] = $ward[$i];
		$ward_data[$i]['name'] = $wardName[$i];
	}
	
	$ward_data = serialize($ward_data);
	$district_data = serialize(array($district, $districtTitle));
	$sql = "INSERT INTO election (title, district, ward, start_time, end_time, status) VALUES ('$title', '$district_data','$ward_data','$start_time','$end_time','$status')";
	
	if($conn->query($sql)){
		echo 'success';
	}
	else{
		echo $conn->error;
	}

}

//assign candidate to election
if(isset($_POST['action']) && $_POST['action'] == 'assign-election-post'){
	$candidadeId	= $_POST['candidadeId'];
	$positionId		= $_POST['positionId'];
	$wardId			= serialize($_POST['wardId']);
	$electionId		= $_POST['electionId'];
	$districtId		= $_POST['districtId'];
	$ward_name		= serialize($_POST['wardName']);
	$meta = serialize(array(
		'candidade_name'	=> $_POST['candidadeName'],
		'district_name' 	=> $_POST['districtName'],
		'position_title' 	=> $_POST['positionName'],
		'ward_name'			=> $ward_name,
	));
	$sql = "INSERT INTO election_meta (candidate_id, position_id, ward_id, election_id, district_id, vote_count, meta) VALUES ('$candidadeId', '$positionId', '$wardId', '$electionId', '$districtId', 0, '$meta')";

	if($conn->query($sql)){
		echo 'success';
	}
	else{
		echo $conn->error;
	} 
}
// remove election with meta from database
if(isset($_POST['action']) && $_POST['action'] == 'removeElection'){
	$id = $_POST['id'];
	$sql = "DELETE FROM election WHERE id = '$id'";
	if($conn->query($sql)){
		$meta_sql = "DELETE FROM election_meta WHERE election_id = '$id'";
		if($conn->query($meta_sql)){
			echo 'success';
		} else {
			echo 'error 404';
		}
	}
	else{
		echo $conn->error;
	}
}


// remove district and ward
if(isset($_POST['action']) && $_POST['action'] == 'delete-area'){
	$id = $_POST['id'];
	$sql = "DELETE FROM district WHERE id = '$id'";
	if($conn->query($sql)){
		$meta_sql = "DELETE FROM ward WHERE district_id = '$id'";
		if($conn->query($meta_sql)){
			echo 'success';
		} else {
			echo 'error 404';
		}
	}
	else{
		echo $conn->error;
	}
}


//status change
if(isset($_POST['action']) && $_POST['action'] == 'status_change'){

	$status = $_POST['status'];
	$id = $_POST['id'];
	
	$sql = "UPDATE election SET status ='$status' WHERE id=$id";
	
	if($conn->query($sql)){
		echo 'success';
	}
}


return false;