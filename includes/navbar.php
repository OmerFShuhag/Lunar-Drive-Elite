<!-- Navbar -->
<?php
$isSubdir = strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/user/') !== false;
$baseUrl = $isSubdir ? '../' : '';
?>
<nav id="main-navbar" class="navbar navbar-expand-lg navbar-dark shadow-sm py-3" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); z-index:1040;">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="<?php echo $baseUrl; ?>index.php">
            <i class="fas fa-moon me-2"></i>
            LunarDrive Elite
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content" aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbar-content" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo $baseUrl; ?>index.php">Home</a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $baseUrl; ?>admin/admin-dashboard.php">Admin Dashboard</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $baseUrl; ?>user/dashboard.php">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $baseUrl; ?>Cars.php">Cars</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex">
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo $baseUrl; ?>logout.php" class="btn btn-outline-light hover-gold" id="logout-button">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo $baseUrl; ?>login.php" class="btn btn-outline-light hover-gold me-2" id="login-button">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                    <a href="<?php echo $baseUrl; ?>registration.php" class="btn btn-light text-primary" id="register-button">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<style>
.hover-gold:hover {
    background-color: #c5a47e !important;
    border-color: #c5a47e !important;
}

.navbar-brand i {
    color: #c5a47e;
}

.nav-link:hover {
    color: #c5a47e !important;
}
</style>