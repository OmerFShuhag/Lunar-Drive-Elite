<?php
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    exit('Booking ID not provided');
}

$booking = getBookingById($_GET['id']);
if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    exit('Booking not found or unauthorized');
}

$car = getCarById($booking['car_id']);
?>

<div class="modal-header">
    <h5 class="modal-title">Booking Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <img src="../<?php echo htmlspecialchars($car['image']); ?>" 
                 alt="<?php echo htmlspecialchars($car['name']); ?>" 
                 class="img-fluid rounded">
        </div>
        <div class="col-md-8">
            <h5><?php echo htmlspecialchars($car['name']); ?></h5>
            <p class="text-muted"><?php echo htmlspecialchars($car['model']); ?> | <?php echo htmlspecialchars($car['year']); ?></p>
            
            <div class="mt-3">
                <h6>Booking Information</h6>
                <ul class="list-unstyled">
                    <li><strong>Start Date:</strong> <?php echo date('d M Y', strtotime($booking['start_date'])); ?></li>
                    <li><strong>End Date:</strong> <?php echo date('d M Y', strtotime($booking['end_date'])); ?></li>
                    <li><strong>Total Amount:</strong> ₹<?php echo number_format($booking['total_amount'], 2); ?></li>
                    <li>
                        <strong>Status:</strong>
                        <?php if ($booking['payment_status'] == 'pending'): ?>
                            <span class="badge bg-warning">Payment Pending</span>
                        <?php elseif ($booking['payment_status'] == 'paid'): ?>
                            <span class="badge bg-success">Paid</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            
            <?php if ($booking['payment_status'] == 'paid'): ?>
            <div class="mt-3">
                <h6>Payment Information</h6>
                <ul class="list-unstyled">
                    <li><strong>Payment Method:</strong> <?php echo htmlspecialchars($booking['payment_method']); ?></li>
                    <li><strong>Transaction ID:</strong> <?php echo htmlspecialchars($booking['transaction_id']); ?></li>
                    <li><strong>Paid On:</strong> <?php echo date('d M Y H:i', strtotime($booking['paid_at'])); ?></li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <?php if ($booking['payment_status'] == 'pending'): ?>
        <button type="button" 
                class="btn btn-primary"
                onclick="proceedToPayment(<?php echo $booking['id']; ?>)">
            Pay Now
        </button>
    <?php endif; ?>
</div> 