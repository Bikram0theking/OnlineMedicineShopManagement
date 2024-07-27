<?php
session_start(); // Start session to store user login state

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password if set
$dbname = "probisuk"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to store form data and error message
$user = $pass = "";
$err_msg = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize username and password inputs
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // SQL query to select user based on username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            // Password is correct, redirect to home.html or any other page
            $_SESSION['username'] = $user; // Store username in session
            header("Location: home.html");
            exit();
        } else {
            // Invalid password
            $err_msg = "Invalid password. Please try again.";
        }
    } else {
        // No user found with that username
        $err_msg = "No user found with that username. Please try again.";
    }
}

// Close connection
$conn->close();

// If there's an error message, redirect back to login.html with an error query parameter
if (!empty($err_msg)) {
    // Use JavaScript to display error message in alert
    echo "<script>alert('" . addslashes($err_msg) . "'); window.location.href = 'login.html';</script>";
    exit();
}
?>
