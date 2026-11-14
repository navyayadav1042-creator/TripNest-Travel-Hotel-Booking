<?php
require_once "config/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$stmt = $pdo->prepare("SELECT b.*, r.room_type, h.hotel_name, d.name AS destination, p.payment_status FROM bookings b JOIN rooms r ON r.room_id=b.room_id JOIN hotels h ON h.hotel_id=r.hotel_id JOIN destinations d ON d.destination_id=h.destination_id LEFT JOIN payments p ON p.booking_id=b.booking_id WHERE b.user_id=? ORDER BY b.booked_at DESC");
$stmt->execute([$_SESSION['user_id']]); $bookings = $stmt->fetchAll();
$pageTitle = "My bookings | TripNest"; require "includes/header.php";
?>
<section class="section"><div class="section-heading"><div><span class="eyebrow">YOUR TRAVEL JOURNAL</span><h1>My bookings</h1></div></div>
<?php if (isset($_GET['created'])): ?><div class="success">Your booking request has been saved. Payment status is pending.</div><?php endif; ?>
<div class="booking-list"><?php foreach($bookings as $b): ?><article class="booking-item"><div><span class="eyebrow">BOOKING #<?= (int)$b['booking_id'] ?></span><h3><?= htmlspecialchars($b['hotel_name']) ?></h3><p><?= htmlspecialchars($b['destination']) ?> · <?= htmlspecialchars($b['room_type']) ?></p><p><?= htmlspecialchars($b['check_in']) ?> → <?= htmlspecialchars($b['check_out']) ?></p></div><div class="booking-status"><strong>₹<?= number_format((float)$b['total_amount']) ?></strong><span class="status"><?= htmlspecialchars(ucfirst($b['status'])) ?></span><small>Payment: <?= htmlspecialchars($b['payment_status'] ?? 'pending') ?></small></div></article><?php endforeach; ?>
<?php if (!$bookings): ?><div class="empty-state">No bookings yet. <a href="hotels.php">Explore hotels</a> to plan your first stay.</div><?php endif; ?></div></section>
<?php require "includes/footer.php"; ?>
