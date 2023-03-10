<?php
session_start();
include 'includes/dbconnection.php';

//start a session

//include the database connection file

// Check if user is logged in by checking if the session variable 'lssemsaid' is set, if not
if (empty($_SESSION['lssemsaid'])) {
    header('location:logout.php');
    exit;
}

// Check if form was submitted
if (isset($_POST['submit'])) {
    $admin_id = $_SESSION['lssemsaid'];

    // Extract form data and sanitize inputs
    $admin_name = filter_input(INPUT_POST, 'adminname', FILTER_SANITIZE_STRING);
    $mobile_number = filter_input(INPUT_POST, 'mobilenumber', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    //check if all inputs are valid
    if ($admin_name && $mobile_number && $email) {
        // Update the tbladmin table with the new values
        $sql = "UPDATE tbladmin SET AdminName=:adminname, MobileNumber=:mobilenumber, Email=:email WHERE ID=:admin_id";

        // Prepare and execute the query
        $query = $dbh->prepare($sql);
        $query->bindParam(':adminname', $admin_name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobilenumber', $mobile_number, PDO::PARAM_STR);
        $query->bindParam(':admin_id', $admin_id, PDO::PARAM_STR);

        // Execute the query and check for success/failure of execution
        if ($query->execute()) {
            // Display an alert message on successful update
            echo '<script>alert("Your profile has been updated")</script>';
        } else {
            // Display an alert message on unsuccessful update
            echo '<script>alert("Unable to update profile")</script>';
        }
    } else {
        echo '<script>alert("Invalid data provided")</script>';
    }
}

// HTML Markup
?>

<!DOCTYPE html>
<html>

<head>
    <title>Local Services Search Engine Mgmt System | Admin Profile</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Admin Profile</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Admin Profile</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Admin Profile</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form name="profile" method="post" action="">

                                    <div class="card-body card-block">
                                        <?php

                                        $sql = "SELECT * from  tbladmin WHERE ID = :adminid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindValue(':adminid', $_SESSION['lssemsaid'], PDO::PARAM_INT);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) { ?>

                                                <div class="form-group">
                                                    <label for="company" class="form-control-label">Admin Name</label>
                                                    <input type="text" name="adminname" value="<?php echo $row->AdminName; ?>" class="form-control" required='true'>
                                                </div>
                                                <div class="form-group">
                                                    <label for="vat" class="form-control-label">User Name</label>
                                                    <input type="text" name="username" value="<?php echo $row->UserName; ?>" class="form-control" readonly="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="street" class="form-control-label">Contact Number</label>
                                                    <input type="text" name="mobilenumber" value="<?php echo $row->MobileNumber; ?>" class="form-control" maxlength='10' required='true'>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="city" class="form-control-label">Email</label>
                                                            <input type="email" name="email" value="<?php echo $row->Email; ?>" class="form-control" required='true'>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="postal-code" class="form-control-label">Admin Registration Date</label>
                                                            <input type="text" name="" value="<?php echo $row->AdminRegdate; ?>" readonly="" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php $cnt = $cnt + 1;
                                            }
                                        } ?>
                                        <div class="card-footer">
                                            <p style="text-align: center;">
                                                <button type="submit" class="btn btn-primary btn-sm" name="submit" id="submit">
                                                    <i class="fa fa-dot-circle-o"></i> Update
                                                </button>
                                            </p>

                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!--/.col (left) -->
                        <!-- right column -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include_once('includes/footer.php'); ?>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>