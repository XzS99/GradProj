<?php
session_start();
include('includes/dbconnection.php');
?>
<div class="header">
    <div class="top-toolbar"><!--header toolbar-->
        <div class="container">
            <div class="row">

                <div class="col-md-6 col-sm-12 col-xs-12 pull-right">
                    <div class="top-contact-info">
                        <ul>
                            <?php
                            $sql = "SELECT * from tblpage where PageType='contactus'";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                            $cnt = 1;
                            if ($query->rowCount() > 0) {
                                foreach ($results as $row) {               ?>
                            <?php $cnt = $cnt + 1;
                                }
                            } ?>
                            <li><a class="toolbar-new-listing" href="admin/login.php"><i class="fa fa-plus-circle"></i> Admin</a></li>
                            <li><a class="toolbar-new-listing" href="craftsmanship/login.php"><i class="fa fa-plus-circle"></i> Craftsman</a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--header toolbar end-->
    <div class="nav-wrapper"><!--main navigation-->
        <div class="container">
            <!--Main Menu HTML Code-->
            <nav class="wsmenu slideLeft clearfix" style="margin-left: -6%; margin-right: -6%">
                <div class="logo pull-left">
                <img src="images\Logoindex.png" id="logo" width="65" height="65"> 
                </div>
                <div class="logo pull-left"><a href="index.php" title="Responsive Slide Menus">
                        <h3 style="color:#08c2f3">KHADMAT.COM</h3>
                    </a></div>
                <ul class="mobile-sub wsmenu-list pull-right">
                    <li><a href="index.php" class="">Home</a>

                    </li>

                    <li><a href="category.php">categories <span class="arrow"></span></a></li>

                    <li><a href="about.php">About Us <span class="arrow"></span></a></li>

                    <li><a href="contact.php">Contact Us <span class="arrow"></span></a></li>
                </ul>
            </nav>
        </div>
    </div><!--main navigation end-->
</div>
<style>
    @media only screen and (max-width: 892px) {
/* styles for hiding the element */
#logo {
display: none;
}
nav {margin-left: 0%; margin-right: 0%;}
}
</style>