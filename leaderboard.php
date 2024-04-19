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
    // Retrieve user's key data from KeyStore
    $FK_USER_ID = $_SESSION['User_ID'];
    $stmt = $conn2->prepare("SELECT keyData FROM KeyStore WHERE User_ID = ?");
    $stmt->bind_param("s", $FK_USER_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    // If key data is found, extract the key
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $key = $row['keyData'];
        $keyBin = hex2bin($key);
    }

    // Retrieve user's username and photo from User table
    $sql = "SELECT Username, Photo FROM User WHERE User_ID = $FK_USER_ID";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $username = $row['Username'];
    $photo = $row['Photo'];
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">
    <title>Quiz Leaderboard - PhishGuardian</title>
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

<div class="leaderboard-container">
    <h2 class="section-header">Quiz Leaderboard - Total Points</h2>
    <table class="leaderboard-table">
        <thead>
        <tr>
            <th>Rank</th>
            <th>Username</th>
            <th>Points</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql_leaderboard = "SELECT User.Username, SUM(Quiz_Points.Points) AS TotalPoints 
                            FROM Quiz_Points 
                            INNER JOIN User ON Quiz_Points.User_ID = User.User_ID 
                            GROUP BY Quiz_Points.User_ID 
                            ORDER BY TotalPoints DESC";
        $result_leaderboard = mysqli_query($conn, $sql_leaderboard);
        $rank = 1;
        while ($row_leaderboard = mysqli_fetch_assoc($result_leaderboard)) {
            echo "<tr>";
            echo "<td>{$rank}</td>";
            echo "<td>{$row_leaderboard['Username']}</td>";
            echo "<td>{$row_leaderboard['TotalPoints']}</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
