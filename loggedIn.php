<!DOCTYPE html>
<html lang="en">
<head>
<?php
include_once 'dbh.inc.php';
include_once "decrypt.php";
include_once 'dbhKey.inc.php';
session_start();
// Check if User_ID is set in the session
if (!isset($_SESSION['User_ID'])) {
    // Set HTTP response code to 401
    http_response_code(401);

    // Redirect to a generic error page
    header("Location: error.php"); // Change "error.php" to the path of your generic error page
    exit; // Stop further execution
}
$FK_USER_ID = $_SESSION['User_ID'];
$stmt = $conn2->prepare("SELECT keyData FROM KeyStore WHERE User_ID = ?");
$stmt->bind_param("s", $FK_USER_ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Get the first row of data as an associative array
    $row = $result->fetch_assoc();
    
    // Access the keyData column to get the value
    $key = $row['keyData'];
    $keyBin = hex2bin($key);
}

$sql = "SELECT Username FROM User WHERE User_ID = $FK_USER_ID";
$result = mysqli_query($conn, $sql);

$sql = "SELECT Username, Photo FROM User WHERE User_ID = $FK_USER_ID";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$username = $row['Username'];
$photo = $row['Photo'];
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">
    <title>User Profile - PhishGuardian</title>
</head>
<body>

<nav>
    <!-- Logo added to the navigation bar -->
    <img src="PhishGuardian.png" alt="PhishGuardian Logo">
    <div class="left-links">
        <!-- Dynamic navigation links -->
        <a href="HomePage.php">Home</a>
        <a href="about.php">About</a>
        <a href="phishMain.php">Phishing</a>
        <a href="get_answers.php">Quiz</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="resources.php">Useful Links</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="right-links">
        <!-- Display username -->
        <a href="loggedIn.php" class="profile-link"><?php echo $username; ?></a>
        <!-- Display user photo if available -->
        <?php if ($photo) { ?>
            <img src="data:image/jpg;base64,<?php echo base64_encode(decrypt_data($photo, $keyBin)); ?>" alt="Avatar" class="avatar">
        <?php } ?>
    </div>
</nav>
    <div class="profile-container">
        <div class="user-details">
            <h2 class="section-header">User Details</h2>
            <!-- Display user details here -->
            <table class="user-details-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>DOB</th>
                        <th>    </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include_once 'dbh.inc.php';
                        include_once "decrypt.php";
                        include_once 'dbhKey.inc.php';
                   
                        $FK_USER_ID = $_SESSION['User_ID'];
                        $stmt = $conn2->prepare("SELECT keyData FROM KeyStore WHERE User_ID = ?");
                        $stmt->bind_param("s", $FK_USER_ID);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Get the first row of data as an associative array
                            $row = $result->fetch_assoc();
                            
                            // Access the keyData column to get the value
                            $key = $row['keyData'];
                            $keyBin = hex2bin($key);
                        }

                        $sql = "SELECT Username, Phone_Num, Email, DOB FROM User WHERE User_ID = $FK_USER_ID";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['Username']}</td>";
                            echo "<td>" . decrypt_data($row['Phone_Num'], $keyBin) . "</td>";
                            echo "<td>" . decrypt_data($row['Email'], $keyBin) . "</td>";
                            echo "<td>" . decrypt_data($row["DOB"], $keyBin) . "</td>"; // Pass $keyBin
                            echo "<td>";
                            echo "<a href='userEditor.php?id=" . $FK_USER_ID . "'>Edit</a> ";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="avatar-section">
            <h2 class="section-header">Avatar</h2>
            <!-- Display avatar here -->
            <?php
                $sql = "SELECT Photo FROM User WHERE User_ID = $FK_USER_ID";
                $result4 = mysqli_query($conn, $sql);
        
                $data4 = array();
                while ($row4 = mysqli_fetch_assoc($result4)) {
                    $data4[] = $row4;
                }
                foreach ($data4 as $row4) { 
            ?>
            <img src="data:image/jpg;base64,<?php echo base64_encode(decrypt_data($row4['Photo'], $keyBin)); ?>" alt="Avatar" class="avatar2">
            <?php } ?>
        </div>
        <div class="points-section">
            <h2 class="section-header">Points Earned</h2>
            <!-- Display points earned here -->
            <table class="points-table">
                <thead>
                    <tr>
                        <th>Attempt Number</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql_points = "SELECT Points FROM Quiz_Points WHERE User_ID = $FK_USER_ID";
                        $result_points = mysqli_query($conn, $sql_points);

                        $attempt_number = 1;
                        while ($row_points = mysqli_fetch_assoc($result_points)) {
                            echo "<tr>";
                            echo "<td>{$attempt_number}</td>";
                            echo "<td>{$row_points['Points']}</td>";
                            echo "</tr>";
                            $attempt_number++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>


