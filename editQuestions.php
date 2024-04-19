<?php
// Include database connection file
include_once 'dbhQuiz.inc.php';
include 'dbh.inc.php';
// Check if User_ID is set in the session
if (!isset($_SESSION['AdminName'])) {
    http_response_code(401);
    // Redirect to a generic error page
    header("Location: error.php"); 
    exit; // Stop further execution
}
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $questionID = $_POST["QuizID"];
    $questionText = $_POST["QuestionText"];
    $ans1 = $_POST["Ans1"];
    $ans2 = $_POST["Ans2"];
    $ans3 = $_POST["Ans3"];
    $ans4 = $_POST["Ans4"];
    $correctAns = $_POST["CorrectAns"];

    // Update question in the database
    $stmt = $conn3->prepare("UPDATE Quiz SET QuestionText=?, Ans1=?, Ans2=?, Ans3=?, Ans4=?, CorrectAns=? WHERE QuizID=?");
    $stmt->bind_param("ssssssi", $questionText, $ans1, $ans2, $ans3, $ans4, $correctAns, $questionID);
    $stmt->execute();
    
    // Redirect back to the question editor page
    header('Location: questionEditor.php');
    exit;
} else {
    // If form is not submitted, redirect to the question editor page
    header('Location: questionEditor.php');
    exit;
}
// Close the database connection
$stmt->close();
$conn3->close();
?>
