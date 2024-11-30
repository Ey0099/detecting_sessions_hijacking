<?php
session_start();
require_once 'db.php';  // Database connection and functions

$errorMessage = ""; // Variable to hold error messages

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['password'], $_POST['fullname'], $_POST['email'], $_POST['phone'])) {
    $userId = $_POST['user_id'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Use password hashing for security
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Register new user
    $db = getDBConnection();

    // Check if user_id already exists
    $checkStmt = $db->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
    $checkStmt->execute([$userId]);
    $userExists = $checkStmt->fetchColumn();

    if ($userExists > 0) {
        $errorMessage = "Error: Username already exists. Please choose a different Username.";
    } else {
        // Proceed with registration
        $stmt = $db->prepare("INSERT INTO users (user_id, password, fullname, email, phone) VALUES (?, ?, ?, ?, ?)");

        if ($stmt->execute([$userId, $password, $fullname, $email, $phone])) {
            $_SESSION['user_id'] = $userId;
            logUserActivity($userId);  // Log activity
            header("Location: dashboard.php");
            exit();
        } else {
            $errorMessage = "Error: Could not register user.";
        }
    }
}
?>
<?php include('src/header.php'); ?>
<section class="py-5 bg-light" id="register">
    <div class="container">
        <h2 class="text-center mb-4">Register to HijackGuard</h2>

        <!-- Alert for error messages -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('src/footer.php'); ?>
