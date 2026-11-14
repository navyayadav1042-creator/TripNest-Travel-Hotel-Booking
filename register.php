<?php
require_once "config/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? ''); $password = $_POST['password'] ?? '';
  if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) $error = "Enter a name, valid email and password with at least 8 characters.";
  else {
    try {
      $stmt = $pdo->prepare("INSERT INTO users (name,email,password,phone) VALUES (?,?,?,?)");
      $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$phone]);
      $_SESSION['user_id'] = (int)$pdo->lastInsertId(); $_SESSION['user_name'] = $name; $_SESSION['role'] = 'user';
      header("Location: index.php"); exit;
    } catch (PDOException $e) { $error = "That email may already be registered."; }
  }
}
$pageTitle = "Create account | TripNest"; require "includes/header.php";
?>
<section class="auth-wrap"><form class="auth-card" method="post"><span class="eyebrow">JOIN THE JOURNEY</span><h1>Create account</h1><p>Save your plans and manage your bookings.</p>
<?php if ($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<label>Full name<input name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"></label>
<label>Email address<input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></label>
<label>Phone number<input name="phone" inputmode="tel"></label>
<label>Password<input type="password" name="password" minlength="8" required></label>
<button class="btn btn-primary full-width">Create account</button><p class="auth-foot">Already have an account? <a href="login.php">Log in</a></p></form></section>
<?php require "includes/footer.php"; ?>
