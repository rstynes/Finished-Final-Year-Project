<?php
// Check if question ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect back to the question editor page if ID is not provided
    header('Location: displayQuestions.php');
    exit;
}

// Include database connection file
include_once 'dbhQuiz.inc.php';

// Get the question ID from the URL
$questionID = $_GET['id'];

// Fetch the question data from the database
$stmt = $conn3->prepare("SELECT * FROM Quiz WHERE QuizID = ?");
$stmt->bind_param("i", $questionID);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

// Close the database connection
$stmt->close();
$conn3->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Question</h2>
        <form action="editQuestions.php" method="post">
            <input type="hidden" name="QuizID" value="<?php echo $question['QuizID']; ?>">
            <label for="QuestionText">Question:</label>
            <textarea id="QuestionText" name="QuestionText" rows="4" cols="50"><?php echo $question['QuestionText']; ?></textarea>
            <label for="Ans1">Answer 1:</label>
            <textarea id="Ans1" name="Ans1" rows="2"><?php echo $question['Ans1']; ?></textarea>
            <label for="Ans2">Answer 2:</label>
            <textarea id="Ans2" name="Ans2" rows="2"><?php echo $question['Ans2']; ?></textarea>
            <label for="Ans3">Answer 3:</label>
            <textarea id="Ans3" name="Ans3" rows="2"><?php echo $question['Ans3']; ?></textarea>
            <label for="Ans4">Answer 4:</label>
            <textarea id="Ans4" name="Ans4" rows="2"><?php echo $question['Ans4']; ?></textarea>
            <label for="CorrectAns">Correct Answer:</label>
            <input type="text" id="CorrectAns" name="CorrectAns" value="<?php echo $question['CorrectAns']; ?>">
            <br>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="submit" value="Update Question">
        </form>
    </div>
</body>
</html>


