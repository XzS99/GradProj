<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include('includes/dbconnection.php');
//start a session

//include the database connection file

//check if the session variable 'lssemsaid' is set, if not redirect to logout

if (strlen($_SESSION['lssemsaid']) == 0) {
    //get the value of session variable 'ofsmsaid' and store it in a variable
    header('location:logout.php');
}

if (isset($_POST['submit'])) {
    //get the value of the form fields 'pagetitle', 'pagedes', 'mobnum', 'email'
    $ofsmsaid = $_SESSION['ofsmsaid'];
    $pagetitle = $_POST['pagetitle'];
    $pagedes = $_POST['pagedes'];
    $mobnum = $_POST['mobnum'];
    $email = $_POST['email'];

    // update the 'contact us' page in the database
    $sql = "UPDATE tblpage SET PageTitle=?, PageDescription=?, Email=?, MobileNumber=? WHERE PageType='contactus'";
    $query = $dbh->prepare($sql);
    $query->execute([$pagetitle, $pagedes, $email, $mobnum]);
    echo '<script>alert("Contact us has been updated")</script>';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Local Services Search Engine Mgmt System | Contact Us</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(nicEditors.allTextAreas);
    </script>
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
                            <h1>Contact Us</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a>
                                <li class="breadcrumb-item active">Contact Us</li>
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
                                    <h3 class="card-title">Contact Us</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="#" method="post" enctype="multipart/form-data">
                                    <?php
                                    $sql = "SELECT * FROM tblpage WHERE PageType='contactus'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                    ?>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Page Title</label>
                                                    <input type="text" class="form-control" id="pagetitle" name="pagetitle" placeholder="Page Title" value="<?php echo htmlentities($row->PageTitle); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Page Description</label>
                                                    <textarea class="form-control" id="pagedes" name="pagedes" rows="3" required><?php echo htmlentities($row->PageDescription); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo htmlentities($row->Email); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Mobile Number</label>
                                                    <input type="text" class="form-control" id="mobnum" name="mobnum" placeholder="Mobile Number" value="<?php echo htmlentities($row->MobileNumber); ?>" required>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->

                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!--/.col (left) -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include_once('includes/footer.php'); ?>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
</body>

</html>