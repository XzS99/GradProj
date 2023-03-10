<?php
session_start();
include('includes/dbconnection.php');

//Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

if (isset($_SESSION['message'])) : ?>
    <div class="success">
        <?php echo $_SESSION['message'];
        unset($_SESSION['message']); ?>
    </div>
<?php endif;


//Retrieve user data from the database
$email = $_SESSION['email'];
$query = $dbh->prepare("SELECT * FROM tblperson WHERE email = :email");
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();

if ($user = $query->fetch(PDO::FETCH_ASSOC)) {
    //Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $category = $_POST['category'];
        $MobileNumber = $_POST['MobileNumber'];
        $StartTime = $_POST['StartTime'];
        $EndTime = $_POST['EndTime'];
        $working_days = $_POST['working_days'];
        $propic = $_FILES["propic"]["name"];


        $errors = array();
        //validate form inputs
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
        if (empty($name)) {
            $errors[] = 'Name is required.';
        }
        if (empty($_POST['email'])) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }
        if (empty($address)) {
            $errors[] = 'Address is required.';
        }
        if (empty($city)) {
            $errors[] = 'City is required.';
        }
        if (empty($category)) {
            $errors[] = 'Category is required.';
        }
        if (empty($MobileNumber)) {
            $errors[] = 'Mobile Number is required.';
        }
        if (empty($StartTime)) {
            $errors[] = 'Start Time is required.';
        }
        if (empty($EndTime)) {
            $errors[] = 'End Time is required.';
        }
        if (empty($working_days)) {
            $errors[] = 'Working days is required.';
        }
        //If there are no errors, update the user's data
        if (empty($errors)) {

            $working_days = implode(',', $_POST['working_days']);
            $query = $dbh->prepare("UPDATE tblperson SET name = :name, email = :email, address = :address, city = :city, picture=:picture, category = :category, MobileNumber = :MobileNumber, StartTime = :StartTime, EndTime = :EndTime, working_days = :working_days WHERE email = :email");
            $query->bindParam(':working_days', $working_days, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':category', $category, PDO::PARAM_STR);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':MobileNumber', $MobileNumber, PDO::PARAM_STR);
            $query->bindParam(':address', $address, PDO::PARAM_STR);
            $query->bindParam(':city', $city, PDO::PARAM_STR);
            $query->bindParam(':picture', $propic, PDO::PARAM_STR);
            $query->bindParam(':StartTime', $StartTime, PDO::PARAM_STR);
            $query->bindParam(':EndTime', $EndTime, PDO::PARAM_STR);
            $query->execute();
            $message = 'Your details have been updated.';
            $_SESSION["message"] = $message;
        }
    }
}
?>

<html>

<head>
    <title>Edit Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
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

    input[type="submit"],
    .logout-button {
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
        color: green;
    }
</style>
<form method="post" enctype="multipart/form-data">
    <h2>Edit details</h2>
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
    <input type="text" id="name" name="name" value="<?php echo $user["Name"]; ?>">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo $user["Email"]; ?>">


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
    <input type="text" id="address" name="address" value="<?php echo $user["Address"]; ?>">


    <label for="MobileNumber">Mobile Number</label>
    <input type="text" id="MobileNumber" name="MobileNumber" value="<?php echo $user["MobileNumber"]; ?>">

    <label for="propic">Profile Picture</label>
    <input type="file" id="propic" name="propic">

    <label for="StartTime">Start Time</label>
    <input type="time" id="StartTime" name="StartTime" value="<?php echo date("H:i", strtotime($user["StartTime"])); ?>">

    <label for="EndTime">End Time</label>
    <input type="time" id="EndTime" name="EndTime" value="<?php echo date("H:i", strtotime($user["EndTime"])); ?>">

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

    <input type="submit" value="Submit changes">

    <button class="logout-button" onclick="location.href='logout.php'" value="Logout">
        Logout
    </button>

</form>
</body>

</html>