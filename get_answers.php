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
    <!-- Meta tags for character set and viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link to CSS file -->
    <link rel="stylesheet" href="quiz-final.css">
    <!-- Title of the page -->
    <title>Quiz</title>
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
<!--QUIZ-QUESTION-->

<div class="wrapper">
    <div class="quiz">
        <!-- Quiz header -->
        <div class="quiz_header">
            <div class="quiz_user">
                <!-- Displaying the user's name -->
                <h4>WELCOME ! <span class="name"></span></h4>
            </div>
        </div>

        <!-- Quiz body where questions and options will be displayed -->
        <div class="quiz_body">
            <div id="questions">
                <!-- PHP code to fetch questions from the database -->
                <?php
                // Including database connection file
                include_once 'dbhQuiz.inc.php';

                // Checking database connection
                if ($conn3->connect_error) {
                    die("Connection failed: " . $conn3->connect_error);
                }

                // Fetching questions from the database
                $sql = "SELECT QuizID, QuestionText, Ans1, Ans2, Ans3, Ans4, CorrectAns FROM Quiz";
                $result = $conn3->query($sql);

                // Creating an array to store questions and options
                $questions = array();
                if ($result->num_rows > 0) {
                    // Fetching each row and adding it to the $questions array
                    while ($row = $result->fetch_assoc()) {
                        $question = array(
                            'id' => $row['QuizID'],
                            'question' => $row['QuestionText'],
                            'answer' => $row['CorrectAns'],
                            'options' => array(
                                $row['Ans1'],
                                $row['Ans2'],
                                $row['Ans3'],
                                $row['Ans4']
                            )
                        );
                        array_push($questions, $question);
                    }
                    // Shuffling the questions array
                    shuffle($questions);
                } else {
                    echo "No questions found in the database.";
                }

                // Closing database connection
                $conn3->close();
                ?>
            </div>
            <!-- Button to navigate to the next question -->
            <button class="btn-next" onclick="next()">Next</button>
        </div>
    </div>
</div>

<script>
    // JavaScript object containing questions array obtained from PHP
    let questions = <?php echo json_encode($questions); ?>;
    let question_count = 0;
    let points = 0;

    // Function to display the current question and options
    function show(count) {
        let question = document.getElementById("questions");
        let[first, second, third, fourth] = questions[count].options;

        // Displaying question and options
        question.innerHTML = `<h2>Q${count + 1}. ${questions[count].question}</h2>
        <ul class="option_group">
        <li class="option">${first}</li>
        <li class="option">${second}</li>
        <li class="option">${third}</li>
        <li class="option">${fourth}</li>
        </ul>`;
        // Enables selections
        toggleActive();  
    }

    // Function to handle answer selection
    function toggleActive(){
        let option = document.querySelectorAll("li.option");
        for(let i=0; i < option.length; i++){
            option[i].onclick = function(){
                for(let i=0; i < option.length; i++){
                    if(option[i].classList.contains("active")){
                        option[i].classList.remove("active");
                    }
                }
                
                option[i].classList.add("active");
            }
        }
    }

    // Function for button click to move to the next question
    function next() {
        // Get the user's selected answer
        let userAnswerElement = document.querySelector("li.option.active");
        let userAnswer = userAnswerElement.innerHTML;

        // Checks if user's answer is correct
        let correctAnswer = questions[question_count].answer;
        let isCorrect = (userAnswer === correctAnswer);

        // Update the styling of the selected answer
        userAnswerElement.style.backgroundColor = isCorrect ? 'green' : 'red';
        userAnswerElement.style.color = 'white';

        // Disable further selections
        let options = document.querySelectorAll("li.option");
        options.forEach(option => {
            option.onclick = null; // Remove onclick event to disable further selection
        });

        // Increment points if the answer is correct
        if (isCorrect) {
            points += 10;
            sessionStorage.setItem("points", points);
        }

        // Move to next question after a delay to display the user's answer
        setTimeout(function () {
            // Move to the next question
            question_count++;

            // Reset background color and remove 'active' class for options
            options.forEach(option => {
                option.style.backgroundColor = ''; // Reset background color
                option.classList.remove('active');
            });

            // Checks if there are more questions to show
            if (question_count < 5) {
                show(question_count);
            } else {
                // If all questions are answered, proceed to submit the quiz
                submitQuiz();
            }
        }, 1250); // Adjust delay time as needed
    }

    // Function to submit the quiz
    function submitQuiz() {
        // Send points to the server to store in the database using Fetch API
        fetch('storeScore.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'points=' + points
        })
        .then(response => {
            if(response.ok) {
                // Redirect to the finish page after storing the score
                location.href = "after-quiz.php";
            } else {
                throw new Error('Failed to store score');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    // Loads question/quiz when the window loads up
    window.onload = function(){
        show(question_count);
        // Reset points when starting a new quiz session
        points = 0;
        sessionStorage.setItem("points", points);
    };
</script>
</body>
</html>
