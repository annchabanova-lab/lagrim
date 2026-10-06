<?php
$data = json_decode(file_get_contents(__DIR__ . '/../content/en/nearby.json'), true) ?: [];
$heroImg = $data['hero_image'] ?? 'images/lg-extra-vaches-normandie';
$heroHeading = $data['hero_heading'] ?? 'Surroundings';
$footer = json_decode(file_get_contents(__DIR__ . '/../content/en/footer.json'), true) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nearby — La Grimouillière</title>
  <meta name="description" content="Explore the Pays d'Auge surroundings: rolling hills, half-timbered manors, local producers and Norman artisans.">
  <link rel="alternate" hreflang="fr" href="../autour.html">
  <link rel="alternate" hreflang="en" href="nearby.html">
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
        <a href="nearby.html" class="active">Nearby</a>
        <a href="rates.html">Rates</a>
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
            srcset="../<?= htmlspecialchars($heroImg) ?>-sm.webp 400w, ../<?= htmlspecialchars($heroImg) ?>-md.webp 800w, ../<?= htmlspecialchars($heroImg) ?>-lg.webp 1600w"
            sizes="100vw">
          <img src="../<?= htmlspecialchars($heroImg) ?>.jpg" alt="Surroundings" loading="lazy">
        </picture>
      </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="section-label">NEARBY</p>
      <h1><?= htmlspecialchars($heroHeading) ?></h1>
    </div>
  </section>

  <section class="section">
    <div class="text-center" style="max-width:700px;margin:0 auto 2rem;">
      <?= $data['intro_text'] ?? '' ?>
    </div>
    <div class="nearby-two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;max-width:900px;margin:0 auto;">
      <div>
        <p class="section-label">THINGS TO SEE AND DO</p>
        <ul class="included-list" style="margin-top:1.5rem;">
          <?php foreach (($data['see_do'] ?? []) as $item): ?>
          <li><?= htmlspecialchars($item) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <p class="section-label" style="margin-bottom:1.5rem;">A FEW IDEAS AROUND THE HOUSE</p>
        <ul class="included-list">
          <?php foreach (($data['ideas'] ?? []) as $item): ?>
          <li><?= htmlspecialchars($item) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:20px;">
    <div class="text-center">
      <p class="section-label">ACCESS</p>
      <h2>How to get here</h2>
    </div>
    <div class="contact-info" style="max-width:500px;margin:2rem auto 0;text-align:center;">
      <p><strong>Address</strong></p>
      <p><?= nl2br(htmlspecialchars($data['access_address'] ?? '')) ?></p>
      <p style="margin-top:1rem;"><strong>From Paris</strong></p>
      <p><?= htmlspecialchars($data['access_directions'] ?? '') ?></p>
      <p style="margin-top:1rem;"><a href="https://maps.google.com/?q=3+route+des+Autels+Saint-Bazile,+61200+Crouttes,+France" target="_blank" rel="noopener">View on Google Maps</a></p>
    </div>
  </section>

  <?php $ctaImg = '../' . ($data['cta_image'] ?? 'images/lg-P05-arche-jardin'); ?>
  <section class="cta-band">
    <div class="hero-bg">
      <picture>
        <source type="image/webp"
          srcset="<?= htmlspecialchars($ctaImg) ?>-sm.webp 400w, <?= htmlspecialchars($ctaImg) ?>-md.webp 800w, <?= htmlspecialchars($ctaImg) ?>-lg.webp 1600w"
          sizes="100vw">
        <img src="<?= htmlspecialchars($ctaImg) ?>.jpg" alt="La Grimouillière" loading="lazy">
      </picture>
    </div>
    <h2>Ready to book?</h2>
    <a href="contact.html" class="btn btn-primary">CONTACT US</a>
  </section>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-col">
        <h4><?= htmlspecialchars($footer['title'] ?? 'LA GRIMOUILLIÈRE') ?></h4>
        <p><?= htmlspecialchars($footer['description'] ?? '') ?></p>
        <p style="margin-top:0.5rem"><?= nl2br(htmlspecialchars($footer['address'] ?? '')) ?></p>
        <p style="margin-top:0.5rem"><?= htmlspecialchars($footer['capacity'] ?? '') ?></p>
        <p style="margin-top:1rem"><a href="<?= htmlspecialchars($footer['instagram_url'] ?? '') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($footer['instagram'] ?? '') ?></a></p>
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
        <h4><?= htmlspecialchars($footer['info_title'] ?? 'INFORMATION') ?></h4>
        <p><?= htmlspecialchars($footer['checkin'] ?? '') ?></p>
        <p><?= htmlspecialchars($footer['checkout'] ?? '') ?></p>
        <p style="margin-top:1rem"><a href="contact.html"><?= htmlspecialchars($footer['cancellation_text'] ?? '') ?></a></p>
      </div>
    </div>
    <div class="footer-bottom">
      <span><?= htmlspecialchars($footer['copyright'] ?? '') ?></span>
      <a href="legal.html"><?= htmlspecialchars($footer['legal_text'] ?? 'Legal notice') ?></a>
    </div>
  </footer>

  <script src="../js/main.js"></script>
  <script src="../js/lang-switcher.js"></script>
  <script src="../js/lightbox.js"></script>
</body>
</html>