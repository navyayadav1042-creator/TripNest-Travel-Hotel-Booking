<?php
require_once "config/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$roomId = (int)($_GET['room_id'] ?? $_POST['room_id'] ?? 0);
$stmt = $pdo->prepare("SELECT r.*, h.hotel_name, h.hotel_id, d.name AS destination FROM rooms r JOIN hotels h ON h.hotel_id=r.hotel_id JOIN destinations d ON d.destination_id=h.destination_id WHERE r.room_id=?");
$stmt->execute([$roomId]); $room = $stmt->fetch();
if (!$room) exit("Room not found. <a href='hotels.php'>Browse hotels</a>");
$error = ''; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $in = $_POST['check_in'] ?? ''; $out = $_POST['check_out'] ?? ''; $guests = max(1,(int)($_POST['guests'] ?? 1)); $qty = max(1,(int)($_POST['number_of_rooms'] ?? 1));
  $nights = (strtotime($out) - strtotime($in)) / 86400;
  if (!$in || !$out || $nights < 1 || $in < date('Y-m-d')) $error = "Choose a valid future check-in and a check-out at least one day later.";
  elseif ($qty > (int)$room['total_rooms']) $error = "The selected number of rooms is not available.";
  else {
    $total = $nights * $qty * (float)$room['price'];
    $insert = $pdo->prepare("INSERT INTO bookings (user_id,room_id,check_in,check_out,guests,number_of_rooms,total_amount,status) VALUES (?,?,?,?,?,?,?,'pending')");
    $insert->execute([$_SESSION['user_id'],$roomId,$in,$out,$guests,$qty,$total]);
    $bookingId = (int)$pdo->lastInsertId();
    $pay = $pdo->prepare("INSERT INTO payments (booking_id,amount,payment_method,payment_status) VALUES (?,?,'Pay at property','pending')");
    $pay->execute([$bookingId,$total]);
    header("Location: my-bookings.php?created=1"); exit;
  }
}
$pageTitle = "Book your stay | TripNest"; require "includes/header.php";
?>
<section class="auth-wrap"><form class="auth-card booking-card" method="post"><span class="eyebrow">YOUR STAY</span><h1><?= htmlspecialchars($room['hotel_name']) ?></h1><p><?= htmlspecialchars($room['destination']) ?> · <?= htmlspecialchars($room['room_type']) ?></p>
<?php if ($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<input type="hidden" name="room_id" value="<?= $roomId ?>">
<label>Check-in<input type="date" name="check_in" id="checkin" min="<?= date('Y-m-d') ?>" required></label>
<label>Check-out<input type="date" name="check_out" id="checkout" min="<?= date('Y-m-d',strtotime('+1 day')) ?>" required></label>
<label>Guests<select name="guests"><?php for($i=1;$i<=8;$i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?></select></label>
<label>Number of rooms<select name="number_of_rooms"><?php for($i=1;$i<=(int)$room['total_rooms'];$i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?></select></label>
<div class="price-note">Room rate: <strong>₹<?= number_format((float)$room['price']) ?> / night</strong><small>Final total is calculated when you submit. Payment is recorded as pending in this demo.</small></div>
<button class="btn btn-primary full-width">Confirm booking request</button></form></section>
<?php require "includes/footer.php"; ?>
