<?php
session_start();
require_once 'db.php';

if (isset($_GET['unlock']) && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE users SET locked = 0 WHERE user_id = ?");
    $stmt->execute([$userId]);
    // Remove logs associated with the user
    $stmt = $db->prepare("DELETE FROM logs WHERE user_id = ?");
    $stmt->execute([$userId]);

    $error = "Your account has been unlocked and your logs have been cleared. Please login.";
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['password'])) {
    $userId = $_POST['user_id'];
    $password = $_POST['password'];

    // Verify login and check if the account is locked
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Check if the account is locked
        if ($user['locked']) {
            $error = "Your account is locked due to suspicious activity. <a href='login.php?unlock=1&user_id=" . urlencode($userId) . "'>Click here to unlock your account.</a>";
        } else {
            // Account is not locked, proceed with login
            $_SESSION['user_id'] = $userId;
            logUserActivity($userId);  // Log activity
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $error = "Invalid login credentials";
    }
}
?>

<?php include('src/header.php'); ?>

<section class="py-5 bg-light" id="login">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-6 card py-5">
                <h2 class="text-center mb-4">Login to HijackGuard</h2>
                <?php if (isset($_GET['error']) && $_GET['error'] == 'suspicious_activity' && isset($_GET['user_id'])): ?>
                    <div class="alert alert-warning">
                        Suspicious activity detected. Your account is locked.
                        <a href="login.php?unlock=1&user_id=<?php echo htmlspecialchars($_GET['user_id']); ?>">Click here to unlock your account.</a>
                    </div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('src/footer.php'); ?>
