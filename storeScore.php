<?php
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['User_ID'])) {
    // User is logged in, retrieve the user ID
    $user_id = $_SESSION['User_ID'];
    
include_once 'dbh.inc.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve points from the POST request
$points = $_POST['points'];

// Insert the points into the database
$sql = "INSERT INTO Quiz_Points (User_ID, Points, timestamp) VALUES ( ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_id, $points);
$stmt->execute();
$stmt->close();

$conn->close();
    // Redirect to the finish page after storing the score
    header("Location: after-quiz.php");
    exit(); // Stop further execution of the script
} else {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit(); // Stop further execution of the script
}
?>


