<?php

include 'admin/includes/conn.php';
session_start();
if($_POST['votarId']){
	$voter = $_POST['votarId'];
	$sql = "SELECT * FROM voters WHERE voters_id = '$voter' OR phone = '$voter'";
	$query = $conn->query($sql);

	if($query->num_rows < 1){
		echo "votar_id_error";
	} else {
		
		$row = $query->fetch_assoc(); 
		//election query
		$sql = "SELECT * FROM election WHERE status = 'active'";
		$query = $conn->query($sql);
		$data = $query->fetch_all(MYSQLI_ASSOC);
	

		foreach($data as $single){
			if($single['id'] == $row['submited_vote']){
				$vote_status = 'done';
				break;
			}
		}

		if($vote_status == 'done'){
			echo "vote_done";
		}else{
			if($row['phone'] != ""){
				$otp = generateRandomString();
				//$otp = '123456';
				sendSms($row['phone'],'Your login OTP is: '.$otp);

				$_SESSION["otp"] = $otp;
				echo "send_ok";
				
	//		}	}
	}
}


//OTP Random Key Generator
function generateRandomString($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}








