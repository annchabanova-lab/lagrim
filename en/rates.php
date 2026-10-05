<?php
$rates = json_decode(file_get_contents(__DIR__ . '/../content/en/rates.json'), true) ?: [];
$avail = json_decode(file_get_contents(__DIR__ . '/../content/availability.json'), true) ?: [];
$statusLabels = ['available' => 'Available', 'booked' => 'Fully booked', 'on_request' => 'On request'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rates — La Grimouillière</title>
  <meta name="description" content="Rental rates for La Grimouillière: €250 per night, €1,500 per week. Linen, Wi-Fi and pool access included.">
  <link rel="alternate" hreflang="fr" href="../tarifs.html">
  <link rel="alternate" hreflang="en" href="rates.html">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Cormorant+SC:wght@400;500&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/responsive.css">
<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  _paq.push(["disableCookies"]);
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);
  (function() {
    var u="https://crysalead-coaching.com/analytics/";
    _paq.push(["setTrackerUrl", u+"matomo.php"]);
    _paq.push(["setSiteId", "3"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
    g.async=true; g.src=u+"matomo.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo -->
</head>
<body>

  <header class="site-header">
    <div class="header-inner">
      <a href="./" class="site-logo">La Grimouillière<span>Pays d'Auge · Normandie</span></a>
      <button class="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
      <nav class="main-nav">
        <a href="the-house.html">The house</a>
        <a href="bedrooms.html">Bedrooms</a>
        <a href="nearby.html">Nearby</a>
        <a href="rates.html" class="active">Rates</a>
        <a href="contact.html">Contact</a>
        <a href="faq.html">FAQ</a>
      </nav>
      <div class="header-actions">
        <div class="lang-switch"><a href="../" data-lang="fr">FR</a> / <a href="./" data-lang="en" class="active">EN</a></div>
        <a href="contact.html" class="btn btn-primary btn-header">Book</a>
      </div>
    </div>
  </header>

  <section class="page-hero">
    <div class="hero-bg">
        <picture>
          <source type="image/webp"
            srcset="../images/lg-P13-facade-jour-banc-sm.webp 400w, ../images/lg-P13-facade-jour-banc-md.webp 800w, ../images/lg-P13-facade-jour-banc-lg.webp 1600w"
            sizes="100vw">
          <img src="../images/lg-P13-facade-jour-banc.jpg" alt="La Grimouillière from the garden" loading="lazy">
        </picture>
      </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="section-label">RATES</p>
      <h1>Rates &amp; availability</h1>
    </div>
  </section>

  <section class="section">
    <div class="text-center" style="max-width:700px;margin:0 auto 2rem;">
      <?= $rates['intro_text'] ?? '' ?>
    </div>
    <div class="rates-grid">
      <?php foreach (($rates['rates'] ?? []) as $rate): ?>
      <div class="rate-card">
        <h3><?= htmlspecialchars($rate['name']) ?></h3>
        <p class="rate-price"><?= htmlspecialchars($rate['price']) ?></p>
        <p class="rate-detail"><?= htmlspecialchars($rate['detail']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section">
    <div class="text-center">
      <p class="section-label">GOOD TO KNOW</p>
      <h2>Before you book</h2>
    </div>
    <ul class="included-list" style="max-width:600px;margin:2rem auto 0;">
      <?php foreach (($rates['info'] ?? []) as $item): ?>
      <li><?= htmlspecialchars($item) ?></li>
      <?php endforeach; ?>
    </ul>
  </section>

  <section class="section">
    <p class="section-label">AVAILABILITY</p>
    <h2>Calendar</h2>
    <table class="availability-table">
      <thead>
        <tr>
          <th>Period</th>
          <th>Status</th>
          <th>Rate</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($avail['periods'] ?? []) as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td class="status-<?= htmlspecialchars($p['status']) ?>"><?= htmlspecialchars($statusLabels[$p['status']] ?? $p['status']) ?></td>
          <td><?= !empty($p['rate']) ? htmlspecialchars($p['rate']) : '—' ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p>The calendar below gives an overview of availability. To confirm specific dates, please send us a request with your desired dates and the number of guests.</p>
  </section>

  <section class="cta-band">
    <div class="hero-bg">
      <picture>
        <source type="image/webp"
          srcset="../images/lg-P06-maison-nuit-sm.webp 400w, ../images/lg-P06-maison-nuit-md.webp 800w, ../images/lg-P06-maison-nuit-lg.webp 1600w"
          sizes="100vw">
        <img src="../images/lg-P06-maison-nuit.jpg" alt="La Grimouillière at night" loading="lazy">
      </picture>
    </div>
    <h2>Book your stay</h2>
    <a href="contact.html" class="btn btn-primary">CONTACT US</a>
  </section>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-col">
        <h4>La Grimouillière</h4>
        <p>Seasonal rental — the little house</p>
        <p style="margin-top:0.5rem">3 route des Autels Saint-Bazile<br>61200 Crouttes<br>Pays d'Auge, Normandy</p>
        <p style="margin-top:0.5rem">Up to 6 guests · 2h15 from Paris-Porte Maillot</p>
        <p style="margin-top:1rem"><a href="https://www.instagram.com/lagrimflow" target="_blank" rel="noopener">@lagrimflow</a></p>
      </div>
      <div class="footer-col">
        <h4>Navigation</h4>
        <ul class="footer-nav">
          <li><a href="the-house.html">The house</a></li>
          <li><a href="bedrooms.html">Bedrooms</a></li>
          <li><a href="nearby.html">Nearby</a></li>
          <li><a href="rates.html">Rates</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="faq.html">FAQ</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Information</h4>
        <p>Check-in: 4 pm</p>
        <p>Check-out: 11 am</p>
        <p style="margin-top:1rem"><a href="contact.html">Cancellation policy</a></p>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; 2026 SCI Five Stars — La Grimouillière</span>
      <a href="legal.html">Legal notice</a>
    </div>
  </footer>

  <script src="../js/main.js"></script>
  <script src="../js/lang-switcher.js"></script>
  <script src="../js/lightbox.js"></script>
</body>
</html>
