<?php
require_once '../includes/functions.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['booking_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Clean up expired bookings
cleanupExpiredBookings();

$booking = getBookingById($_GET['booking_id']);
if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    header('Location: dashboard.php');
    exit();
}

// Check if booking is still valid
if ($booking['status'] != 'active') {
    header('Location: dashboard.php?error=This booking is no longer active');
    exit();
}

if ($booking['payment_status'] == 'paid') {
    header('Location: dashboard.php?error=This booking is already paid');
    exit();
}

// Check if payment is expired
$booking_time = strtotime($booking['created_at']);
$expiry_time = $booking_time + (24 * 60 * 60); // 24 hours
if (time() > $expiry_time) {
    // Cancel the booking
    cancelBooking($booking['id']);
    header('Location: dashboard.php?error=Payment time expired. Booking has been cancelled.');
    exit();
}

$car = getCarById($booking['car_id']);

$error = null;
$payment_methods = [
    'bKash' => ['number' => '01XXXXXXXXX', 'image' => 'images/payments/bkash.png'],
    'Rocket' => ['number' => '01XXXXXXXXX', 'image' => 'images/payments/rocket.png'],
    'Nagad' => ['number' => '01XXXXXXXXX', 'image' => 'images/payments/nagad.png']
];

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $transaction_id = trim($_POST['transaction_id']);
    
    // Validate payment method
    if (!array_key_exists($payment_method, $payment_methods)) {
        $error = "Invalid payment method selected";
    }
    // Validate transaction ID format (assuming it should be at least 10 characters)
    else if (strlen($transaction_id) < 10) {
        $error = "Invalid transaction ID. Please enter a valid ID from your payment app";
    }
    // Check if transaction ID is already used
    else if (isTransactionIdUsed($transaction_id)) {
        $error = "This transaction ID has already been used";
    }
    else {
        // Update booking payment status
        if (updateBookingPayment($booking['id'], $payment_method, $transaction_id)) {
            // Send confirmation email
            $to = $_SESSION['email'];
            $subject = "Car Rental - Payment Confirmation";
            $message = "
            Dear " . $_SESSION['name'] . ",
            
            Your payment has been confirmed for the following booking:
            
            Car: {$car['name']} {$car['model']}
            Start Date: " . date('d M Y', strtotime($booking['start_date'])) . "
            End Date: " . date('d M Y', strtotime($booking['end_date'])) . "
            Total Amount: ₹" . number_format($booking['total_amount'], 2) . "
            Payment Method: $payment_method
            Transaction ID: $transaction_id
            
            Thank you for choosing our service!
            
            Best regards,
            Car Rental Team
            ";
            
            $headers = "From: noreply@carrental.com";
            
            mail($to, $subject, $message, $headers);
            
            header('Location: dashboard.php?success=Payment confirmed successfully');
            exit();
        } else {
            $error = "Failed to process payment. Please try again.";
        }
    }
}

// Calculate time remaining for payment
$time_remaining = $expiry_time - time();
$hours_remaining = floor($time_remaining / 3600);
$minutes_remaining = floor(($time_remaining % 3600) / 60);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Payment</h5>
                </div>
                <div class="card-body">
                    <!-- Payment Timer -->
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-clock me-2"></i>
                        Time remaining to complete payment: 
                        <strong><?php echo $hours_remaining; ?> hours <?php echo $minutes_remaining; ?> minutes</strong>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <img src="../<?php echo htmlspecialchars($car['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($car['name']); ?>" 
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h4><?php echo htmlspecialchars($car['name']); ?></h4>
                            <p class="text-muted">
                                <?php echo htmlspecialchars($car['model']); ?> | 
                                <?php echo htmlspecialchars($car['year']); ?>
                            </p>
                            <div class="mb-2">
                                <small class="d-block">Start Date: <?php echo date('d M Y', strtotime($booking['start_date'])); ?></small>
                                <small class="d-block">End Date: <?php echo date('d M Y', strtotime($booking['end_date'])); ?></small>
                            </div>
                            <h5 class="text-primary">Total Amount: ₹<?php echo number_format($booking['total_amount'], 2); ?></h5>
                        </div>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" id="paymentForm">
                        <div class="mb-4">
                            <label class="form-label">Select Payment Method</label>
                            <div class="row g-3">
                                <?php foreach ($payment_methods as $method => $details): ?>
                                <div class="col-md-4">
                                    <div class="form-check payment-method">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="payment_method" 
                                               value="<?php echo $method; ?>" 
                                               id="<?php echo $method; ?>" 
                                               required>
                                        <label class="form-check-label" for="<?php echo $method; ?>">
                                            <img src="../<?php echo $details['image']; ?>" 
                                                 alt="<?php echo $method; ?>" 
                                                 height="30">
                                            <?php echo $method; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Transaction ID</label>
                            <input type="text" 
                                   name="transaction_id" 
                                   class="form-control" 
                                   required 
                                   pattern=".{10,}"
                                   title="Transaction ID must be at least 10 characters long">
                            <div class="form-text">Enter the transaction ID from your payment app</div>
                        </div>

                        <div class="alert alert-info">
                            <h6>Payment Instructions:</h6>
                            <ol class="mb-0">
                                <li>Open your selected payment app</li>
                                <li>Send payment to the following number:
                                    <div class="payment-numbers mt-2" style="display: none;">
                                        <?php foreach ($payment_methods as $method => $details): ?>
                                        <div class="payment-number" id="<?php echo $method; ?>-number">
                                            <strong><?php echo $method; ?>:</strong> <?php echo $details['number']; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </li>
                                <li>Copy the transaction ID</li>
                                <li>Paste the transaction ID above</li>
                            </ol>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Confirm Payment</button>
                            <a href="dashboard.php" class="btn btn-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #0d6efd;
}

.form-check-input:checked ~ .form-check-label .payment-method {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}

.payment-numbers .payment-number {
    display: none;
    margin-bottom: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentNumbers = document.querySelector('.payment-numbers');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Hide all payment numbers
            document.querySelectorAll('.payment-number').forEach(num => num.style.display = 'none');
            // Show selected payment number
            if (this.checked) {
                paymentNumbers.style.display = 'block';
                document.getElementById(this.value + '-number').style.display = 'block';
            }
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?> 