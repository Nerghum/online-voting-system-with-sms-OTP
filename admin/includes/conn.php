<?php
	$conn = new mysqli('localhost', 'root', 'mysql', 'votesystem');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	

//send sms
function sendSms($number, $sms_content){
	// sms sending api
}

?>
