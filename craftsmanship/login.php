<?php
session_start(); //start session
include('includes/dbconnection.php'); //include the database connection file

//Check if the session variable is set
if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    // Get the form data
    $email = $_POST['email'];
    $password = md5($_POST['password']); // md5 the password for security

    // Check if the email and password match any records in the 'tblperson' table
    $query = "SELECT * FROM tblperson WHERE email = :email AND password = :password";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // If a match is found, set session variable and redirect to 'edit_details.php'
        $_SESSION['email'] = $email;
        $_SESSION['message'] = 'You have successfully logged in.';
        header('Location: edit_details.php'); //redirect to the edit_details.php page
    } else {
        // If no match is found, redirect back to the login page with an error message
        $error = "Invalid email or password.";
    }
}
?>

<html>

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
</head>

<body>
    <div class="login-box">
        <form action="login.php" method="post">
            <h1>Login</h1>
            <div class="textbox">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Email" name="email" required>
            </div>
            <div class="textbox">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <?php if (!empty($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
            <input type="submit" class="btn" name="login" value="Sign in">

            <p><a href="forgot_password.php" class="btn">Forgot Password</a></p>
            <p><a href="signup.php" class="btn">Sign Up</a></p>
            <p><a href="../index.php">Back to home page</a></p>
        </form>

    </div>
</body>


</html>