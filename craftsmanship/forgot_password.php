<?php
error_reporting(0);
session_start();
include('includes/dbconnection.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // database connection and query to check if email exists
    // ...

    if ($emailExists) {
        // generate a password reset token
        $token = bin2hex(random_bytes(50));

        // hash the token
        $hashed_token = password_hash($token, PASSWORD_DEFAULT);

        // set expiration time for token
        $expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // store the token and expiration time in the database
        // ...

        // send the password reset link
        $to = $email;
        $subject = "Password Reset";
        $headers = "From: no-reply@example.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message = '<p>A password reset has been requested for your account. If you did not make this request, please ignore this email.</p>';
        $message .= '<p>To reset your password, please click on the link below:</p>';
        $message .= '<a href="http://example.com/reset_password.php?token=' . $token . '">Reset Password</a>';
        mail($to, $subject, $message, $headers);

        // show a message to the user
        echo "A password reset link has been sent to your email.";
    } else {
        echo '<div class="error">';
        echo "The email you entered does not exist in our system.";
        echo '</div>';

    }
}
?>


<html>

<style>
    .error {
        color: red;
        font-size: 14px;
        padding: 10px;
        text-align: center;
        background-color: #f2dede;
        border: 1px solid #a94442;
        border-radius: 5px;
    }
</style>


<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        form {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        input,
        select {
            width: 100%;
            padding: 12px 20px;
            margin-bottom: 20px;
            font-size: 16px;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
    </style>
</head>


<body>
<div class="forgot-password-box">
    <form action="forgot_password.php" method="post">
        <h1>Forgot Password</h1>
        <div class="textbox">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email" name="email" required>
        </div>
        <input type="submit" class="btn" value="Send Reset Link">
    </form>
</div>
</body>

</html>