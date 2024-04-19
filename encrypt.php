<?php

// Define database connection parameters
include_once 'dbh.inc.php';
include_once 'dbhKey.inc.php';

// Define encryption key and functions
$encryption_key = random_bytes(32);

function encrypt_data($data) {
    global $encryption_key;
    $iv = random_bytes(16);
    $encrypted_data = openssl_encrypt($data, "AES-256-CTR", $encryption_key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted_data);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['Password'];
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    $username = $_POST["Username"];

    // Check if photo is uploaded
    if ($_FILES["Photo"]["error"] === UPLOAD_ERR_OK) {
        $phone_number = encrypt_data($_POST["phone"]);
        $email = encrypt_data($_POST["email"]);
        $dob = encrypt_data($_POST["dob"]);
        $photo = file_get_contents($_FILES["Photo"]["tmp_name"]);
        $encryptPhoto = encrypt_data($photo);

        $key_in_hex = bin2hex($encryption_key);

        // Insert encrypted data into database
        $stmt = $conn->prepare("INSERT INTO User (Username, Phone_Num, Email, DOB, Password, Photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $phone_number, $email, $dob, $hashedPass, $encryptPhoto);
        $stmt->execute();
        $FK_User_ID = mysqli_insert_id($conn);

        $stmt4 = $conn2->prepare("INSERT INTO KeyStore (keyData, USER_ID) VALUES (?, ?)");
        $stmt4->bind_param("ss", $key_in_hex, $FK_User_ID);
        $stmt4->execute();

        // Close database connection
        $stmt->close();
        $conn->close();

        header('Location:index.html');
        exit(); // Add exit to stop executing further code
    } else {
        // Handle error when photo is not uploaded
        echo "Error uploading photo. Please make sure you select a photo.";
        echo "<br><br>";
        echo "<button onclick=\"window.history.back();\">Back</button>"; 
    }
}
?>
