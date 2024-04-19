<?php
// Include database connection
include 'dbh.inc.php';
include 'dbhKey.inc.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $adminName = $_POST['adminName'];
    $adminPass = password_hash($_POST['AdminPass'], PASSWORD_DEFAULT); // Encrypt the password for security

    // Insert encrypted user data into the database
    $stmt = $conn->prepare("INSERT INTO Admin_Login (AdminName, AdminPass) VALUES (?, ?)");
    $stmt->bind_param("ss", $adminName, $adminPass); // Bind the encrypted password
    
    if ($stmt->execute()) {
        echo "Admin added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
</head>
<body>
    <h1>Add Admin</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="adminName">Username:</label>
        <input type="text" id="adminName" name="adminName" required><br><br>
        <label for="AdminPass">Password:</label>
        <input type="password" id="AdminPass" name="AdminPass" required><br><br>
        <input type="submit" value="Add Admin">
        <!-- Add CSRF token if needed -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    </form>
    <a href="adminLandingPage.php"><button>Back</button></a>
</body>
</html>
