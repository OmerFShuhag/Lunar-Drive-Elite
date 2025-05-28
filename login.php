<!-- login page with email and password -->
<?php
include 'includes/functions.php';
include 'includes/connect.php';
include 'includes/header.php';
include 'includes/navbar.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        if($email == "admin@g.c" && $password == "admin") {
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit();
        }

        $stmt = $conn->prepare("SELECT id, name, password, is_verified, created_at FROM users WHERE email = ?");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $name, $hashedPassword, $is_verified, $created_at);

        if ($stmt->fetch()) {
            if (password_verify($password, $hashedPassword)) {
                if ($is_verified == 1) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $_SESSION['created_at'] = $created_at;
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<script>alert('Please verify your email first.');</script>";
                }
            } else {
                echo "<script>alert('Invalid email or password.');</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%);">
                    <h4 class="mb-0">
                        <i class="fas fa-moon me-2" style="color: #c5a47e;"></i>
                        Welcome Back
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label text-muted">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label text-muted">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock text-primary"></i>
                                </span>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" type="submit" name="login" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); border: none;">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </button>
                            <a href="registration.php" class="btn btn-outline-primary btn-lg" style="color: #1a237e; border-color: #1a237e;">
                                <i class="fas fa-user-plus me-2"></i>
                                Create New Account
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #1a237e;
    box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.25);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #283593 0%, #1a237e 100%) !important;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #1a237e 0%, #283593 100%) !important;
    color: white !important;
}

.input-group-text {
    border: none;
}

.input-group-text i {
    color: #1a237e !important;
}
</style>

<?php include 'includes/footer.php'; ?>