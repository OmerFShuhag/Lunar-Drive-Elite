<?php
require_once '../includes/functions.php';


if (!isAdmin()) {
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];
    
    if (deleteCar($car_id)) {
        header('Location: admin-dashboard.php?success=Car deleted successfully');
        exit;
    } else {
        header('Location: admin-dashboard.php?error=Failed to delete car');
        exit;
    }
}

header('Location: admin-dashboard.php');
exit; 