<?php
        // Generate CSRF token
        function generateCSRFToken() {
            $token = bin2hex(random_bytes(12)); // Generate a random string (at least 12 bytes) as token
            $timestamp = time(); // Get current timestamp
            $combined = $token . $timestamp; // Combine token and timestamp
            $hashedToken = hash('sha256', $combined); // Hash the combined string
            return $hashedToken;
        }
        // Check if CSRF token exists in session, otherwise generate a new one
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = generateCSRFToken();
        }  
?>


<!doctype html>
<html>
<head>
<link rel="stylesheet" href="register.css" type="text/css" />	<!--Link to stylesheet--->
<title>Registration</title>
</head>

<body>
	<div class ="form">

<form action="encrypt.php" method="POST" enctype="multipart/form-data">
	<h2>Registration Form</h2>
	<fieldset>											<!--Fieldset to split information into different sections on form--->
	<legend>Personal Details</legend>
	<div class = "inputbox">							
	<label for="Username">Username:</label>
	<input type="text" name="Username" id="Username" placeholder = "Username" required title="e.g. rstynes" autofocus />	<!--Firsname and Surname made to be required with auto focus on Firstname--->
	</div>
	<div class = "inputbox">							
	<label for="Password">Password:</label>
	<input type="password" name="Password" id="Password" placeholder = "Password" required title="Must have Capital" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required />	<!--Firsname and Surname made to be required with auto focus on Firstname--->
	</div>
	<div class = "inputbox">							
	<label for="confrimPassword">Confirm Password:</label>
	<input type="password" name="confrimPassword" id="confrimPassword" placeholder = "confrimPassword" required oninput= "checkPassword(this)"title="Must Match" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required />	<!--Firsname and Surname made to be required with auto focus on Firstname--->
	</div>
	<div class = "inputbox">
	<label for="email">Email:</label>
	<input type="email" name="email" id="email" placeholder = "example@hotmail.com"required title="Current Email Address" />
	</div>
	<div class = "inputbox">
	<label for="dob">DOB:</label>
	<input type="date" name="dob" id="dob" placeholder = "dd/mm/yyyy"required title="Use dd/mm/yyyy" />
	</div>
	<div class = "inputbox">
	<label for="phone">Phone No:</label>
	<input type="tel" id="phone" name="phone" placeholder = "Mobile Number" title="e.g. 087-1234567" pattern="[0-9]{2,3}-[0-9]{5,7}">
	</div>
    <div class = "inputbox">
    <label for="Photo">Profile Photo:</label>
	<input type="file" id="Photo" name="Photo">
    </div>
	</fieldset>
	
<div class="mybutton">
<input type="submit" value="Submit" name="submit" id="submit" /><br><br>				<!--Creates two buttons on form with the assigned labels for clearing and submitting the form--->
<input type="reset" value="Clear" name="reset" id="submit" />
</div>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
</form>
</div>
</body>
</html>