<?php
include_once 'dbh.inc.php';
session_start();
// Check if User_ID is set in the session
if (!isset($_SESSION['AdminName'])) {
    http_response_code(401);
    // Redirect to a generic error page
    header("Location: error.php"); 
    exit; // Stop further execution
}
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" href="register.css" type="text/css" />	<!--Link to stylesheet--->
<link rel="stylesheet" href="PageStructure.css">

</head>
<nav>
        <img src="PhishGuardian.png" alt="PhishGuardian Logo">
    <div class="left-links">
        <a href="logout.php">Logout</a> 
        <a href="adminEditor.php">Manage User</a>
        <a href="adminPage.php">Add Question</a>
        <a href="displayQuestions.php">Edit Questions</a>
        <a href="editContent.php">Edit Content</a>
        <a href="add_admin.php">Add Admin</a>
    </div>
    </nav>

<body>
	<div class ="form">

<form action="addQuestions.php" method="POST" enctype="multipart/form-data">
	<h2>Add a Question</h2>
	<fieldset>											<!--Fieldset to split information into different sections on form--->
	<legend>Question</legend>
	<div class = "inputbox">							
	<label for="QuestionText">Enter Question:</label>
	<input type="text" name="QuestionText" id="QuestionText"/>	
	</div>
	<div class = "inputbox">							
	<label for="Ans1">Answer 1:</label>
	<input type="text" name="Ans1" id="Ans1"/>	
	</div>
	<div class = "inputbox">							
    <label for="Ans2">Answer 2:</label>
    <input type="text" name="Ans2" id="Ans2"/>	
    </div>
    <div class = "inputbox">							
    <label for="Ans3">Answer 3:</label>
    <input type="text" name="Ans3" id="Ans3"/>	
    </div>
    <div class = "inputbox">							
    <label for="Ans4">Answer 4:</label>
    <input type="text" name="Ans4" id="Ans4"/>	
    </div>
    <div class = "inputbox">							
    <label for="CorrectAns">Correct Answer:</label>
    <input type="text" name="CorrectAns" id="CorrectAns"/>	
    </div>
	
	</fieldset>
	
<div class="mybutton">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
<input type="submit" value="Submit" name="submit" id="submit" /><br>
<a href="adminEditor.php" class="back-button">Back</a><br>				
<input type="reset" value="Clear" name="reset" id="submit" />
</div>
</form>
</div>
</body>
</html>