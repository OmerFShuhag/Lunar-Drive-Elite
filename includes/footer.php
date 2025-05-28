<?php
require_once __DIR__ . '/functions.php';
?>
<footer id="site-footer" class="text-white pt-5 pb-3 mt-auto" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%);">
    <div class="container">
        <div class="row">
            <!-- About Us -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3" style="color: #c5a47e;">About LunarDrive Elite</h5>
                <p class="mb-3">Experience luxury and comfort with our premium car rental service. We offer the finest vehicles for your celestial journey.</p>
                <div class="d-flex gap-3 social-icons">
                    <a href="#" class="text-white" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-white" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-white" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-white" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3" style="color: #c5a47e;">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo !str_contains($_SERVER['REQUEST_URI'], 'admin/') ? '' : '../'; ?>index.php" class="footer-link">
                            <i class="fas fa-chevron-right small me-2" style="color: #c5a47e;"></i>Home
                        </a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                    <li class="mb-2">
                        <a href="<?php echo !str_contains($_SERVER['REQUEST_URI'], 'admin/') ? '' : '../'; ?>cars.php" class="footer-link">
                            <i class="fas fa-chevron-right small me-2" style="color: #c5a47e;"></i>Our Fleet
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo !str_contains($_SERVER['REQUEST_URI'], 'admin/') ? '' : '../'; ?>index.php#featured-cars" class="footer-link featured-cars-link">
                            <i class="fas fa-chevron-right small me-2" style="color: #c5a47e;"></i>Featured Cars
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (!isLoggedIn()): ?>
                    <li class="mb-2">
                        <a href="<?php echo !str_contains($_SERVER['REQUEST_URI'], 'admin/') ? '' : '../'; ?>login.php" class="footer-link">
                            <i class="fas fa-chevron-right small me-2" style="color: #c5a47e;"></i>Login
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo !str_contains($_SERVER['REQUEST_URI'], 'admin/') ? '' : '../'; ?>registration.php" class="footer-link">
                            <i class="fas fa-chevron-right small me-2" style="color: #c5a47e;"></i>Register
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3" style="color: #c5a47e;">Contact Info</h5>
                <div class="mb-3">
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt me-2" style="color: #c5a47e;"></i>
                        123 Lunar Avenue, Star City, SC 12345
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-phone-alt me-2" style="color: #c5a47e;"></i>
                        <a href="tel:+1234567890" class="footer-link">+1 (234) 567-890</a>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2" style="color: #c5a47e;"></i>
                        <a href="mailto:contact@lunardrive.com" class="footer-link">contact@lunardrive.com</a>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-clock me-2" style="color: #c5a47e;"></i>
                        Mon - Sat: 9:00 AM - 7:00 PM
                    </p>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3" style="color: #c5a47e;">Newsletter</h5>
                <p class="mb-3">Subscribe to our newsletter for celestial updates and exclusive offers!</p>
                <form action="" method="POST" class="newsletter-form">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Your email" required>
                        <button class="btn" type="submit" style="background-color: #c5a47e; color: white;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="row mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-md-0">&copy; <?php echo date('Y'); ?> LunarDrive Elite. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="back-to-top" class="btn rounded-circle" style="background-color: #c5a47e; color: white;" title="Back to Top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Back to Top Button
document.addEventListener('DOMContentLoaded', function() {
    var backToTop = document.getElementById('back-to-top');
    
    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            backToTop.style.display = "block";
        } else {
            backToTop.style.display = "none";
        }
    };
    
    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Handle Featured Cars link
    document.querySelector('.featured-cars-link')?.addEventListener('click', function(e) {
        const currentPage = window.location.pathname;
        if (!currentPage.endsWith('index.php')) {
            e.preventDefault();
            window.location.href = (currentPage.includes('admin/') ? '../' : '') + 'index.php#featured-cars';
        }
    });
});
</script>

<style>
/* Footer Styles */
.footer-link {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-link:hover {
    color: #c5a47e;
}

.social-icons a {
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.social-icons a:hover {
    background: #c5a47e;
    transform: translateY(-3px);
}

.newsletter-form .form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
}

.newsletter-form .form-control::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.newsletter-form .form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #c5a47e;
    box-shadow: 0 0 0 0.25rem rgba(197, 164, 126, 0.25);
}

#back-to-top {
    position: fixed;
    bottom: 25px;
    right: 25px;
    display: none;
    width: 40px;
    height: 40px;
    z-index: 999;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

#back-to-top:hover {
    background-color: #b08d5e !important;
    transform: translateY(-2px);
}

@media (max-width: 767.98px) {
    .social-icons {
        justify-content: center;
    }
    
    .footer-link {
        display: inline-block;
        margin-bottom: 0.5rem;
    }
}
</style>

</body>
</html>