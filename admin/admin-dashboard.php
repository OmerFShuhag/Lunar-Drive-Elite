<?php
include '../includes/functions.php';
include '../includes/header.php';
include '../includes/navbar.php';



if (isset($_POST['delete_car'])) {
    $car_id = $_POST['car_id'];
    deleteCar($car_id);
}


if (isset($_POST['toggle_featured'])) {
    $car_id = $_POST['car_id'];
    $featured_status = $_POST['featured_status'];
    toggleFeaturedCar($car_id, $featured_status);
}


if (isset($_POST['add_car'])) {
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
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            exit;
        }
    } else {
        echo "<script>alert('Please select a car image.');</script>";
        exit;
    }
    
    insertCar($car_data);
}


$cars = getAllCarsWithStatus();
?>


<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Admin Dashboard</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
            Add New Car
        </button>
    </div>

    <!-- Featured Cars Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Featured Cars</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Image</th>
                            <th>Car Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($cars as $car): 
                            if (!$car['is_featured']) continue;
                        ?>
                        <tr>
                            <td>
                                <img src="../<?php echo htmlspecialchars($car['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($car['name']); ?>" 
                                     class="img-thumbnail" 
                                     style="width: 100px; height: 70px; object-fit: cover;">
                            </td>
                            <td>
                                <h6 class="mb-1"><?php echo htmlspecialchars($car['name']); ?></h6>
                                <small class="text-muted">
                                    Model: <?php echo htmlspecialchars($car['model']); ?> | 
                                    Year: <?php echo htmlspecialchars($car['year']); ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($car['is_booked']): ?>
                                    <span class="badge bg-warning">Booked</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Available</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="" method="POST" class="d-inline">
                                    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                                    <input type="hidden" name="featured_status" value="0">
                                    <button type="submit" name="toggle_featured" class="btn btn-sm btn-warning">
                                        Remove from Featured
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- All Cars Table -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">All Cars</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Image</th>
                            <th>Car Details</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                        <tr>
                            <td>
                                <img src="../<?php echo htmlspecialchars($car['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($car['name']); ?>" 
                                     class="img-thumbnail" 
                                     style="width: 100px; height: 70px; object-fit: cover;">
                            </td>
                            <td>
                                <h6 class="mb-1"><?php echo htmlspecialchars($car['name']); ?></h6>
                                <small class="text-muted">
                                    Model: <?php echo htmlspecialchars($car['model']); ?> | 
                                    Year: <?php echo htmlspecialchars($car['year']); ?>
                                </small>
                                <div class="mt-1">
                                    <span class="badge bg-info"><?php echo htmlspecialchars($car['transmission']); ?></span>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($car['fuel_type']); ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($car['is_booked']): ?>
                                    <span class="badge bg-warning">Booked</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Available</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($car['is_booked']): ?>
                                    <?php if ($car['payment_status'] == 'pending'): ?>
                                        <span class="badge bg-danger">Payment Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="editCar(<?php echo $car['id']; ?>)">
                                        Edit
                                    </button>
                                    <?php if (!$car['is_featured'] && !$car['is_booked']): ?>
                                    <form action="" method="POST" class="d-inline">
                                        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                                        <input type="hidden" name="featured_status" value="1">
                                        <button type="submit" name="toggle_featured" class="btn btn-sm btn-success">
                                            Add to Featured
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <form action="" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this car?');">
                                        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                                        <button type="submit" name="delete_car" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Car</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Car Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price per Day</label>
                            <input type="number" name="price_per_day" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Seats</label>
                            <input type="number" name="seats" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Transmission</label>
                            <select name="transmission" class="form-select" required>
                                <option value="Automatic">Automatic</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fuel Type</label>
                            <select name="fuel_type" class="form-select" required>
                                <option value="Petrol">Petrol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Electric">Electric</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Car Image</label>
                            <input type="file" name="car_image" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_car" class="btn btn-primary">Add Car</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Car Modal -->
<div class="modal fade" id="editCarModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>

<script>
function editCar(carId) {
    fetch(`get_car_details.php?id=${carId}`)
        .then(response => response.text())
        .then(html => {
            document.querySelector('#editCarModal .modal-content').innerHTML = html;
            new bootstrap.Modal(document.querySelector('#editCarModal')).show();
        });
}
</script>

<?php include '../includes/footer.php'; ?> 