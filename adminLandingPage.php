
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }
        .panel {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .panel h2 {
            margin-top: 0;
        }
        .panel a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin!</h1>
        <div class="panel">
            <h2>Admin Panel</h2>
            <a href="adminEditor.php">Update Users</a>
            <a href="adminPage.php">Add Questions</a>
            <a href="displayQuestions.php">Edit Questions</a>
            <a href="editContent.php">Edit Content</a>
            <a href="add_admin.php">Add Admin</a>
        </div>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
