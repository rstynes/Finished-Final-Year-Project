<?php
// Include database connection
include 'dbh.inc.php';
include 'dbhKey.inc.php';

// Check if user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch encryption key for decryption
    $stmt = $conn2->prepare("SELECT keyData FROM KeyStore WHERE User_ID = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if key data exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $encryption_key = hex2bin($row['keyData']);
    } else {
        // Handle the case where key data is not found
        echo "Error: Encryption key not found for user.";
        exit(); // Terminate script
    }

    // Include decryption function
    include 'decrypt.php';

    // Fetch encrypted user details from the database
    $stmt = $conn->prepare("SELECT Username, Phone_Num, Email, DOB FROM User WHERE User_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($username, $phone_encrypted, $email_encrypted, $dob_encrypted);
    $stmt->fetch();

    // Decrypt user details
    $phone = decrypt_data($phone_encrypted, $encryption_key);
    $email = decrypt_data($email_encrypted, $encryption_key);
    $dob = decrypt_data($dob_encrypted, $encryption_key);
} else {
    // Handle the case where user ID is not provided in the URL
    echo "Error: User ID not provided.";
    exit(); // Terminate script
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define encryption key
    $encryption_key = random_bytes(32);

    // Function to encrypt data
    function encrypt_on_update($data, $encryption_key) {
        $iv = random_bytes(16); 
        $encrypted_data = openssl_encrypt($data, "AES-256-CTR", $encryption_key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted_data);
    }

    function get_user_encryption_key($conn2, $user_id) {
        $stmt = $conn2->prepare("SELECT keyData FROM KeyStore WHERE User_ID = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return hex2bin($row['keyData']);
        }
        return null;
    }

    if ($user_id) {
        $encryption_key = get_user_encryption_key($conn2, $user_id);
        if (!$encryption_key) {
            die("Encryption key not found for user.");
        }
    }

    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];

    // Encrypt user data
    $phone_encrypted = encrypt_on_update($phone, $encryption_key);
    $email_encrypted = encrypt_on_update($email, $encryption_key);
    $dob_encrypted = encrypt_on_update($dob, $encryption_key);

    // Update encrypted user data in the database
    $stmt = $conn->prepare("UPDATE User SET Username = ?, Phone_Num = ?, Email = ?, DOB = ? WHERE User_ID = ?");
    $stmt->bind_param("ssssi", $username, $phone_encrypted, $email_encrypted, $dob_encrypted, $user_id);

    if ($stmt->execute()) {
        // Redirect to loggedIn.php after successful update
        header("Location: loggedIn.php");
        exit();
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
    <link rel="stylesheet" href="editUser.css">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$user_id"; ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br><br>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required><br><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" required><br><br>
        <input type="submit" value="Update User">
    </form>
    <div class="mybutton">
        <a href="loggedIn.php" class="back-button"><button type="button">Back</button></a>
    </div>
</body>
</html>



