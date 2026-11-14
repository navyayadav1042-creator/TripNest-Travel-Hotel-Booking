<?php
require_once "config/db.php";
$pageTitle = "TripNest | Discover your next stay";
$destinations = $pdo->query("SELECT * FROM destinations ORDER BY destination_id DESC LIMIT 6")->fetchAll();
require "includes/header.php";
?>
<section class="hero">
  <div class="hero-content">
    <span class="eyebrow">YOUR NEXT CHAPTER STARTS HERE</span>
    <h1>Travel more.<br><em>Worry less.</em></h1>
    <p>Discover beautiful destinations and stays that make every journey special.</p>
    <form class="search-panel" action="hotels.php" method="get">
      <label>Where to?<input name="q" placeholder="City, destination or hotel"></label>
      <label>Check-in<input type="date" name="check_in" id="hero-checkin"></label>
      <label>Check-out<input type="date" name="check_out" id="hero-checkout"></label>
      <label>Guests<select name="guests"><option>1</option><option selected>2</option><option>3</option><option>4</option><option>5</option><option>6</option></select></label>
      <button class="btn btn-primary" type="submit">Explore stays <span>→</span></button>
    </form>
  </div>
  <div class="hero-note"><span>✦</span> Thoughtful stays for curious travellers</div>
</section>

<section class="section">
  <div class="section-heading"><div><span class="eyebrow">A LITTLE INSPIRATION</span><h2>Popular destinations</h2></div><a class="text-link" href="hotels.php">Explore all stays →</a></div>
  <div class="destination-grid">
    <?php if ($destinations): foreach ($destinations as $d): ?>
      <a class="destination-card" href="hotels.php?q=<?= urlencode($d['name']) ?>" style="--card-image:url('<?= htmlspecialchars($d['image'] ?: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=900') ?>')">
        <div><span><?= htmlspecialchars($d['country']) ?></span><h3><?= htmlspecialchars($d['name']) ?></h3></div>
      </a>
    <?php endforeach; else: ?>
      <div class="empty-state">Your destinations will appear here once sample data is added. Run <strong>database.sql</strong> in phpMyAdmin.</div>
    <?php endif; ?>
  </div>
</section>
<section class="feature-band"><div><span class="eyebrow">THE TRIPNEST DIFFERENCE</span><h2>Good trips begin<br>with a great stay.</h2></div><div class="feature-list"><p>✦ Carefully listed hotels and rooms</p><p>✦ Clear booking details and trip history</p><p>✦ Simple tools for travellers and admins</p></div></section>
<?php require "includes/footer.php"; ?>
