<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

function isLoggedIn() {
    return isset($_SESSION['email']);
}

function isAdmin() {
    return $_SESSION['email'] == 'admin@g.c';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Car Management Functions
function getAllCarsWithStatus() {
    global $conn;
    $query = "SELECT c.*, 
              CASE WHEN b.id IS NOT NULL THEN 1 ELSE 0 END as is_booked,
              COALESCE(b.payment_status, 'none') as payment_status
              FROM cars c
              LEFT JOIN bookings b ON c.id = b.car_id AND b.status = 'active'
              ORDER BY c.is_featured DESC, c.id DESC";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getAllAvailableCars() {
    global $conn;
    $query = "SELECT c.*, 
              CASE WHEN b.id IS NOT NULL AND b.status = 'active' AND 
                   (b.payment_status = 'paid' OR 
                    (b.payment_status = 'pending' AND b.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)))
              THEN 0 ELSE 1 END as is_available
              FROM cars c
              LEFT JOIN bookings b ON c.id = b.car_id AND b.status = 'active'
              ORDER BY c.id DESC";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getCarById($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM cars WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function insertCar($car_data) {
    global $conn;
    
    $name = mysqli_real_escape_string($conn, $car_data['name']);
    $model = mysqli_real_escape_string($conn, $car_data['model']);
    $year = mysqli_real_escape_string($conn, $car_data['year']);
    $price_per_day = mysqli_real_escape_string($conn, $car_data['price_per_day']);
    $seats = mysqli_real_escape_string($conn, $car_data['seats']);
    $transmission = mysqli_real_escape_string($conn, $car_data['transmission']);
    $fuel_type = mysqli_real_escape_string($conn, $car_data['fuel_type']);
    $description = mysqli_real_escape_string($conn, $car_data['description']);
    $image = mysqli_real_escape_string($conn, $car_data['image']);
    
    $query = "INSERT INTO cars (name, model, year, price_per_day, seats, transmission, fuel_type, description, image) 
              VALUES ('$name', '$model', '$year', '$price_per_day', '$seats', '$transmission', '$fuel_type', '$description', '$image')";
    
    return mysqli_query($conn, $query);
}

function updateCar($id, $car_data) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $name = mysqli_real_escape_string($conn, $car_data['name']);
    $model = mysqli_real_escape_string($conn, $car_data['model']);
    $year = mysqli_real_escape_string($conn, $car_data['year']);
    $price_per_day = mysqli_real_escape_string($conn, $car_data['price_per_day']);
    $seats = mysqli_real_escape_string($conn, $car_data['seats']);
    $transmission = mysqli_real_escape_string($conn, $car_data['transmission']);
    $fuel_type = mysqli_real_escape_string($conn, $car_data['fuel_type']);
    $description = mysqli_real_escape_string($conn, $car_data['description']);
    
    // Get current car data to handle image update
    $current_car = getCarById($id);
    
    $query = "UPDATE cars SET 
              name = '$name',
              model = '$model',
              year = '$year',
              price_per_day = '$price_per_day',
              seats = '$seats',
              transmission = '$transmission',
              fuel_type = '$fuel_type',
              description = '$description'";
    
    if (isset($car_data['image'])) {
        $image = mysqli_real_escape_string($conn, $car_data['image']);
        // Delete old image if it exists
        if ($current_car && !empty($current_car['image']) && file_exists("../" . $current_car['image'])) {
            unlink("../" . $current_car['image']);
        }
        $query .= ", image = '$image'";
    }
    
    $query .= " WHERE id = '$id'";
    
    return mysqli_query($conn, $query);
}

function deleteCar($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    
    // First get the car image to delete the file
    $car = getCarById($id);
    if ($car && file_exists("../" . $car['image'])) {
        unlink("../" . $car['image']);
    }
    
    $query = "DELETE FROM cars WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

// Featured Car Functions
function getFeaturedCars($limit = 6) {
    global $conn;
    $limit = (int)$limit;
    $query = "SELECT c.*, 
              CASE WHEN b.id IS NOT NULL AND b.status = 'active' AND 
                   (b.payment_status = 'paid' OR 
                    (b.payment_status = 'pending' AND b.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)))
              THEN 0 ELSE 1 END as is_available
              FROM cars c
              LEFT JOIN bookings b ON c.id = b.car_id AND b.status = 'active'
              WHERE c.is_featured = 1
              ORDER BY c.id DESC LIMIT $limit";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function toggleFeaturedCar($car_id, $featured_status) {
    global $conn;
    $car_id = mysqli_real_escape_string($conn, $car_id);
    $featured_status = (int)$featured_status;
    
    $query = "UPDATE cars SET is_featured = $featured_status WHERE id = '$car_id'";
    return mysqli_query($conn, $query);
}

// Booking Functions
function createBooking($booking_data) {
    global $conn;
    
    $user_id = (int)$booking_data['user_id'];
    $car_id = (int)$booking_data['car_id'];
    $start_date = mysqli_real_escape_string($conn, $booking_data['start_date']);
    $end_date = mysqli_real_escape_string($conn, $booking_data['end_date']);
    $total_amount = (float)$booking_data['total_amount'];
    $payment_status = mysqli_real_escape_string($conn, $booking_data['payment_status']);
    
    $query = "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_amount, payment_status, status) 
              VALUES ($user_id, $car_id, '$start_date', '$end_date', $total_amount, '$payment_status', 'active')";
    
    return mysqli_query($conn, $query);
}

function getUserBookings($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    
    $query = "SELECT b.*, c.name as car_name, c.model as car_model, c.image as car_image
              FROM bookings b
              JOIN cars c ON b.car_id = c.id
              WHERE b.user_id = $user_id
              ORDER BY b.created_at DESC";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getBookingById($booking_id) {
    global $conn;
    $booking_id = (int)$booking_id;
    
    $query = "SELECT b.*, c.name as car_name, c.model as car_model, c.image as car_image
              FROM bookings b
              JOIN cars c ON b.car_id = c.id
              WHERE b.id = $booking_id";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function updateBookingPayment($booking_id, $payment_method, $transaction_id) {
    global $conn;
    
    $booking_id = (int)$booking_id;
    $payment_method = mysqli_real_escape_string($conn, $payment_method);
    $transaction_id = mysqli_real_escape_string($conn, $transaction_id);
    
    $query = "UPDATE bookings SET 
              payment_status = 'paid',
              payment_method = '$payment_method',
              transaction_id = '$transaction_id',
              paid_at = NOW()
              WHERE id = $booking_id";
    
    return mysqli_query($conn, $query);
}

function isCarBooked($car_id) {
    global $conn;
    $car_id = (int)$car_id;
    
    $query = "SELECT b.* FROM bookings b
              WHERE b.car_id = $car_id 
              AND b.status = 'active'
              AND (
                  (b.payment_status = 'paid') OR 
                  (b.payment_status = 'pending' AND b.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR))
              )";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Auto-release cars after return date + 2 days
function releaseExpiredBookings() {
    global $conn;
    
    $query = "UPDATE bookings 
              SET status = 'completed' 
              WHERE status = 'active' 
              AND end_date < DATE_SUB(NOW(), INTERVAL 2 DAY)";
    
    return mysqli_query($conn, $query);
}

function getUserById($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function hasOverlappingBookings($car_id, $start_date, $end_date, $exclude_booking_id = null) {
    global $conn;
    
    $car_id = (int)$car_id;
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);
    
    $exclude_clause = $exclude_booking_id ? " AND id != " . (int)$exclude_booking_id : "";
    
    $query = "SELECT id FROM bookings 
              WHERE car_id = $car_id 
              AND status = 'active'
              AND payment_status = 'paid'
              AND (
                  (start_date BETWEEN '$start_date' AND '$end_date')
                  OR (end_date BETWEEN '$start_date' AND '$end_date')
                  OR ('$start_date' BETWEEN start_date AND end_date)
              )
              $exclude_clause";
    
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

function cleanupExpiredBookings() {
    global $conn;
    
    // Cancel bookings with pending payment after 24 hours
    $query = "UPDATE bookings 
              SET status = 'cancelled' 
              WHERE status = 'active' 
              AND payment_status = 'pending' 
              AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)";
    mysqli_query($conn, $query);
    
    // Complete bookings after return date + 2 days
    $query = "UPDATE bookings 
              SET status = 'completed' 
              WHERE status = 'active' 
              AND end_date < DATE_SUB(NOW(), INTERVAL 2 DAY)";
    mysqli_query($conn, $query);
}

function validateBookingDates($start_date, $end_date) {
    $start = strtotime($start_date);
    $end = strtotime($end_date);
    $now = strtotime('today');
    $max_date = strtotime('+30 days');
    
    if ($start < $now) {
        return "Start date cannot be in the past";
    }
    
    if ($start > $max_date || $end > $max_date) {
        return "Booking dates cannot be more than 30 days in advance";
    }
    
    if ($end <= $start) {
        return "End date must be after start date";
    }
    
    $days = ($end - $start) / (60 * 60 * 24);
    if ($days < 1) {
        return "Minimum booking duration is 1 day";
    }
    
    if ($days > 30) {
        return "Maximum booking duration is 30 days";
    }
    
    return null;
}

function isTransactionIdUsed($transaction_id) {
    global $conn;
    $transaction_id = mysqli_real_escape_string($conn, $transaction_id);
    
    $query = "SELECT id FROM bookings WHERE transaction_id = '$transaction_id'";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

function cancelBooking($booking_id) {
    global $conn;
    $booking_id = (int)$booking_id;
    
    $query = "UPDATE bookings SET status = 'cancelled' WHERE id = $booking_id";
    return mysqli_query($conn, $query);
}

function getCarBookings($car_id) {
    global $conn;
    $car_id = (int)$car_id;
    
    $query = "SELECT * FROM bookings 
              WHERE car_id = $car_id 
              AND status = 'active'
              AND payment_status = 'paid'
              ORDER BY start_date ASC";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
