<?php
include "includes/functions.php";
include "includes/header.php";?>

<?php include "includes/navbar.php";?>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] === 'true'): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Your profile has been successfully deleted. We're sorry to see you go!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
  
<!-- Hero Section -->
<section id="hero-section" class="hero-area position-relative">
    <div class="hero-slider">
        <div class="hero-bg"></div>
        <div class="container position-relative">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-7">
                    <div class="hero-content text-white">
                        <span class="text-uppercase fw-bold mb-3 d-block hero-subtitle">Your Journey Begins Here</span>
                        <h1 class="display-3 fw-bold mb-4 hero-title">Drive Your Dreams<br>With Our Premium Cars</h1>
                        <p class="lead mb-4 hero-description">Experience luxury and comfort with our extensive fleet of vehicles. 
                        From elegant sedans to powerful SUVs, find the perfect car for your next adventure.</p>
                        <div class="hero-buttons">
                            <?php if (isLoggedIn()): ?>
                            <a href="#featured-cars" class="btn btn-primary btn-lg me-3">View Our Fleet</a>
                            <a href="Cars.php" class="btn btn-outline-light btn-lg">Book Now</a>
                            <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-lg me-3">Login Now</a>
                            <a href="registration.php" class="btn btn-outline-light btn-lg">Register</a>
                            <?php endif; ?>
                        </div>
                        <div class="hero-features mt-5">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="fas fa-car-side mb-2"></i>
                                        <h5 class="mb-2">Latest Models</h5>
                                        <p class="mb-0">Premium vehicles for your comfort</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="fas fa-shield-alt mb-2"></i>
                                        <h5 class="mb-2">Fully Insured</h5>
                                        <p class="mb-0">Safe and secure rentals</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="fas fa-headset mb-2"></i>
                                        <h5 class="mb-2">24/7 Support</h5>
                                        <p class="mb-0">Always here to help you</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
:root {
    --primary-blue: #1a237e;  /* Deep Navy */
    --primary-gold: #c5a47e;  /* Rich Gold */
    --secondary-blue: #283593; /* Slightly lighter navy for hover */
    --text-dark: #1a1a1a;
    --text-light: #666;
}

.hero-area {
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1485291571150-772bcfc10da5?auto=format&fit=crop&w=2000&q=80');
    
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    position: relative;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    color: var(--primary-gold);
    font-size: 1.1rem;
    letter-spacing: 2px;
    background: rgba(26, 35, 126, 0.1);
    padding: 8px 16px;
    border-radius: 4px;
    display: inline-block;
}

.hero-title {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    line-height: 1.2;
}

.hero-description {
    font-size: 1.2rem;
    opacity: 0.9;
}

.hero-buttons .btn {
    padding: 12px 30px;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.hero-buttons .btn-primary {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
}

.hero-buttons .btn-primary:hover {
    background: var(--secondary-blue);
    border-color: var(--secondary-blue);
}

.hero-buttons .btn-outline-light:hover {
    background: var(--primary-gold);
    border-color: var(--primary-gold);
}

.hero-features {
    background: rgba(0, 0, 0, 0.2);
    padding: 30px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.feature-item {
    text-align: center;
    padding: 20px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.feature-item i {
    font-size: 2.5rem;
    color: var(--primary-gold);
}

.feature-item h5 {
    color: #fff;
    font-weight: 600;
}

.feature-item p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

@media (max-width: 991.98px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-features {
        margin-top: 3rem;
    }
}

@media (max-width: 767.98px) {
    .hero-content {
        text-align: center;
        padding: 60px 0;
    }
    
    .hero-buttons {
        justify-content: center;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .hero-features .col-md-4:not(:last-child) {
        margin-bottom: 1rem;
    }
}

/* Section Title Styles */
.section-title {
    margin-bottom: 3rem;
}

.section-title .subtitle {
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 2px;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    display: block;
}

.title-line {
    width: 80px;
    height: 3px;
    background: var(--primary-gold);
    margin: 1rem auto;
}

/* Featured Cars Styles */
.car-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(197, 164, 126, 0.1);
    transition: all 0.3s ease;
}

.car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(197, 164, 126, 0.2);
}

.car-image {
    position: relative;
    overflow: hidden;
}

.car-image img {
    height: 220px;
    object-fit: cover;
    width: 100%;
}

.car-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.car-card:hover .car-overlay {
    opacity: 1;
}

.car-tag {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--primary-blue);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.car-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #333;
}

.car-features {
    display: flex;
    gap: 15px;
    margin-bottom: 1.5rem;
}

.feature-item {
    font-size: 0.9rem;
    color: #666;
}

.feature-item i {
    color: var(--primary-gold);
    margin-right: 5px;
}

.car-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #eee;
    padding-top: 1rem;
}

.price {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary-blue);
}

.price span {
    font-size: 0.9rem;
    color: #666;
    font-weight: normal;
}

/* Stats Section Styles */
.stats-section {
    background: linear-gradient(rgba(0,0,0,0.9), rgba(0,0,0,0.9)), url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=2000&q=80');
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
}

.stat-item {
    padding: 2rem;
}

.stat-item i {
    font-size: 2.5rem;
    color: var(--primary-gold);
    margin-bottom: 1rem;
}

.stat-item h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-item p {
    color: rgba(255,255,255,0.8);
    font-size: 1.1rem;
    margin: 0;
}

/* Feature Box Styles */
.feature-box {
    padding: 2rem;
    border-radius: 15px;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.feature-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    background: linear-gradient(145deg, #fff 0%, rgba(197, 164, 126, 0.1) 100%);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: var(--primary-blue);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.feature-icon i {
    font-size: 1.8rem;
    color: var(--primary-gold);
}

.feature-box h4 {
    margin-bottom: 1rem;
    color: #333;
}

.feature-box p {
    color: #666;
    margin: 0;
}

/* Review Card Styles */
.review-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    height: 100%;
}

.review-profile {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.review-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-right: 1rem;
}

.review-info h5 {
    margin: 0 0 0.5rem;
    color: #333;
}

.stars {
    color: var(--primary-gold);
}

.review-text {
    color: #666;
    font-style: italic;
    margin-bottom: 1rem;
}

.review-date {
    color: #999;
    font-size: 0.9rem;
}

/* CTA Section Styles */
#cta {
    background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=2000&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    position: relative;
}

.cta-content {
    padding: 4rem 0;
}

.cta-buttons {
    margin-top: 2rem;
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .car-image img {
        height: 180px;
    }
    
    .stat-item {
        padding: 1.5rem;
    }
    
    .stat-item h3 {
        font-size: 2rem;
    }
}

@media (max-width: 767.98px) {
    .car-features {
        flex-direction: column;
        gap: 10px;
    }
    
    .cta-buttons {
        flex-direction: column;
        gap: 1rem;
    }
    
    .cta-buttons .btn {
        width: 100%;
    }
}

.text-primary {
    color: var(--primary-gold) !important;
}

.btn-primary {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-primary:hover {
    background: var(--secondary-blue);
    border-color: var(--secondary-blue);
}

.btn-outline-primary {
    color: var(--primary-gold);
    border-color: var(--primary-gold);
}

.btn-outline-primary:hover {
    background: var(--primary-gold);
    border-color: var(--primary-gold);
    color: white;
}

.car-tag-warning {
    background-color: #ffc107;
    top: 50px;
}
</style>

<!-- Featured Cars Section -->
<section id="featured-cars" class="py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle text-primary">Exclusive Selection</span>
            <h2 class="fw-bold">Featured Vehicles</h2>
            <div class="title-line"></div>
            <p class="text-muted">Experience luxury and performance with our handpicked collection</p>
        </div>
        <div class="row g-4">
            <?php
            $featured_cars = getFeaturedCars(6);
            
            if($featured_cars && count($featured_cars) > 0) {
                foreach($featured_cars as $car) {
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-card h-100">
                            <div class="car-image">
                                <img src="<?php echo $car['image']; ?>" class="card-img-top" alt="<?php echo $car['name']; ?>">
                                <div class="car-overlay">
                                    <?php if (isLoggedIn()): ?>
                                        <a href="user/book.php?id=<?php echo $car['id']; ?>" class="btn btn-light">Book Now</a>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-light">Login to Book</a>
                                    <?php endif; ?>
                                </div>
                                <div class="car-tag">Featured</div>
                                <?php if (!$car['is_available']): ?>
                                    <div class="car-tag car-tag-warning">Booked</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="car-title"><?php echo $car['name']; ?></h5>
                                <div class="car-features">
                                    <span class="feature-item"><i class="fas fa-users"></i> <?php echo $car['seats']; ?> Seats</span>
                                    <span class="feature-item"><i class="fas fa-gas-pump"></i> <?php echo $car['fuel_type']; ?></span>
                                    <span class="feature-item"><i class="fas fa-cog"></i> <?php echo $car['transmission']; ?></span>
                                </div>
                                <div class="car-footer">
                                    <div class="price">¥ <?php echo $car['price_per_day']; ?> <span>/ day</span></div>
                                    <?php if ($car['is_available']): ?>
                                        <?php if (isLoggedIn()): ?>
                                            <a href="user/book.php?id=<?php echo $car['id']; ?>" class="btn btn-primary">Book Now</a>
                                        <?php else: ?>
                                            <a href="login.php" class="btn btn-primary">Login to Book</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>Currently Unavailable</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center">No featured cars available at the moment.</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5 bg-dark text-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <i class="fas fa-car-side"></i>
                    <h3 class="counter">50+</h3>
                    <p>Premium Cars</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <h3 class="counter">1000+</h3>
                    <p>Happy Clients</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3 class="counter">10+</h3>
                    <p>Locations</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <i class="fas fa-star"></i>
                    <h3 class="counter">4.8</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section id="why-us" class="py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle text-primary">Our Advantages</span>
            <h2 class="fw-bold">Why Choose Us</h2>
            <div class="title-line"></div>
            <p class="text-muted">Experience the difference with our premium car rental service</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-car-side"></i>
                    </div>
                    <h4>Premium Fleet</h4>
                    <p>Choose from our extensive collection of luxury and premium vehicles, regularly maintained and updated.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h4>Best Price Guarantee</h4>
                    <p>We offer competitive rates and transparent pricing with no hidden fees or surprise charges.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>24/7 Support</h4>
                    <p>Our dedicated customer support team is always ready to assist you whenever you need help.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Fully Insured</h4>
                    <p>Drive with peace of mind knowing that all our vehicles are fully insured and well-maintained.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h4>Flexible Pick-up</h4>
                    <p>Choose from multiple convenient locations for vehicle pick-up and drop-off.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4>Easy Booking</h4>
                    <p>Simple and quick online booking process with instant confirmation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section id="reviews" class="py-5 bg-light">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle text-primary">Testimonials</span>
            <h2 class="fw-bold">What Our Clients Say</h2>
            <div class="title-line"></div>
            <p class="text-muted">Real experiences from our valued customers</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="review-card">
                    <div class="review-profile">
                        <img src="https://ui-avatars.com/api/?name=John+Smith&background=random" alt="John Smith" class="review-avatar">
                        <div class="review-info">
                            <h5>John Smith</h5>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="review-text">"Outstanding service! The car was in pristine condition, and the staff was incredibly helpful. Will definitely rent again!"</p>
                    <div class="review-date">2 days ago</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="review-card">
                    <div class="review-profile">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random" alt="Sarah Johnson" class="review-avatar">
                        <div class="review-info">
                            <h5>Sarah Johnson</h5>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="review-text">"Great experience! Competitive prices and a smooth booking process. The car was perfect for our family trip."</p>
                    <div class="review-date">1 week ago</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="review-card">
                    <div class="review-profile">
                        <img src="https://ui-avatars.com/api/?name=Michael+Brown&background=random" alt="Michael Brown" class="review-avatar">
                        <div class="review-info">
                            <h5>Michael Brown</h5>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="review-text">"Top-notch service and premium vehicles. The staff went above and beyond to ensure our satisfaction."</p>
                    <div class="review-date">2 weeks ago</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section id="cta" class="py-5">
    <div class="container">
        <div class="cta-content text-center">
            <h2 class="display-4 fw-bold mb-4">Ready for Your Next Adventure?</h2>
            <p class="lead mb-4">Experience the luxury and comfort of our premium vehicles</p>
            <div class="cta-buttons">
                <?php if (isLoggedIn()): ?>
                    <a href="Cars.php" class="btn btn-primary btn-lg me-3">Browse Our Fleet</a>
                    <a href="#" class="btn btn-outline-primary btn-lg">Contact Us</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-lg me-3">Login Now</a>
                    <a href="registration.php" class="btn btn-outline-primary btn-lg">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
  </section>

<?php include "includes/footer.php";?>
