<?php
// Start a new session
session_start();

// Unset all session variables
session_unset();

// Reset session data
session_reset();

// Destroy the session
session_destroy();

// Redirect the user to the login page
header('location:login.php');
?>


