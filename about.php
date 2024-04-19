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
    <link rel="stylesheet" href="PageStructure.css">
    <title>Phishing</title>
    
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

    <!-- Main Content -->
    <main>
    <section class="content-block">
        <?php 
        include_once 'content.php';
        ?>
        <h2><?php echo $aboutContentH1; ?></h2>
        <p><?php echo $aboutContentP1; ?></p>
        <h2><?php echo $aboutContentH2; ?></h2>
        <p><?php echo $aboutContentP2; ?></p>
        <h2><?php echo $aboutContentH3; ?></h2>
        <p><?php echo $aboutContentP3; ?></p>
        <h2><?php echo $aboutContentH4; ?></h2>
        <ul>
            <?php
            // Explode the heredoc string by newline
            $listItems = explode("\n", $aboutContentList);
            foreach ($listItems as $item) {
                // Trim any leading/trailing whitespace
                $item = trim($item);
                // Output each item as a list item
                echo "<li>$item</li>"; 
            }
            ?>
        </ul>
        <h2><?php echo $aboutContentH5; ?></h2>
        <p><?php echo $aboutContentP4; ?></p>
        <?php echo $aboutContentLink; ?>
    </section>
    </main>
</body>
</html>