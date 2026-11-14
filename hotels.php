<?php
require_once "config/db.php";
$pageTitle = "Explore hotels | TripNest";
$q = trim($_GET['q'] ?? '');
$sql = "SELECT h.*, d.name AS destination_name, MIN(r.price) AS starting_price
        FROM hotels h JOIN destinations d ON h.destination_id=d.destination_id
        LEFT JOIN rooms r ON r.hotel_id=h.hotel_id";
$params = [];
if ($q !== '') {
  $sql .= " WHERE h.hotel_name LIKE ? OR d.name LIKE ? OR d.country LIKE ?";
  $like = "%$q%"; $params = [$like,$like,$like];
}
$sql .= " GROUP BY h.hotel_id ORDER BY h.rating DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $hotels = $stmt->fetchAll();
require "includes/header.php";
?>
<section class="page-hero compact"><span class="eyebrow">FIND YOUR PERFECT STAY</span><h1>Places worth <em>staying for.</em></h1><p>Browse hotels and discover a room for your next adventure.</p></section>
<section class="section">
  <form class="filter-bar" method="get"><input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search by hotel, city or country"><button class="btn btn-primary">Search</button></form>
  <div class="hotel-grid">
  <?php foreach ($hotels as $h): ?>
    <article class="hotel-card">
      <img src="<?= htmlspecialchars($h['image'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1000') ?>" alt="<?= htmlspecialchars($h['hotel_name']) ?>">
      <div class="hotel-info"><div class="hotel-meta"><span><?= htmlspecialchars($h['destination_name']) ?></span><span>★ <?= htmlspecialchars($h['rating']) ?></span></div>
      <h3><?= htmlspecialchars($h['hotel_name']) ?></h3><p><?= htmlspecialchars($h['address'] ?? '') ?></p>
      <div class="hotel-bottom"><strong><?= $h['starting_price'] !== null ? '₹'.number_format((float)$h['starting_price']) : 'Price on request' ?><small> / night</small></strong><a class="btn btn-outline" href="hotel.php?id=<?= (int)$h['hotel_id'] ?>">View rooms</a></div></div>
    </article>
  <?php endforeach; ?>
  <?php if (!$hotels): ?><div class="empty-state">No hotels found. Try another search or add sample data using database.sql.</div><?php endif; ?>
  </div>
</section>
<?php require "includes/footer.php"; ?>
