<html> 
    <head> 
        <title> My first PHP website </title> 
    </head> 
    <body>
        <h2> Registration Page </h2> 
        <a href="index.php"> Click here to go back <br/><br/>
        <form action="register.php" method="POST">
            Enter Username: <input type="text"
            name="username" required="required" /> <br/>
            Enter password: <input type="password"
            name="password" required="required" /> <br/>
            <input type="submit" value="Register"/>
        </form>
    </body> 
</html>