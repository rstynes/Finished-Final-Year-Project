<!DOCTYPE html>
<html>
<head>
    <title>Admin Login Page</title>
    <link rel="stylesheet" type="text/css" href="adminLogin.css">
</head>
<body>
	<header>
        <h1>PhishGuardian</h1>
    </header>
    <div class="container">
        <form method="post" action="adminLogin.php">
            <h2>Admin Login</h2>
            <label for="AdminName">Username:</label>
            <input type="text" id="AdminName" name="AdminName"><br><br>
            <label for="AdminPass">Password:</label>
            <input type="Password" id="AdminPass" name="AdminPass"><br><br>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="submit" value="Login">
			<a href="index.html" class="back-button">Back</a>
        </form>
    </div>
</body>
</html>