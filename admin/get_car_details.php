<?php
require_once '../includes/functions.php';


if (!isAdmin()) {
    exit('Unauthorized');
}

if (!isset($_GET['id'])) {
    exit('Car ID not provided');
}

$car = getCarById($_GET['id']);
if (!$car) {
    exit('Car not found');
}
?>

<div class="modal-header">
    <h5 class="modal-title">Edit Car</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<form action="update_car.php" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row g-3">
            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
            <div class="col-md-6">
                <label class="form-label">Car Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($car['name']); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Model</label>
                <input type="text" name="model" class="form-control" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Year</label>
                <input type="number" name="year" class="form-control" value="<?php echo htmlspecialchars($car['year']); ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Price per Day</label>
                <input type="number" name="price_per_day" class="form-control" value="<?php echo htmlspecialchars($car['price_per_day']); ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Seats</label>
                <input type="number" name="seats" class="form-control" value="<?php echo htmlspecialchars($car['seats']); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Transmission</label>
                <select name="transmission" class="form-select" required>
                    <option value="Automatic" <?php echo $car['transmission'] == 'Automatic' ? 'selected' : ''; ?>>Automatic</option>
                    <option value="Manual" <?php echo $car['transmission'] == 'Manual' ? 'selected' : ''; ?>>Manual</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fuel Type</label>
                <select name="fuel_type" class="form-select" required>
                    <option value="Petrol" <?php echo $car['fuel_type'] == 'Petrol' ? 'selected' : ''; ?>>Petrol</option>
                    <option value="Diesel" <?php echo $car['fuel_type'] == 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                    <option value="Electric" <?php echo $car['fuel_type'] == 'Electric' ? 'selected' : ''; ?>>Electric</option>
                    <option value="Hybrid" <?php echo $car['fuel_type'] == 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($car['description']); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Current Image</label>
                <img src="../<?php echo htmlspecialchars($car['image']); ?>" alt="Current car image" class="img-thumbnail mb-2" style="height: 100px;">
                <input type="file" name="car_image" class="form-control" accept="image/*">
                <small class="text-muted">Leave empty to keep current image</small>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="update_car" class="btn btn-primary">Update Car</button>
    </div>
</form> 