<?php
$host = 'localhost';        // Localhost for local development
$user = 'root';             // Default local MySQL username
$pass = '';                 // Often empty on local setups (e.g., XAMPP, WAMP)
$db = 'forwork';  

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$con->set_charset("utf8mb4");