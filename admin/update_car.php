<?php
require_once '../includes/functions.php';


if (!isAdmin()) {
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];
    $car_data = [
        'name' => $_POST['name'],
        'model' => $_POST['model'],
        'year' => $_POST['year'],
        'price_per_day' => $_POST['price_per_day'],
        'seats' => $_POST['seats'],
        'transmission' => $_POST['transmission'],
        'fuel_type' => $_POST['fuel_type'],
        'description' => $_POST['description']
    ];

    if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] === 0) {
        $uploadDir = "../images/cars/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["car_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . basename($_FILES["car_image"]["name"]);
        $target_file = $uploadDir . $new_filename;
        
        if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
            $car_data['image'] = 'images/cars/' . $new_filename;
        } else {
            header('Location: admin-dashboard.php?error=Error uploading image');
            exit;
        }
    }
    
    if (updateCar($car_id, $car_data)) {
        header('Location: admin-dashboard.php?success=Car updated successfully');
        exit;
    } else {
        header('Location: admin-dashboard.php?error=Failed to update car');
        exit;
    }
}

header('Location: admin-dashboard.php');
exit; 