<?php
// Database connection
$servername = "localhost";
$username = "php_docker";
$password = "password";
$dbname = "php_docker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch email addresses from the database
$sql = "SELECT email, name FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each user
    while($row = $result->fetch_assoc()) {
        $to = $row["email"];
        $subject = "Test Email";
        $message = "Hello " . $row["name"] . ",\n\nThis is a test email.";
        $headers = "From: your-email@example.com";

        // Send email
        if(mail($to, $subject, $message, $headers)) {
            echo "Email sent to " . $row["name"] . " (" . $to . ")<br>";
        } else {
            echo "Failed to send email to " . $row["name"] . " (" . $to . ")<br>";
        }
    }
} else {
    echo "No users found.";
}

$conn->close();
?>