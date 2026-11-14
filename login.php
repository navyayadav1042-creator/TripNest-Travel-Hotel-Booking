<?php
require_once "config/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?"); $stmt->execute([trim($_POST['email'] ?? '')]); $user = $stmt->fetch();
  if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
    $_SESSION['user_id']=(int)$user['user_id']; $_SESSION['user_name']=$user['name']; $_SESSION['role']=$user['role'];
    header("Location: index.php"); exit;
  } else $error = "Email or password is incorrect.";
}
$pageTitle = "Login | TripNest"; require "includes/header.php";
?>
<section class="auth-wrap"><form class="auth-card" method="post"><span class="eyebrow">WELCOME BACK</span><h1>Log in</h1><p>Pick up where your next adventure begins.</p>
<?php if ($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<label>Email address<input type="email" name="email" required></label><label>Password<input type="password" name="password" required></label>
<button class="btn btn-primary full-width">Log in</button><p class="auth-foot">New to TripNest? <a href="register.php">Create an account</a></p></form></section>
<?php require "includes/footer.php"; ?>
