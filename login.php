<?php
   session_start();
   
   //include("inc/config.php");
   
   #echo 'in the php---';
   //var_dump($_SESSION);

   if($_SERVER["REQUEST_METHOD"] == "POST") {
		#echo 'in the php post---';
		//$email = mysqli_real_escape_string($db,$_POST['email']);
		//$key = mysqli_real_escape_string($db,$_POST['key']); 
		$email = strtolower($_POST['email']);
		$key = $_POST['key'];
		
		#echo 'Email: ' . $email;
		#echo 'Key: ' . $key;
		
		$sql = "SELECT USER_ID, FIRST_NAME, PASSWORD, GRADE, ACTIVATION_CODE FROM USER_TABLE WHERE EMAIL = ?";
//       $query = $connection->prepare("SELECT * FROM users WHERE username=:username");
		$db->prepare($sql);
		if ($stmt = $db->prepare($sql)) {
			$stmt->bind_param('s', $email);

			$stmt->execute();

			$stmt->bind_result($user_id, $firstName, $password, $grade, $acode);
			if ($stmt->fetch()){
				if(password_verify($key, $password)){
					if ($acode !="activated"){
					$Inerror = "This account's email has not being verified.";
					echo'<div class="alert alert-danger alert-dismissible" style="width: 30%; text-align: center; padding: 10px 0px; margin: 0px auto -10px auto;"
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>'.$Inerror.'</strong></div>';
						
						$result = 0;
							}
					else{
							$result = $user_id;
						}
				}
				else{
					
					$Inerror = "Your login email or password is invalid.";
					echo'<div class="alert alert-danger alert-dismissible" style="width: 30%; text-align: center; padding: 10px 0px; margin: 0px auto -10px auto;"
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>'.$Inerror.'</strong></div>';
					
					$result = 0;
					
				}
			}
			else{
				$Inerror = "Your login email or password is invalid.";
				echo'<div class="alert alert-danger alert-dismissible" style="width: 30%; text-align: center; padding: 10px 0px; margin: 0px auto -10px auto;"
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>'.$Inerror.'</strong></div>';
					
					$result = 0;
				
			}
			
			$stmt->close();
		}
//		echo 'Result: ' . $result;
		if($result > 0) 
		{
			#session_start();
			//session_register("id");
			$_SESSION['login_user'] = $result;
			$_SESSION['logged_in'] = true;
			$_SESSION['user_fname'] = $firstName;
			$_SESSION['grade'] = $grade;
			session_regenerate_id(true); 
			//var_dump($_SESSION);  
			//echo 'in the succesful login---';
		    if($_SESSION['grade'] == NULL)
		    {header("location: https://app.myhomeworkrewards.com/profile.php");}
			else {header("location: https://app.myhomeworkrewards.com/");}
			exit();
		}
		/*
		else 
		{
			$error = "Your login email or password is invalid.";
			echo'<div class="alert alert-danger alert-dismissible" style="width: 30%; text-align: center; padding: 10px 0px; margin: 0px auto -10px auto;"
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>'.$error.'</strong></div>';
		}*/
   }
   
   //var_dump($_SESSION);
   //mysqli_close($db);
   #echo 'in the php end';
   
?>

<style>
        .nav{margin-top:25px;}
	    .navbar-default {
            background-color: #5B0A9F !important;}	
        .nav.navbar-nav>li>a {color: white;}
        .nav.navbar-nav>li>a:hover {font-size:larger; color:white; background-color:#5B0A9F !important;}
        .navbar-brand{margin-right:160px;}
		h1{font-family: lucida sans;}
	    h2{font-family: lucida sans;}
	    h3{font-family: lucida sans;}
    	.loadbar
        {
             width:400px;
             height:35px;
             background-color:#fff;
             border:1px solid #ccc; 
             color:black;
        }
        .bar
        {
            line-height:25px;        
            height:100%;
            display:block;        
            font-family:arial;
            font-size:12px;
            color:black;
        }
        button
        {
            background-color:#5B0A9F;
        }
</style>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyHomeworkRewards - Sign In</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
  </head>
	<body style="padding-top:150px;" >
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			 <div class="navbar-header" style="height:100px;">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="https://app.myhomeworkrewards.com/"><img src="/images/favicon.png" width="80px" style="margin-bottom:150px;"></img></span></a>
			</div>
		</div>
	</nav>
  
	<div class="container" id="login-container" style="width:50%; min-width: 250px;">		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Sign In</h4>						
			</div>
			<div class="panel-body" style="padding:30px;">
				<form class="form-horizontal" method="post" action="" role="form">
					<div class="form-group">
						<label class="sr-only" for="email">Email address</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
							<input class="form-control" type="email" name="email" id="email" placeholder="Email" autocomplete="on">
						</div>
					</div>
					<div class="form-group">
						<label class="sr-only" for="password">Password</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
							<input class="form-control" type="password" name="key" id="key" placeholder="Password" autocomplete="on"/>
						</div>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox">Remember Me
						</label>
					</div>
					<div>
						<a href="register.php">Register</a>
					</div>
					<button type="submit" class="btn btn-primary pull-right" style="background-color:#037df6;">Submit</button>
				</form>
			</div>
		</div>
	</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>