<?php include('src/header.php'); ?>

<!-- Hero Section -->
<section class="pt-6 bg-600" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5 col-lg-6 order-0 order-md-1 text-end">
                <img class="pt-7 pt-md-0 w-100" src="/src/assets/img/sec.jpg" width="470" alt="about-header" />
            </div>
            <div class="col-md-7 col-lg-6 text-md-start text-center py-6">
                <h4 class="fw-bold font-sans-serif">Protect Your Online Sessions</h4>
                <h1 class="fs-6 fs-xl-7 mb-5">About HijackGuard: Real-Time Session Protection</h1>
                <p class="fs-6">Our intelligent system detects and prevents session hijacking in real-time, securing your sensitive information online.</p>
            </div>
        </div>
    </div>
</section>

<!-- Overview Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Overview: Detecting Session Hijacking</h2>
        <p class="text-center mb-5">Session hijacking is an attack where a malicious actor intercepts and uses a user's session ID to gain unauthorized access to their account. Our system is built to detect and prevent these attacks in real-time.</p>

        <div class="row text-center">
            <!-- Key Step 1: Session Monitoring -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Session Monitoring</h5>
                        <p class="card-text">Continuously monitor user sessions and analyze behavior patterns such as IP address, geolocation, and response time to detect anomalies.</p>
                    </div>
                </div>
            </div>
            <!-- Key Step 2: Anomaly Detection -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Anomaly Detection</h5>
                        <p class="card-text">Use machine learning to identify unusual activity, such as changes in IP addresses, sudden location shifts, or abnormal request patterns that may indicate session hijacking.</p>
                    </div>
                </div>
            </div>
            <!-- Key Step 3: Session Signature Analysis -->
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
    </div>
</section>

<!-- Immediate Protection Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Immediate Protection</h2>
        <p class="text-center mb-5">Once suspicious behavior is detected, HijackGuard takes immediate actions to protect the user's session and sensitive data.</p>

        <div class="row text-center">
            <!-- Protection Step 1: Terminate the Session -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Terminate the Session</h5>
                        <p class="card-text">Terminate any suspicious sessions to protect users from unauthorized access.</p>
                    </div>
                </div>
            </div>
            <!-- Protection Step 2: Require Authentication -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Require Additional Authentication</h5>
                        <p class="card-text">Require users to authenticate again if suspicious activity is detected.</p>
                    </div>
                </div>
            </div>
            <!-- Protection Step 3: Alert the User -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Alert the User</h5>
                        <p class="card-text">Notify the user of any suspicious behavior and provide instructions on how to secure their account.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Continuous Improvement Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Logging and Continuous Improvement</h2>
        <p class="text-center mb-5">We log all session activities to enhance the detection model and prevent future attacks based on new data and patterns.</p>
    </div>
</section>

<?php include('src/footer.php'); ?>
