<?php include('src/header.php'); ?>

<!-- Hero Section -->
<section class="pt-6 bg-600" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5 col-lg-6 order-0 order-md-1 text-end">
                <img class="pt-7 pt-md-0 w-100" src="/src/assets/img/cover.png" width="470" alt="hero-header" />
            </div>
            <div class="col-md-7 col-lg-6 text-md-start text-center py-6">
                <h4 class="fw-bold font-sans-serif">Master the Art of Web Security</h4>
                <h1 class="fs-6 fs-xl-7 mb-5">Learn How HijackGuard Protects Sessions in Real-Time</h1>
                <a class="btn btn-primary me-2" href="#!" role="button">Get A Quote</a>
                <a class="btn btn-outline-secondary" href="#!" role="button">Learn More</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Key Features of HijackGuard</h2>
        <div class="row text-center">
            <!-- Feature 1: Session Monitoring -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Session Monitoring</h5>
                        <p class="card-text">Continuously monitor user sessions, analyzing behavior patterns such as IP address, geolocation, and response time to detect anomalies.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 2: Anomaly Detection -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Anomaly Detection</h5>
                        <p class="card-text">Use machine learning to identify unusual activity, such as changes in IP addresses or abnormal request patterns, that may indicate session hijacking.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 3: Session Signature Analysis -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-key fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Session Signature Analysis</h5>
                        <p class="card-text">Analyze session identifiers (cookies, tokens) for unauthorized changes, signaling a potential hijacking attempt.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-center">
            <!-- Feature 4: Immediate Protection -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Immediate Protection</h5>
                        <p class="card-text">Terminate suspicious sessions, alert users of potential hijacking attempts, and require additional authentication when necessary.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 5: Logging and Continuous Improvement -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-database fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Logging & Continuous Improvement</h5>
                        <p class="card-text">Log all session activities and improve the detection model over time based on new data and attack patterns.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 6: Real-Time Alerts -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Real-Time Alerts</h5>
                        <p class="card-text">Provide real-time alerts and take immediate protective actions to prevent session hijacking attempts.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php include('src/footer.php'); ?>
