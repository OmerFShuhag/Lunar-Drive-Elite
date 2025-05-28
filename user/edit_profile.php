<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Create profiles directory if it doesn't exist
$profile_pics_dir = "../images/profiles/";
if (!file_exists($profile_pics_dir)) {
    mkdir($profile_pics_dir, 0777, true);
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle profile deletion
if (isset($_POST['delete_profile']) && $_POST['delete_profile'] === 'yes') {
    // First, get the user's profile picture
    $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    // Delete the profile picture if it exists and is not the default
    if ($user_data['profile_pic'] && $user_data['profile_pic'] != 'default.jpg') {
        $pic_path = "../" . $user_data['profile_pic'];
        if (file_exists($pic_path)) {
            unlink($pic_path);
        }
    }

    // Delete user's bookings
    $stmt = $conn->prepare("DELETE FROM bookings WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        // Destroy session and redirect to home
        session_destroy();
        header('Location: ../index.php?deleted=true');
        exit();
    } else {
        $error_message = "Error deleting profile. Please try again.";
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT name, email, phone, address, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Validate inputs
    if (empty($name) || empty($phone) || empty($address)) {
        $error_message = "All fields are required.";
    } else {
        $update_query = "UPDATE users SET name = ?, phone = ?, address = ?";
        $params = array($name, $phone, $address);
        $types = "sss";
        
        // Handle profile picture upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES['profile_pic']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($filetype), $allowed)) {
                $temp_name = $_FILES['profile_pic']['tmp_name'];
                $new_filename = "profile_" . $user_id . "_" . time() . "." . $filetype;
                $upload_path = $profile_pics_dir . $new_filename;
                
                if (move_uploaded_file($temp_name, $upload_path)) {
                    // Delete old profile picture if it exists and is not default
                    if ($user['profile_pic'] && $user['profile_pic'] != 'default.jpg' && file_exists($profile_pics_dir . $user['profile_pic'])) {
                        unlink($profile_pics_dir . $user['profile_pic']);
                    }
                    
                    $update_query .= ", profile_pic = ?";
                    $params[] = "images/profiles/" . $new_filename;
                    $types .= "s";
                }
            }
        }
        
        $update_query .= " WHERE id = ?";
        $params[] = $user_id;
        $types .= "i";
        
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            $_SESSION['name'] = $name;
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $stmt = $conn->prepare("SELECT name, email, phone, address, profile_pic FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $error_message = "Error updating profile. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Car Rental System</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Edit Profile</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="text-center mb-4">
                                <?php 
                                $profile_pic = $user['profile_pic'] ? $user['profile_pic'] : 'images/profiles/default.jpg';
                                ?>
                                <img src="../<?php echo htmlspecialchars($profile_pic); ?>" 
                                     alt="Current Profile Picture" 
                                     class="rounded-circle border-3 border"
                                     style="width: 150px; height: 150px; object-fit: cover; border-color: #1a237e !important;">
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email (Cannot be changed)</label>
                                <input type="email" class="form-control" id="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" 
                                          required><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="profile_pic">Change Profile Picture</label>
                                <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*">
                                <small class="text-muted">Supported formats: JPG, JPEG, PNG</small>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); border: none;">Update Profile</button>
                                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                            </div>
                        </form>

                        <!-- Add Delete Profile Form -->
                        <hr class="my-4">
                        <div class="text-center">
                            <h5 class="text-danger mb-3">Delete Profile</h5>
                            <p class="text-muted">Warning: This action cannot be undone. All your data will be permanently deleted.</p>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                                Delete My Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Profile Confirmation Modal -->
    <div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-labelledby="deleteProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteProfileModalLabel">Confirm Profile Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action is permanent and cannot be undone!
                    </div>
                    <p>By deleting your profile, you will lose:</p>
                    <ul>
                        <li>All your booking history</li>
                        <li>Your profile information</li>
                        <li>Access to your account</li>
                    </ul>
                    <p>Are you absolutely sure you want to proceed?</p>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            Yes, I understand that this action is permanent and want to delete my profile
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="delete_profile" value="yes">
                        <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                            Delete Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script>
    // Enable/disable delete button based on checkbox
    document.getElementById('confirmDelete').addEventListener('change', function() {
        document.getElementById('confirmDeleteBtn').disabled = !this.checked;
    });
    </script>
</body>
</html> 