<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include 'includes/functions.php';
include 'includes/connect.php';
require 'vendor/autoload.php'; // Ensure you have PHPMailer installed via Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];
    $phone      = $_POST['phone'];
    $address    = $_POST['address'];
    $profilePic = $_FILES['profile_pic'];
    $idCardPic  = $_FILES['id_card_pic'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm) || empty($phone) || empty($address)) {
        echo "<script>alert('All fields are required.'); window.location.href='registration.php';</script>";
        exit;
    }

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match.'); window.location.href='registration.php';</script>";
        exit;
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered.'); window.location.href='registration.php';</script>";
        exit;
    }

    // Upload images
    $uploadDir = 'images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $profilePicPath = $uploadDir . time() . '_' . basename($profilePic['name']);
    $idCardPicPath  = $uploadDir . time() . '_' . basename($idCardPic['name']);

    move_uploaded_file($profilePic['tmp_name'], $profilePicPath);
    move_uploaded_file($idCardPic['tmp_name'], $idCardPicPath);

    // Token & hashed password
    $verify_token = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, profile_pic, id_card_pic, verify_token, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssssssss", $name, $email, $hashedPassword, $phone, $address, $profilePicPath, $idCardPicPath, $verify_token);

    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '';
            $mail->Password   = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('cse_0182210012101128@lus.ac.bd', 'Car Rental');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification - Car Rental';
            $mail->Body    = "Hello $name,<br><br>
                Please verify your email:<br>
                <a href='http://localhost/GARAGE/verify.php?token=$verify_token'>Verify Email</a><br><br>
                Thank you!";

            $mail->send();
            echo "<script>alert('Registration successful. Please verify your email.'); window.location.href='login.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error sending email: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('Error registering user.'); window.location.href='registration.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Car Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .preview-container {
            width: 200px;
            height: 200px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        .preview-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .preview-placeholder {
            color: #6c757d;
            text-align: center;
            padding: 20px;
        }
        .preview-placeholder i {
            font-size: 3rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%);">
                    <h4 class="mb-0">
                        <i class="fas fa-moon me-2" style="color: #c5a47e;"></i>
                        Join LunarDrive Elite
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="registration.php" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <label class="form-label text-muted">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-primary"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-primary"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-primary"></i>
                                    </span>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-phone text-primary"></i>
                                    </span>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </span>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="col-md-6">
                                <label class="form-label text-muted">Profile Picture</label>
                                <input type="file" name="profile_pic" class="form-control" accept="image/*" required onchange="previewImage(this, 'profile-preview')">
                                <div class="preview-container mt-2" id="profile-preview">
                                    <div class="preview-placeholder text-center p-3 border rounded">
                                        <i class="fas fa-user fa-2x text-primary"></i>
                                        <p class="mb-0 text-muted">Profile Picture Preview</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">ID Card Picture</label>
                                <input type="file" name="id_card_pic" class="form-control" accept="image/*" required onchange="previewImage(this, 'id-preview')">
                                <div class="preview-container mt-2" id="id-preview">
                                    <div class="preview-placeholder text-center p-3 border rounded">
                                        <i class="fas fa-id-card fa-2x text-primary"></i>
                                        <p class="mb-0 text-muted">ID Card Preview</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); border: none;">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Register
                                </button>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <p class="mb-0">Already have an account? <a href="login.php" class="text-primary fw-bold" style="color: #1a237e !important;">Login here</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const placeholder = preview.querySelector('.preview-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (placeholder) {
                placeholder.style.display = 'none';
            }
            
            let img = preview.querySelector('img');
            if (!img) {
                img = document.createElement('img');
                preview.appendChild(img);
            }
            img.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        if (placeholder) {
            placeholder.style.display = 'block';
        }
        const img = preview.querySelector('img');
        if (img) {
            img.remove();
        }
    }
}
</script>

<style>
.form-control:focus {
    border-color: #1a237e;
    box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.25);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #283593 0%, #1a237e 100%) !important;
}

.input-group-text {
    border: none;
}

.input-group-text i {
    color: #1a237e !important;
}

.preview-container {
    max-width: 100%;
    overflow: hidden;
}

.preview-container img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.preview-placeholder {
    background: #f8f9fa;
    border-radius: 8px;
}

.preview-placeholder i {
    color: #1a237e;
}
</style>

<?php include 'includes/footer.php'; ?>

</body>
</html>
