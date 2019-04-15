<?php
	include_once 'db.php';
	
	//header("Content-Type: application/json; charset=UTF-8");
	$myObj = new stdClass();

	if(isset($_GET['signupbtn'])){
		$email 	= $_GET['email'];
		$pass1 	= $_GET['psw'];
		$pass2 	= $_GET['psw-repeat'];
		$fname 	= $_GET['fname'];
		$lname 	= $_GET['lname'];

		// Check connection
		if ($conn->connect_error) {
			//die("Connection failed: " . $conn->connect_error);
			$myObj->user_registration = die("Connection failed: " . $conn->connect_error);
		} 		
		if($pass1!=$pass2){
			$myObj->user_registration =	"password did not match!";
		} else {
			//check if email exist and return boolean
			$sql = "SELECT email FROM tbl_users WHERE email = '$email' ";         
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				//echo "account exist!";
				$myObj->user_registration = "account exist"; 
			} else {
				//create user if false and send notif
					//generate hash key
					$registration_hashkey = md5($email);
					$pass_hashkey = md5($pass2);
					
					//insert to new user and activation hash
					$sql = "INSERT INTO tbl_users( email, fname, lname, pass_hashkey, registration_hashkey) VALUES ('$email','$fname','$lname','$pass_hashkey','$registration_hashkey');";
					if (mysqli_query($conn, $sql)) {
						//echo "registration has been successfull and activation link has been sent to your email!";
						$myObj->user_registration = "successfull"; 
							//send notif
							include_once 'custom_mailer.php';
							//sendmail_activation_hashkey($from, $to, $subject, $body, $registration_hashkey)
							$m = new MyMail();
							//$result = $m->sendmail_registration_hashkey('donotreply@krakenjr.com', $email, 'Activation Link', $registration_hashkey);
							$result = $m->sendmail_user_registration_activation($email, $registration_hashkey);
					} else {
						//echo "Error: " . $sql . "" . mysqli_error($conn);
						$myObj->user_registration = "Error: " . $sql . "" . mysqli_error($conn);				
					}//	
			}
		}//
		//echo json_encode($myObj);
		//close db connection
		$conn->close();
	}//
?>

