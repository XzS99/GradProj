<?php
error_reporting(0);
session_start();
include('includes/dbconnection.php');

// Initialize an array to store validation errors
$errors = array();

if (
    $_SERVER['REQUEST_METHOD'] == 'POST'
) {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $category = $_POST['category'];
    $MobileNumber = $_POST['MobileNumber'];
    $propic = $_FILES["propic"]["name"];
    $StartTime = $_POST['StartTime'];
    $EndTime = $_POST['EndTime'];

    if ($password != $password_confirm) {
        array_push($errors, "The passwords do not match.");
    }

    //Password > 6
    if (strlen($password) < 6) {
        array_push($errors, "Password must be at least 6 characters.");
    }


    // Validate the form data
    if (empty($name)) {
        array_push($errors, "Name is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email address");
        }
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    if (empty($address)) {
        array_push($errors, "Address is required");
    }
    if (empty($city)) {
        array_push($errors, "City is required");
    }
    if (empty($category)) {
        array_push($errors, "Category is required");
    }
    if (empty($MobileNumber)) {
        array_push($errors, "MobileNumber is required");
    } else if (!preg_match('/^\d{10}$/', $MobileNumber)) {
        array_push($errors, "Invalid mobile number");
    }
    if (empty($StartTime)) {
        array_push($errors, "StartTime is required");
    }
    if (empty($EndTime)) {
        array_push($errors, "EndTime is required");
    }

    //Check if the end time is greater than start time

    if (strtotime($EndTime) <= strtotime($StartTime)) {
        array_push($errors, "End time must be greater than start time.");
    }

    // If there are no validation errors
    if (count($errors) == 0) {
        // File handling
        $extension = substr($propic, strlen($propic) - 4, strlen($propic));
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");

        // Check if file extension is allowed
        if (!in_array($extension, $allowed_extensions)) {
            $propic = "Default.jpg";
            $extension = ".jpg";
        } else {
            $propic = md5($propic) . time() . '.' . $extension;
            move_uploaded_file($_FILES["propic"]["tmp_name"], "images/" . $propic);
        }

        // Check if email already exists in database 
        $emailCheck = $dbh->prepare("SELECT email FROM tblperson WHERE email = :email");
        $emailCheck->bindParam(':email', $email, PDO::PARAM_STR);
        $emailCheck->execute();

        // If email already exists, add error to errors array  
        if ($emailCheck->rowCount() > 0) {
            array_push($errors, "The email address is already in use.");
        }

        // Check if mobile number already exists
        $mobileCheck = $dbh->prepare("SELECT COUNT(*) FROM tblperson WHERE MobileNumber = :MobileNumber");
        $mobileCheck->bindParam(':MobileNumber', $MobileNumber, PDO::PARAM_STR);
        $mobileCheck->execute();
        if ($mobileCheck->fetchColumn() > 0) {
            array_push($errors, "The mobile number is already in use.");
        }

        // If there are no validation errors
        if (count($errors) == 0) {
            // Hash the password for security
            $password = md5($password);
            $working_days = implode(',', $_POST['working_days']);
            // Insert the data into the database
            $sql = "INSERT INTO tblperson (email, picture, password,category,name,MobileNumber,Address,city,StartTime,EndTime,working_days) VALUES (:email, :picture,:password,:category,:name,:MobileNumber,:Address,:city,:StartTime,:EndTime,:working_days)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':MobileNumber', $MobileNumber, PDO::PARAM_STR);
            $stmt->bindParam(':picture', $propic, PDO::PARAM_STR);
            $stmt->bindParam(':Address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':city', $city, PDO::PARAM_STR);
            $stmt->bindParam(':StartTime', $StartTime, PDO::PARAM_STR);
            $stmt->bindParam(':EndTime', $EndTime, PDO::PARAM_STR);
            $stmt->bindParam(':working_days', $working_days, PDO::PARAM_STR);
            $stmt->execute();
            $success = "Your account is now pending approval";
        }
    }
}
?>

<html>

<head>
    <title>Signup Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <style>
        select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 20px;
            font-size: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="time"],
        input[type="file"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }

        .success {
            color: navy;
        }
    </style>

    <form method="post" enctype="multipart/form-data">
        <h2>Signup</h2>
        <?php
        if (!empty($errors)) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '</div>';
        }
        ?>
        <?php if (isset($success)) : ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <label for="password_confirm">Retype Password</label>
        <input type="password" id="password_confirm" name="password_confirm">

        <label for="category">Category</label>
        <select type="text" name="category" id="category" value="" class="form-control" required="true">
            <option value="">Choose Category</option>
            <?php

            $sql2 = "SELECT * from   tblcategory ";
            $query2 = $dbh->prepare($sql2);
            $query2->execute();
            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

            foreach ($result2 as $row) {
            ?>
                <option value="<?php echo htmlentities($row->Category); ?>"><?php echo htmlentities($row->Category); ?></option>
            <?php } ?>


        </select>

        <div class="textbox">
            <i class="fas fa-user"></i>
            <label for="city">City</label>

            <select name="city">
                <option value="" class="options" selected disabled>Choose a City</option>
                <option value="Amman" class="options">Amman</option>
                <option value="Zarqa" class="options">Zarqa</option>
                <option value="Irbid" class="options">Irbid</option>
                <option value="Mafraq" class="options">Mafraq</option>
                <option value="Ajloun" class="options">Ajloun</option>
                <option value="Jerash" class="options">Jerash</option>
                <option value="Madaba" class="options">Madaba</option>
                <option value="Balqa" class="options">Balqa</option>
                <option value="Karak" class="options">Karak</option>
                <option value="Tafileh" class="options">Tafileh</option>
                <option value="Maan " class="options">Maan</option>
                <option value="Aqaba " class="options">Aqaba</option>
            </select>
        </div>


        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?php echo $address; ?>">


        <label for="MobileNumber">Mobile Number</label>
        <input type="text" id="MobileNumber" name="MobileNumber" value="<?php echo $MobileNumber; ?>">

        <label for="propic">Profile Picture</label>
        <input type="file" id="propic" name="propic">

        <label for="StartTime">Start Time</label>
        <input type="time" id="StartTime" name="StartTime" value="<?php echo $StartTime; ?>">

        <label for="EndTime">End Time</label>
        <input type="time" id="EndTime" name="EndTime" value="<?php echo $EndTime; ?>">

        <style>
            #working-days {
                height: 100px;
                width: 300px;
                font-size: 16px;
                padding: 10px;
                border: 1px solid #ccc;
            }

            h1 {
                color: #464646;
                width: 500px;
                margin: 0 auto;
                text-align: center;
            }

            .dowPicker {
                width: 300px;
                margin: 0 auto;
                text-align: center;
                padding: 40px 0 0 0;
            }

            .dowPicker .dowPickerOption {
                display: inline-block;
            }

            .dowPicker .dowPickerOption input[type=checkbox] {
                display: none;
            }

            .dowPicker .dowPickerOption label {
                font-size: 50px;
                color: #a7a7a7;
                cursor: pointer;
            }

            .dowPicker .dowPickerOption input[type=checkbox]:checked~label {
                color: #464646;
                font-weight: 500;
            }
        </style>
        <h1>Select Working Days</h1>
        <div class="dowPicker">
            <div class="dowPickerOption">
                <input type="checkbox" name="working_days[]" id="Sunday" value="Sunday">
                <label for="Sunday">S</label>
            </div>
            <div class="dowPickerOption">
                <input type="checkbox" name="working_days[]" id="Monday" value="Monday">
                <label for="Monday">M</label>
            </div>
            <div class="dowPickerOption">
                <input type="checkbox" name="working_days[]" id="Tuesday" value="Tuesday">
                <label for="Tuesday">T</label>
            </div>
            <div class="dowPickerOption">
                <input type="checkbox" name="working_days[]" id="Wednesday" value="Wednesday">
                <label for="Wednesday">W</label>
            </div>
            <div class="dowPickerOption">
                <input type="checkbox" name="working_days[]" id="Thursday" value="Thursday">
                <label for="Thursday">T</label>
            </div>
            <div class="dowPickerOption">
                <input type="checkbox" name="working_days[]" id="Friday" value="Friday">
                <label for="Friday">F</label>
            </div>
            <div class="dowPickerOption">
                <input type="checkbox" name="working_days[]" id="Saturday" value="Saturday">
                <label for="Saturday">S</label>
            </div>
        </div>


        <input type="submit" value="Signup">
        <a href="login.php">signin</a>

    </form>
</body>

</html>