<?php
session_start();
session_unset();
session_reset();
session_destroy();
header('location:login.php');
