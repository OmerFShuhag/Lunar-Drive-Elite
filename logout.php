<!-- logout -->
<?php
session_start();
if(isset($_SESSION["email"])){
    session_unset();
    session_destroy();
    echo "<script>alert('You have been logged out successfully.');</script>";
    echo "<script>location.href='index.php';</script>";
} else {
    echo "<script>alert('You are not logged in.');</script>";
    echo "<script>location.href='login.php';</script>";
}
