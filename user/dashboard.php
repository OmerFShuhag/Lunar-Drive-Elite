<?php
require_once '../includes/functions.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

// Get user data
$user_data = getUserById($_SESSION['user_id']);
if (!$user_data) {
    header('Location: ../logout.php');
    exit();
}

// Get user bookings
$user_bookings = getUserBookings($_SESSION['user_id']);

// Success message handling
$success_message = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
?>


<div class="container py-5 mt-5">
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- User Info Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2" style="color: #c5a47e;"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="../<?php echo htmlspecialchars($user_data['profile_pic']); ?>" 
                             alt="Profile Picture"
                             class="rounded-circle border-3 border"
                             style="width: 120px; height: 120px; object-fit: cover; border-color: #1a237e !important;">
                    </div>
                    <h6 class="fw-bold text-primary" style="color: #1a237e !important;"><?php echo htmlspecialchars($user_data['name']); ?></h6>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-2" style="color: #c5a47e;"></i>
                        <?php echo htmlspecialchars($user_data['email']); ?>
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-2" style="color: #c5a47e;"></i>
                        <?php echo htmlspecialchars($user_data['phone']); ?>
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-2" style="color: #c5a47e;"></i>
                        <?php echo htmlspecialchars($user_data['address']); ?>
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar me-2" style="color: #c5a47e;"></i>
                        Member since: <?php echo date('M Y', strtotime($user_data['created_at'])); ?>
                    </p>
                    <hr>
                    <div class="text-center">
                        <a href="edit_profile.php" class="btn btn-primary" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); border: none;">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings History -->
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2" style="color: #c5a47e;"></i>
                        Booking History
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($user_bookings)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x mb-3" style="color: #c5a47e;"></i>
                            <h5 class="text-muted">No Bookings Yet</h5>
                            <p class="text-muted mb-3">You haven't made any car bookings yet.</p>
                            <a href="../Cars.php" class="btn btn-primary" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); border: none;">Browse Cars</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Car</th>
                                        <th>Dates</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_bookings as $booking): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../<?php echo htmlspecialchars($booking['car_image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($booking['car_name']); ?>"
                                                     class="img-thumbnail me-2" 
                                                     style="width: 50px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0" style="color: #1a237e;"><?php echo htmlspecialchars($booking['car_name']); ?></h6>
                                                    <small class="text-muted"><?php echo htmlspecialchars($booking['car_model']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="d-block">From: <?php echo date('d M Y', strtotime($booking['start_date'])); ?></small>
                                                <small class="d-block">To: <?php echo date('d M Y', strtotime($booking['end_date'])); ?></small>
                                            </div>
                                        </td>
                                        <td style="color: #1a237e;">₹<?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td>
                                            <?php if ($booking['payment_status'] == 'pending'): ?>
                                                <span class="badge bg-warning">Payment Pending</span>
                                            <?php elseif ($booking['payment_status'] == 'paid'): ?>
                                                <span class="badge" style="background: #c5a47e;">Paid</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($booking['payment_status'] == 'pending'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm text-white"
                                                        style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%);"
                                                        onclick="proceedToPayment(<?php echo $booking['id']; ?>)">
                                                    Pay Now
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" 
                                                    class="btn btn-sm"
                                                    style="background: #c5a47e; color: white;"
                                                    onclick="viewBookingDetails(<?php echo $booking['id']; ?>)">
                                                Details
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<script>
function proceedToPayment(bookingId) {
    window.location.href = `payment.php?booking_id=${bookingId}`;
}

function viewBookingDetails(bookingId) {
    fetch(`get_booking_details.php?id=${bookingId}`)
        .then(response => response.text())
        .then(html => {
            document.querySelector('#bookingDetailsModal .modal-content').innerHTML = html;
            new bootstrap.Modal(document.getElementById('bookingDetailsModal')).show();
        })
        .catch(error => {
            alert('Error loading booking details. Please try again.');
        });
}
</script>

<style>
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.badge {
    padding: 0.5em 0.8em;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
}

.card {
    border: none;
    border-radius: 10px;
}

.table-hover tbody tr:hover {
    background-color: rgba(26, 35, 126, 0.05);
}
</style>

<?php require_once '../includes/footer.php'; ?>