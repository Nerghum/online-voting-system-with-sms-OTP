<?php
	session_start();
	include 'admin/includes/conn.php';

	if(isset($_POST['otp']) && isset($_POST['nid'])){
		$voter = $_POST['nid'];
		$otp = $_POST['otp'];
		$sql = "SELECT * FROM voters WHERE voters_id = '$voter' OR phone = '$voter'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			echo 'nid-error';
		}
		else{
			$row = $query->fetch_assoc();
			if($otp == $_SESSION['otp']){
				$_SESSION['voter'] = $row['id'];
				$_SESSION['ward_id'] = $row['ward_id'];
				$_SESSION['district_id'] = $row['district_id'];
				echo "login";
			}
			else{
				echo "otp-error";
			}
		}
	}
	else{
		echo "all-error";
	}


?>