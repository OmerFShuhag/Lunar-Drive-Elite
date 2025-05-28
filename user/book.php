<?php
require_once '../includes/functions.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['car_id'])) {
    header('Location: ../cars.php');
    exit();
}

// Clean up expired bookings
cleanupExpiredBookings();

$car = getCarById($_GET['car_id']);
if (!$car) {
    header('Location: ../cars.php');
    exit();
}

// Check if car is booked
$is_booked = isCarBooked($car['id']);
if ($is_booked && $is_booked['payment_status'] == 'paid') {
    header('Location: ../cars.php?error=Car is already booked');
    exit();
}

$error = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    // Validate dates
    $validation_error = validateBookingDates($start_date, $end_date);
    if ($validation_error) {
        $error = $validation_error;
    }
    // Check for overlapping bookings
    else if (hasOverlappingBookings($car['id'], $start_date, $end_date)) {
        $error = "Selected dates overlap with an existing booking";
    }
    else {
        $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
        $total_amount = $days * $car['price_per_day'];
        
        // Create booking
        $booking_data = [
            'user_id' => $_SESSION['user_id'],
            'car_id' => $car['id'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_amount' => $total_amount,
            'payment_status' => 'pending'
        ];
        
        if (createBooking($booking_data)) {
            $booking_id = mysqli_insert_id($conn);
            if (isset($_POST['pay_later'])) {
                header('Location: dashboard.php?success=Booking created successfully. Please complete payment within 24 hours.');
            } else {
                header('Location: payment.php?booking_id=' . $booking_id);
            }
            exit();
        } else {
            $error = "Failed to create booking. Please try again.";
        }
    }
}

// Calculate minimum and maximum dates
$min_date = date('Y-m-d');
$max_date = date('Y-m-d', strtotime('+30 days'));

// Get car availability calendar
$bookings = getCarBookings($car['id']);
$unavailable_dates = [];
foreach ($bookings as $booking) {
    if ($booking['payment_status'] == 'paid') {
        $period = new DatePeriod(
            new DateTime($booking['start_date']),
            new DateInterval('P1D'),
            new DateTime($booking['end_date'])
        );
        foreach ($period as $date) {
            $unavailable_dates[] = $date->format('Y-m-d');
        }
    }
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <div class="position-sticky" style="top: 2rem;">
                <img src="../<?php echo htmlspecialchars($car['image']); ?>" 
                     alt="<?php echo htmlspecialchars($car['name']); ?>" 
                     class="img-fluid rounded shadow-sm mb-4">
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($car['name']); ?></h4>
                        <p class="text-muted">
                            <?php echo htmlspecialchars($car['model']); ?> | 
                            <?php echo htmlspecialchars($car['year']); ?>
                        </p>
                        
                        <div class="car-features mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-users"></i> <?php echo htmlspecialchars($car['seats']); ?> Seats
                                </span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['fuel_type']); ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-cog"></i> <?php echo htmlspecialchars($car['transmission']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <h5 class="text-primary mb-3">¥<?php echo number_format($car['price_per_day'], 0); ?> / day</h5>
                        
                        <div class="description">
                            <h6 class="fw-bold">Description</h6>
                            <p class="text-muted"><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Book Car</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" id="bookingForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" 
                                       name="start_date" 
                                       class="form-control" 
                                       min="<?php echo $min_date; ?>" 
                                       max="<?php echo $max_date; ?>" 
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" 
                                       name="end_date" 
                                       class="form-control" 
                                       min="<?php echo $min_date; ?>" 
                                       max="<?php echo $max_date; ?>" 
                                       required>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-info" id="priceCalculation" style="display: none;">
                                    Total Days: <span id="totalDays">0</span><br>
                                    Total Amount: ¥<span id="totalAmount">0</span>
                                </div>
                            </div>
                        </div>

                        <?php if (!isAdmin()): ?>
                        <div class="mt-4">
                            <button type="submit" name="pay_now" class="btn btn-primary">Proceed to Payment</button>
                            <button type="submit" name="pay_later" class="btn btn-outline-primary">Pay Later</button>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <a href="../cars.php" class="btn btn-link">Back to Cars</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');
    const pricePerDay = <?php echo $car['price_per_day']; ?>;
    const unavailableDates = <?php echo json_encode($unavailable_dates); ?>;
    
    function isDateUnavailable(date) {
        return unavailableDates.includes(date);
    }
    
    function updatePriceCalculation() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            
            if (days > 0) {
                const total = days * pricePerDay;
                document.getElementById('totalDays').textContent = days;
                document.getElementById('totalAmount').textContent = total.toLocaleString('ja-JP');
                document.getElementById('priceCalculation').style.display = 'block';
            } else {
                document.getElementById('priceCalculation').style.display = 'none';
            }
        }
    }
    
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (isDateUnavailable(this.value)) {
            this.value = '';
            alert('Selected date is not available');
        }
        updatePriceCalculation();
    });
    
    endDate.addEventListener('change', function() {
        if (isDateUnavailable(this.value)) {
            this.value = '';
            alert('Selected date is not available');
        }
        updatePriceCalculation();
    });
    
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        
        if (end <= start) {
            e.preventDefault();
            alert('End date must be after start date');
            return;
        }
        
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        if (days > 30) {
            e.preventDefault();
            alert('Maximum booking duration is 30 days');
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?> 