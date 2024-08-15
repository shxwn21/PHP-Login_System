<?php
#include('inc/session.php');
include('inc/config.php');
error_reporting(E_ALL);
if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	var_dump($_POST);  
	
	
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
	$email = strtolower ($_POST['email']);
	
	#Check if email existis
	
	if($stmt = $db->prepare("SELECT * FROM USER_TABLE WHERE EMAIL = ? ")) {
		$stmt->bind_param("s",$email);
		$stmt->execute();
		$stmt->store_result();
	} else{
		$error = $db->errno . ' ' . $db->error;
		echo $error;
	}
	$count = $stmt->num_rows;
 //	echo 'count:' . $count;
	if($count > 0){
		echo '<script type="text/javascript">alert("Email already registered."); </script>';
		echo '<script type="text/javascript">window.location.assign("http://app.myhomeworkrewards.com/register.php");	</script>';
		#header('location: register.php');
		#window.location.replace(http://hw.myhomeworkrewards.com/register.php);

	}
	else{
		
		
		if(isset($_POST['parent_email'])){
			$parent_email = $_POST['parent_email'];
		}
		else{
			$parent_email = "";
		}
		
		if(isset($_POST['code'])){
			$code = $_POST['code'];
			
			if ($code == "HHUBTUTOR" or $code == "HHUBSTUDENT"){
			    $type = 1;
			}
			else{
			    $type = 0;
			}
		}
		else{
			$code = "";
			$type = 0;
		}
	
		$stmt = $db->prepare("INSERT INTO USER_TABLE 
		(FIRST_NAME, PASSWORD, EMAIL, PARENT_EMAIL, DATE_CREATED, CODE, ACTIVATION_CODE, USER_TYPE, POINTS) 
		VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0)");
	
		$uniqid = uniqid();
		$stmt->bind_param("ssssssi",$_POST['firstName'], $password, $email, $parent_email, $code, $uniqid, $type);
		#var_dump($stmt);
		$stmt->execute();
		$user_id = $db->insert_id;		

		#EMAIL USER FOR ACTIVATION
		
		$from    = 'MyHomeworkRewards@gmail.com'; #Trying to send from gmail fails. From: line in headers removed. 
		$reply    = 'MyHomeworkRewards@gmail.com';
		$subject = 'Account Activation Required';
		$headers = 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
		$activate_link = 'http://app.myhomeworkrewards.com/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
		$message = '<p>Thank you for signing up to MyHomeworkRewards!<br>Your account has been created. 
		            You can login with the following credentials after you have activated your account by pressing the link below. <br>------------------------<br>
		            Email: '.$_POST['email'].'<br>------------------------<br>Please click this link to activate your account and start earning rewards doing homework:
		                <a href="' . $activate_link . '">Activate my account!</a><br>
		                Sign up for the Basic Plan <a href="https://checkout.square.site/merchant/MLT6HG0A0PB78/checkout/SGITXSGO5DD7HWGUQCNWUWHR">here</a> to unlock unlimited questions and even more rewards! 
		                <br>Your referral code is <b>MHR'. $user_id . '</b>. Share this code with friends to both get an additional free month of the Basic Plan (up to 3 months).<br>
		                Have a question? Just reply to this email and we will get back ASAP. Happy learning (and earning)!</p>';
		mail($_POST['email'], $subject, $message, $headers, "-fMyHomeworkRewards@gmail.com");
		#mail("G.Aversano@live.ca", "Test", "Test 123");
		
		#$stmt->store_result();
		#$stmt->bind_result($user_id);
		print_r($stmt->error_list);
		var_dump($stmt);
			
		#$stmt->fetch();
		#$stmt->close();	
			
		
		header('location: /new_user_notice.php');
		exit();

	}
		
}

?>