
<html> 
    <head> 
        <title> My first PHP Website </title>
    </head>
    <body> 
            <h2> Login Page </h2> 
            <a href="index.php"> Click here to go back <br/><br/> 
            <form action="checklogin.php" method="POST">
                Enter Username: <input type="text" 
                name="username" required="required" /> <br/> 
                Enter password: <input type="password" 
                name="password" required="required" /> <br/>
                <input type="submit" value="Login"/>
            </form>
    </body>
</html> 

<?php
if($_SERVER["REQUEST METHOD"] == "POST") {
    $username = mysql_real_escape_string($_POST['username']);
    $password = mysql_real_escape_string($_POST['password']); 

    echo "Username entered is: ". $username . "<br />";
    echo "Password entered is: ". $password;  
}
?>
