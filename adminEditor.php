<?php
session_start();

// Include necessary files
include_once 'dbh.inc.php';
include_once 'dbhKey.inc.php';
include_once 'decrypt.php';

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
    <link rel="stylesheet" href="PageStructure.css">
<title>Website Feedback</title>
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
    <title>Admin Panel</title>
    <link rel="stylesheet" href="adminEditor.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <div class="admin-actions">
        <a href="RegisterPage.php">Add User</a>
    </div>
    <div class="user-table">
        <h2>User List</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetch users from the database
            $sql = "SELECT User_ID, Username, Email, Phone_Num, DOB FROM User";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Fetch the encryption key for the current user
                    $FK_USER_ID = $row['User_ID'];
                    $stmt = $conn2->prepare("SELECT keyData FROM KeyStore WHERE User_ID = ?");
                    $stmt->bind_param("s", $FK_USER_ID);
                    $stmt->execute();
                    $result_key = $stmt->get_result();

                    // Check if key data exists for the user
                    if ($result_key->num_rows > 0) {
                        // Get the first row of data as an associative array
                        $row_key = $result_key->fetch_assoc();

                        // Access the keyData column to get the value
                        $key = $row_key['keyData'];
                        $keyBin = hex2bin($key);
                    } else {
                        // Handle the case where key data is not found for the user
                        // You may want to provide default values or display an error message
                        $keyBin = null; // Set to default value or handle appropriately
                    }

                    echo "<tr>";
                    echo "<td>" . $row["User_ID"] . "</td>";
                    echo "<td>" . $row["Username"] . "</td>";
                    echo "<td>" . decrypt_data($row["Email"], $keyBin) . "</td>"; // Pass $keyBin
                    echo "<td>" . decrypt_data($row["Phone_Num"], $keyBin) . "</td>"; // Pass $keyBin
                    echo "<td>" . decrypt_data($row["DOB"], $keyBin) . "</td>"; // Pass $keyBin
                    echo "<td>";
                    echo "<a href='edit_user.php?id=" . $row["User_ID"] . "'>Edit</a> ";
                    echo "<a href='delete_user.php?id=" . $row["User_ID"] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }

            // Close database connections
            $stmt->close();
            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>


