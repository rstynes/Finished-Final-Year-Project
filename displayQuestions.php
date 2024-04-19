<?php
// Define database connection parameters
include_once 'dbhQuiz.inc.php';

// Fetch all questions from the database
$sql = "SELECT * FROM Quiz";
$result = $conn3->query($sql);

// Array to store fetched questions
$questions = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="PageStructure.css">
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
    <title>Edit Questions</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h2>Edit Questions</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Answer 1</th>
            <th>Answer 2</th>
            <th>Answer 3</th>
            <th>Answer 4</th>
            <th>Correct Answer</th>
            <th>Action</th>
        </tr>
        <?php foreach ($questions as $question): ?>
            <tr>
                <td><?php echo $question['QuizID']; ?></td>
                <td><?php echo $question['QuestionText']; ?></td>
                <td><?php echo $question['Ans1']; ?></td>
                <td><?php echo $question['Ans2']; ?></td>
                <td><?php echo $question['Ans3']; ?></td>
                <td><?php echo $question['Ans4']; ?></td>
                <td><?php echo $question['CorrectAns']; ?></td>
                <td><a href="questionEditor.php?id=<?php echo $question['QuizID']; ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
