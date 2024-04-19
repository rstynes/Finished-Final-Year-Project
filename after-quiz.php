<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once 'dbh.inc.php';
    include_once "decrypt.php";
    include_once 'dbhKey.inc.php';
    session_start();

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
    <link rel="stylesheet" href="quiz-final.css">
    <title>Award Page</title>
    <style>
        /* Custom CSS for adjusting text size and color */
        .username {
            font-size: 50px;
            color: black;
        }

        .userpoints,
        .correct-answers {
            font-size: 50px;
            color: black;
        }

        /* Adjustments for share buttons */
        .share-buttons {
            margin-top: 20px;
        }

        .share-button {
            display: inline-block;
            margin-right: 20px;
            color: #333;
            text-decoration: none;
            font-size: 24px;
        }

        .share-button:hover {
            color: #007bff; /* Change to your desired hover color */
        }
    </style>
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

<!--AWARD & POINTS-->
<div class="wrapper">
    <div class="border">
        <i class="fas fa-award award_icon" style="margin: 10px 0px 0px 130px; color: gold;"></i>
        <h1 class="username"><span class="name"></span><br>WELL DONE !</h1>
        <p class="userpoints">Your Points: <span class="points"></span></p>
        
        <!-- Share buttons -->
        <div class="share-buttons">
            <!-- Facebook Share Button -->
            <a href="https://www.facebook.com/sharer/sharer.php?u=https://ape-able-rarely.ngrok-free.app/index.html=Check out my quiz results on PhishGuardian!" target="_blank" class="share-button">
                <i class="fab fa-facebook"></i> Share on Facebook
            </a>
           <!-- Twitter Share Button -->
            <a href="https://twitter.com/intent/tweet?url=http://localhost/index.html=Check out my quiz results on PhishGuardian!" target="_blank" class="share-button">
                <i class="fab fa-twitter"></i> Share via X
            </a>
            <!-- Email Share Button -->
            <a href="mailto:?subject=Check out my quiz results&amp;body=Hey there! I just completed the quiz and earned points on PhishGuardian. Signup and check out your results too! Here is the link to signup: http://localhost/index.html" target="_blank" class="share-button">
                <i class="fas fa-envelope"></i> Share via Email
            </a>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/d56261bbb9.js"></script>
<script src="userinfo.js"></script>
</body>
</html>

