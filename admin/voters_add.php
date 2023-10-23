<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$phone = $_POST['phone'];
		$voters_id = $_POST['votarId'];
		$district_id = $_POST['select-district'];
		$ward_id = $_POST['ward-district'];
		$filename = $_FILES['photo']['name'];
		$meta = serialize(array(
			'district_name' =>  $_POST['district-name'],
			'ward_name' =>  $_POST['ward-name'],
		));

		if(!empty($filename)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}

		$sql = "INSERT INTO voters (voters_id, phone, firstname, lastname, photo, district_id, ward_id, meta) VALUES ('$voters_id', '$phone', '$firstname', '$lastname', '$filename', '$district_id', '$ward_id', '$meta' )";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Voter added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: voters.php');
?>