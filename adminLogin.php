<?php
include_once 'dbh.inc.php';

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

// Function to clean input characters
function cleanChars($val)
{
    $val = str_replace('&', '&amp;', $val);
    $val = str_replace('"', '&quot;', $val);
    $val = str_replace("'", '&#39;', $val);
    $val = str_replace('<', '&lt;', $val);
    $val = str_replace('>', '&gt;', $val);
    $val = str_replace('[', '&#91;', $val);
    $val = str_replace(']', '&#93;', $val);
    $val = str_replace('{', '&#123;', $val);
    $val = str_replace('}', '&#125;', $val);
    $val = str_replace('|', '&#124;', $val);
    $val = str_replace('/', '&#47;', $val);
    $val = str_replace('\\', '&#92;', $val);
    $val = str_replace('$', '&#36;', $val);
    $val = str_replace('%', '&#37;', $val);
    return $val;
}

// Define username and password
$adminName = cleanChars($_POST['AdminName']); 
$adminPass = cleanChars($_POST['AdminPass']); 

if (isset($_POST['AdminName'], $_POST['AdminPass'])) 
{
    $stmt = $conn->prepare("SELECT * FROM Admin_Login WHERE AdminName = ?");
    $stmt->bind_param("s", $adminName);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['AdminPass'];
        if (password_verify($adminPass, $hashedPassword)) { 
            // Start session
            session_start();
            session_regenerate_id(true);
            
            $_SESSION['AdminName'] = $row['AdminName'];
            $_SESSION['Admin_ID'] = $row['Admin_ID'];
            // Set session timeout and expiration
            $_SESSION['CREATED'] = time(); // Creation time
            $_SESSION['LAST_ACTIVITY'] = $_SESSION['CREATED']; // Last activity time
            $session_timeout = 1800; // 30 minutes
            $_SESSION['EXPIRES'] = $_SESSION['CREATED'] + $session_timeout; // Expiration time
   
            // Set secure and httponly flags for session cookies
            ini_set('session.cookie_secure', 1); // Only transmit cookies over secure HTTPS connection
            ini_set('session.cookie_httponly', 1); // Make cookies accessible only via HTTP(S), not JavaScript

            header('Location: adminLandingPage.php');
            exit();
        } else {
            header("Location: error.php?error=wrongpassword");
            exit();
        }
    } else {
        header("Location: error.php?error=nouserfound");
        exit();
    }
}
?>
