<?php
require_once "config/db.php";
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT h.*, d.name AS destination_name FROM hotels h JOIN destinations d ON h.destination_id=d.destination_id WHERE h.hotel_id=?");
$stmt->execute([$id]); $hotel = $stmt->fetch();
if (!$hotel) { http_response_code(404); exit("Hotel not found. <a href='hotels.php'>Browse hotels</a>"); }
$roomsStmt = $pdo->prepare("SELECT * FROM rooms WHERE hotel_id=? ORDER BY price"); $roomsStmt->execute([$id]); $rooms = $roomsStmt->fetchAll();
$pageTitle = $hotel['hotel_name']." | TripNest";
require "includes/header.php";
?>
<section class="section hotel-detail">
  <a class="text-link" href="hotels.php">← Back to hotels</a>
  <img class="detail-image" src="<?= htmlspecialchars($hotel['image'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1400') ?>" alt="Hotel">
  <div class="detail-heading"><div><span class="eyebrow"><?= htmlspecialchars($hotel['destination_name']) ?></span><h1><?= htmlspecialchars($hotel['hotel_name']) ?></h1><p><?= htmlspecialchars($hotel['address'] ?? '') ?></p></div><span class="rating">★ <?= htmlspecialchars($hotel['rating']) ?> / 5</span></div>
  <p class="detail-description"><?= nl2br(htmlspecialchars($hotel['description'] ?? 'A comfortable stay for your next trip.')) ?></p>
  <h2>Choose your room</h2>
  <div class="room-list">
  <?php foreach ($rooms as $room): ?><div class="room-row"><div><h3><?= htmlspecialchars($room['room_type']) ?></h3><p><?= (int)$room['total_rooms'] ?> room(s) listed</p></div><div class="room-price"><strong>₹<?= number_format((float)$room['price']) ?></strong><small>per night</small></div><a class="btn btn-primary" href="booking.php?room_id=<?= (int)$room['room_id'] ?>">Book now</a></div><?php endforeach; ?>
  <?php if (!$rooms): ?><div class="empty-state">No rooms listed yet for this hotel.</div><?php endif; ?>
  </div>
</section>
<?php require "includes/footer.php"; ?>
