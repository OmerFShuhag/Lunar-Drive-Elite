<?php
require_once '../includes/functions.php';


if (!isAdmin()) {
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id']) && isset($_POST['featured_status'])) {
    $car_id = $_POST['car_id'];
    $featured_status = $_POST['featured_status'];
    
    if (toggleFeaturedCar($car_id, $featured_status)) {
        header('Location: admin-dashboard.php?success=Featured status updated successfully');
        exit;
    } else {
        header('Location: admin-dashboard.php?error=Failed to update featured status');
        exit;
    }
}

header('Location: admin-dashboard.php');
exit; 