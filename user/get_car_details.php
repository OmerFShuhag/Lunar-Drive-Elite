<?php
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    exit('Car ID not provided');
}

$car = getCarById($_GET['id']);
if (!$car) {
    exit('Car not found');
}

// Check if car is currently booked
$is_booked = isCarBooked($car['id']);
?>

<div class="modal-header">
    <h5 class="modal-title"><?php echo htmlspecialchars($car['name']); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-12 mb-3">
            <img src="../<?php echo htmlspecialchars($car['image']); ?>" 
                 alt="<?php echo htmlspecialchars($car['name']); ?>" 
                 class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h6>Car Details</h6>
            <ul class="list-unstyled">
                <li><strong>Model:</strong> <?php echo htmlspecialchars($car['model']); ?></li>
                <li><strong>Year:</strong> <?php echo htmlspecialchars($car['year']); ?></li>
                <li><strong>Seats:</strong> <?php echo htmlspecialchars($car['seats']); ?></li>
                <li><strong>Transmission:</strong> <?php echo htmlspecialchars($car['transmission']); ?></li>
                <li><strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></li>
            </ul>
        </div>
        <div class="col-md-6">
            <h6>Pricing</h6>
            <p class="h4 text-primary mb-3">₹<?php echo number_format($car['price_per_day'], 2); ?> / day</p>
            <?php if ($is_booked): ?>
                <div class="alert alert-warning">
                    This car is currently booked.
                    <?php if ($is_booked['payment_status'] == 'pending'): ?>
                        <br>
                        <small>Note: Payment is pending for current booking.</small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <h6>Description</h6>
            <p><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <?php if (!$is_booked || ($is_booked && $is_booked['payment_status'] == 'pending')): ?>
        <?php if (isLoggedIn()): ?>
            <a href="book.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary">Book Now</a>
        <?php else: ?>
            <a href="../login.php" class="btn btn-primary">Login to Book</a>
        <?php endif; ?>
    <?php endif; ?>
</div> 