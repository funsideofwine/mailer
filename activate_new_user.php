<?php
	//header("Content-Type: application/json; charset=UTF-8");
	$myObj = new stdClass();

	include_once 'db.php';	
	if(isset($_GET['email']) && isset($_GET['code'])){		
		//echo "done! activated!";			
		$email = $_GET['email'];
		$registration_hashkey = $_GET['code'];		
		// Check connection
		if ($conn->connect_error) {
			//die("Connection failed: " . $conn->connect_error);
			$myObj->user_activation = die("Connection failed: " . $conn->connect_error);
		} 
		//check if email exist and return boolean
		$sql = "SELECT * FROM tbl_users WHERE email = '$email' AND registration_hashkey = '$registration_hashkey' ";			 
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			//echo "found record! ";
				$sql = "SELECT * FROM tbl_users WHERE email = '$email' AND registration_hashkey = '$registration_hashkey' AND activation_status = 1 ";			 
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					//echo "account already activated!";
					$myObj->user_activation = "account already activated!";					
				} else {
					//check if email exist and return boolean
					$sql = "UPDATE tbl_users SET activation_status = '1', registration_hashkey = '' WHERE email = '$email' AND registration_hashkey = '$registration_hashkey' AND activation_status = 0 ";
					if ($conn->query($sql) === TRUE) {
						//echo "account has been activated successfully!";
						$myObj->user_activation = "account has been activated successfully!";
							/////////////////////////////////////////////////////////////////////////////////////////////////////
							/////////////////////////////////////////////////////////////////////////////////////////////////////
							/////////////////////////////////////////////////////////////////////////////////////////////////////
							$sql = "SELECT * FROM tbl_users WHERE email = '$email' ";	
							$result = $conn->query($sql);
							$row = $result->fetch_assoc();
								//send notif
								include_once 'custom_mailer.php';
								$m = new MyMail();
								//sendmail_welcome_email($from, $to, $subject, $fname, $lname)
								//$result = $m->sendmail_welcome_email('donotreply@krakenjr.com', $email, 'Welcome!', ucfirst($row['fname']), ucfirst($row['lname']));
								$result = $m->sendmail_user_registration_welcome($email, ucfirst($row['fname']), ucfirst($row['lname']));
							/////////////////////////////////////////////////////////////////////////////////////////////////////
							/////////////////////////////////////////////////////////////////////////////////////////////////////
							/////////////////////////////////////////////////////////////////////////////////////////////////////
					} else {
						//echo "Error updating record: " . $conn->error;
						$myObj->user_activation = "Error updating record: " . $conn->error;
					}	
				}//
		} else {
			$myObj->user_activation = "Invalid activation code!";	
		}//
		
		//echo json_encode($myObj);
		echo $myObj;
		//close db connection
		$conn->close();	
	}//end