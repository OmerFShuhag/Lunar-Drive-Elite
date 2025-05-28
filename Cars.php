<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Get all available cars
$cars = getAllAvailableCars();
?>

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <div class="section-title text-center mb-5">
        <h2 class="fw-bold">Available Cars</h2>
        <p class="text-muted">Choose from our selection of premium vehicles</p>
    </div>

    <div class="row g-4">
        <?php foreach ($cars as $car): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card car-card h-100">
                <div class="car-image-container position-relative">
                    <img src="<?php echo htmlspecialchars($car['image']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($car['name']); ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="car-overlay">
                        <?php if ($car['is_available']): ?>
                            <a href="user/book.php?car_id=<?php echo $car['id']; ?>" 
                               class="btn btn-primary">Book Now</a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Currently Unavailable</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($car['name']); ?></h5>
                        <?php if (!$car['is_available']): ?>
                            <span class="badge bg-warning">Booked</span>
                        <?php endif; ?>
                    </div>
                    <p class="card-text text-muted mb-2">
                        <?php echo htmlspecialchars($car['model']); ?> | 
                        <?php echo htmlspecialchars($car['year']); ?>
                    </p>
                    <div class="car-features mb-3">
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-users"></i> <?php echo htmlspecialchars($car['seats']); ?> Seats
                        </span>
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['fuel_type']); ?>
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-cog"></i> <?php echo htmlspecialchars($car['transmission']); ?>
                        </span>
                    </div>
                    <p class="price-tag fw-bold mb-0">₹<?php echo htmlspecialchars($car['price_per_day']); ?> / day</p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.car-image-container {
    overflow: hidden;
}

.car-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(26, 60, 109, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.car-card:hover .car-overlay {
    opacity: 1;
}

.car-overlay .btn {
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.car-card:hover .car-overlay .btn {
    transform: translateY(0);
}
</style>

<?php include 'includes/footer.php'; ?> 