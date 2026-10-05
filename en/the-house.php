<?php
$data = json_decode(file_get_contents(__DIR__ . '/../content/en/the-house.json'), true) ?: [];
function img_srcset($lgPath, $prefix = '../') {
    $sm = str_replace('-lg.', '-sm.', $lgPath);
    $md = str_replace('-lg.', '-md.', $lgPath);
    $jpg = preg_replace('/\.webp$/', '.jpg', $lgPath);
    return '<picture>
          <source type="image/webp"
            srcset="' . $prefix . $sm . ' 400w, ' . $prefix . $md . ' 800w, ' . $prefix . $lgPath . ' 1600w"
            sizes="100vw">
          <img src="' . $prefix . $jpg . '" alt="" loading="lazy">
        </picture>';
}
function img_srcset_half($lgPath, $prefix = '../') {
    $sm = str_replace('-lg.', '-sm.', $lgPath);
    $md = str_replace('-lg.', '-md.', $lgPath);
    $jpg = preg_replace('/\.webp$/', '.jpg', $lgPath);
    return '<picture>
          <source type="image/webp"
            srcset="' . $prefix . $sm . ' 400w, ' . $prefix . $md . ' 800w, ' . $prefix . $lgPath . ' 1600w"
            sizes="(max-width: 768px) 100vw, 50vw">
          <img src="' . $prefix . $jpg . '" alt="" loading="lazy">
        </picture>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The house — La Grimouillière</title>
  <meta name="description" content="Discover the little house of La Grimouillière, a world of its own in the heart of Pays d'Auge, Normandy.">
  <link rel="alternate" hreflang="fr" href="../la-maison.html">
  <link rel="alternate" hreflang="en" href="the-house.html">
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
        <a href="the-house.html" class="active">The house</a>
        <a href="bedrooms.html">Bedrooms</a>
        <a href="nearby.html">Nearby</a>
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
        <?= img_srcset($data['hero_image'] ?? '/images/lg-P04-facade-hortensias-lg.webp') ?>
      </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="section-label"><?= htmlspecialchars($data['hero_label'] ?? 'THE HOUSE') ?></p>
      <h1><?= htmlspecialchars($data['hero_heading'] ?? 'The little house') ?></h1>
    </div>
  </section>

  <section class="section">
    <div class="intro-grid">
      <div class="intro-image">
        <?= img_srcset_half($data['intro_image'] ?? '/images/lg-extra-facade-guirlandes-lg.webp') ?>
      </div>
      <div class="intro-text">
        <h2><?= htmlspecialchars($data['intro_heading'] ?? '') ?></h2>
        <?= $data['intro_text'] ?? '' ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="text-center">
      <p class="section-label">THE HOUSE IN BRIEF</p>
    </div>
    <div class="nearby-grid" style="margin-top:2rem">
      <?php foreach (($data['summary'] ?? []) as $card): ?>
      <div class="nearby-item">
        <h3><?= htmlspecialchars($card['title'] ?? '') ?></h3>
        <ul class="included-list">
          <?php foreach (($card['items'] ?? []) as $item): ?>
          <li><?= htmlspecialchars($item) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section">
    <div class="text-center">
      <p class="section-label">COMFORT</p>
      <h2><?= htmlspecialchars($data['comfort_heading'] ?? '') ?></h2>
    </div>
    <div style="font-size:0.9rem;color:var(--taupe-light);line-height:1.8;">
      <?= $data['comfort_text'] ?? '' ?>
    </div>
  </section>

  <?php $ctaImg = '../' . ($data['cta_image'] ?? 'images/lg-P09-chambre-poutres-voilages'); ?>
  <section class="cta-band">
    <div class="hero-bg">
      <picture>
        <source type="image/webp"
          srcset="<?= htmlspecialchars($ctaImg) ?>-sm.webp 400w, <?= htmlspecialchars($ctaImg) ?>-md.webp 800w, <?= htmlspecialchars($ctaImg) ?>-lg.webp 1600w"
          sizes="100vw">
        <img src="<?= htmlspecialchars($ctaImg) ?>.jpg" alt="La Grimouillière" loading="lazy">
      </picture>
    </div>
    <a href="bedrooms.html" class="btn btn-primary">VIEW BEDROOMS</a>
  </section>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-col">
        <h4>La Grimouillière</h4>
        <p>Seasonal rental — the little house</p>
        <p style="margin-top:0.5rem">3 route des Autels Saint-Bazile<br>61200 Crouttes<br>Pays d'Auge, Normandy</p>
        <p style="margin-top:0.5rem">Up to 6 guests · 2h15 from Paris-Porte Maillot</p>
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