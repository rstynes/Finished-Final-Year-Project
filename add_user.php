<?php
// Include database connection
include 'dbh.inc.php';
include 'dbhKey.inc.php';


// Include encryption function
include 'encrypt.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $phone = encrypt_data($_POST['phone']);
    $dob = encrypt_data($_POST['dob']);
    $photo = file_get_contents($_FILES['photo']['tmp_name']);
    $encrypted_photo = encrypt_data($photo);

    // Insert encrypted user data into the database
    $stmt = $conn->prepare("INSERT INTO User (Username, Phone_Num, Email, DOB, Password, Photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $phone, $email, $dob, $password, $encrypted_photo);
    
    if ($stmt->execute()) {
        echo "User added successfully";
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
    <title>Add User</title>
</head>
<body>
    <h1>Add User</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required><br><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>
        <label for="photo">Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*" required><br><br>
        <input type="submit" value="Add User">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    </form>
</body>
</html>

