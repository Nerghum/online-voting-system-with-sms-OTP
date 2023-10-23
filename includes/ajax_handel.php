<?php 
include 'conn.php'; 
session_start();


if(isset($_POST['action']) && $_POST['action'] == 'submit_voat'){

	
	$id = $_SESSION['voter'];
	$data = $_POST['data'];
	$election_id = $_POST['election_id'];
	$sms_content = "Your vote has been accepted. Thank you";
	$voater_ward = $_SESSION['ward_id'];
	
	$ward_data = array();

	foreach($data as $candidate){
		$sql = "SELECT * FROM election_meta WHERE election_id=$election_id AND candidate_id=$candidate";
		$query = $conn->query($sql);

		$query_data = $query->fetch_assoc();

		$vote_ward = $query_data['vote_meta'];



		if(isset($vote_ward)){
			$ward_data = unserialize($vote_ward);

			if(isset($ward_data[$voater_ward])){
				$ward_data[$voater_ward] = $ward_data[$voater_ward]+1;
			} else {
				$ward_data[$voater_ward] = 1;
			}

		} else {
			$ward_data = array(
				$voater_ward => 1 
			);
		}

		$ward_data = serialize($ward_data);
		
		$vote = $query_data['vote_count']+1;

		$sql = "UPDATE election_meta SET vote_count=$vote, vote_meta='$ward_data' WHERE election_id=$election_id AND candidate_id=$candidate";
		if($conn->query($sql)){
			$feedback = 'success';
		}
		else{
			echo $conn->error;
		}
		
	}

	if($feedback == 'success'){
		$sql = "UPDATE voters SET submited_vote=$election_id WHERE id=$id";
		if($conn->query($sql)){
			$feedback = 'success';
			$sql = "SELECT phone FROM voters WHERE id=$id";
			$query = $conn->query($sql);
			$number = $query->fetch_assoc();
			
			sendSms($number['phone'], $sms_content);

		}
		else{
			echo $conn->error;
		}
	}

session_destroy();
echo $feedback;
return false;
}






?>

