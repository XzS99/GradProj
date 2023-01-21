<?php
session_start();

include('includes/dbconnection.php');
//start a session

//include the database connection file

//check if the session variable 'lssemsaid' is set, if not redirect to logout page
if (strlen($_SESSION['lssemsaid'] == 0)) {
    header('location:logout.php');
} else {
    //check if the form is submitted
    if (isset($_POST['submit'])) {

        //get the value of session variable 'lssemsaid' and store it in a variable
        $ofsmsaid = $_SESSION['lssemsaid'];

        //get the value of the form field 'pagetitle' and store it in a variable
        $pagetitle = $_POST['pagetitle'];

        //get the value of the form field 'pagedes' and store it in a variable
        $pagedes = $_POST['pagedes'];

        // update the 'about us' page in the database 
        $sql = "UPDATE tblpage SET PageTitle=?, PageDescription=? WHERE PageType='aboutus'";

        $query = $dbh->prepare($sql);

        $query->execute([$pagetitle, $pagedes]);

        echo '<script>alert("About us has been updated")</script>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Local Services Search Engine Mgmt System | About Us</title>

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
    </script>
</head>
<script>
    bkLib.onDomLoaded(nicEditors.allTextAreas);
</script>

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
                            <h1>About Us</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">About Us</li>
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
                                    <h3 class="card-title">About Us</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="#" method="post" enctype="multipart/form-data">
                                    <?php
                                    //select the about us page from the tblpage
                                    $sql = "SELECT * from  tblpage where PageType='aboutus'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) { ?>

                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Page Title</label>
                                                    <input type="text" class="form-control" id="pagetitle" name="pagetitle" value="<?php echo $row->PageTitle; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Page Description</label>
                                                    <textarea class="form-control" id="pagedes" name="pagedes" rows="3" required><?php echo $row->PageDescription; ?></textarea>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->

                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary" name="submit">Update</button>
                                            </div>
                                    <?php }
                                    } ?>
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
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('pagedes');
    </script>
</body>

</html>