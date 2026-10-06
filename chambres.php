<?php
$data = json_decode(file_get_contents(__DIR__ . '/content/fr/chambres.json'), true) ?: [];
$rooms = $data['rooms'] ?? [];
$heroImg = $data['hero_image'] ?? 'images/lg-extra-chambre-large';
$heroHeading = $data['hero_heading'] ?? 'Nos chambres';
$footer = json_decode(file_get_contents(__DIR__ . '/content/fr/footer.json'), true) ?: [];
$ctaImg = $data['cta_image'] ?? 'images/lg-P13-facade-jour-banc';
function img_base($path) {
    $path = ltrim($path, '/');
    $path = preg_replace('/-(sm|md|lg)\.(webp|jpg|jpeg|png)$/i', '', $path);
    $path = preg_replace('/\.(webp|jpg|jpeg|png)$/i', '', $path);
    return $path;
}
function img_srcset_room($path, $sizes = '(max-width: 768px) 100vw, 50vw') {
    $base = img_base($path);
    return '<picture>
            <source type="image/webp"
              srcset="' . htmlspecialchars($base) . '-sm.webp 400w, ' . htmlspecialchars($base) . '-md.webp 800w, ' . htmlspecialchars($base) . '-lg.webp 1600w"
              sizes="' . $sizes . '">
            <img src="' . htmlspecialchars($base) . '.jpg" alt="" loading="lazy" width="800" height="600">
          </picture>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chambres — La Grimouillière</title>
  <meta name="description" content="Trois chambres pour six voyageurs : chambre principale sous les combles, boudoir fleuri et chambre d'enfants.">
  <link rel="alternate" hreflang="fr" href="chambres.html">
  <link rel="alternate" hreflang="en" href="en/bedrooms.html">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Cormorant+SC:wght@400;500&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/responsive.css">
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
        <a href="la-maison.html">La maison</a>
        <a href="chambres.html" class="active">Chambres</a>
        <a href="autour.html">Autour</a>
        <a href="tarifs.html">Tarifs</a>
        <a href="contact.html">Contact</a>
        <a href="faq.html">FAQ</a>
      </nav>
      <div class="header-actions">
        <div class="lang-switch"><a href="./" data-lang="fr" class="active">FR</a> / <a href="en/" data-lang="en">EN</a></div>
        <a href="contact.html" class="btn btn-primary btn-header">Réserver</a>
      </div>
    </div>
  </header>

  <section class="page-hero">
    <div class="hero-bg">
        <?= img_srcset_room($heroImg, '100vw') ?>
      </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="section-label">CHAMBRES & COUCHAGES</p>
      <h1><?= htmlspecialchars($heroHeading) ?></h1>
    </div>
  </section>

  <section class="section">
    <div class="text-center" style="max-width:700px;margin:0 auto;">
      <?= $data['intro_text'] ?? '' ?>
    </div>
  </section>

  <section class="section">
    <?php foreach ($rooms as $i => $room):
      $reverse = ($i % 2 === 1) ? ' reverse' : '';
      $img = $room['image'] ?? '';
    ?>
    <div class="room-section<?= $reverse ?>">
      <div class="room-images">
        <div class="room-image">
          <?= img_srcset_room($img) ?>
        </div>
      </div>
      <div class="room-info">
        <p class="section-label"><?= htmlspecialchars($room['label'] ?? '') ?></p>
        <h3><?= htmlspecialchars($room['heading'] ?? '') ?></h3>
        <p><?= htmlspecialchars($room['description'] ?? '') ?></p>
        <?php if (!empty($room['detail'])): ?>
        <p style="margin-top:1rem;font-size:0.8rem;color:var(--taupe-light);"><?= htmlspecialchars($room['detail']) ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="text-center" style="max-width:700px;margin:0 auto;">
      <p class="section-label">CONFIGURATION IDÉALE</p>
      <h2><?= htmlspecialchars($data['ideal_heading'] ?? '') ?></h2>
      <p style="margin-top:1rem;font-size:0.9rem;color:var(--taupe-light);line-height:1.8;"><?= htmlspecialchars($data['ideal_text'] ?? '') ?></p>
    </div>
  </section>

  <?php $ctaImg = $data['cta_image'] ?? 'images/lg-P13-facade-jour-banc'; ?>
  <section class="cta-band">
    <div class="hero-bg">
      <picture>
        <source type="image/webp"
          srcset="<?= htmlspecialchars($ctaImg) ?>-sm.webp 400w, <?= htmlspecialchars($ctaImg) ?>-md.webp 800w, <?= htmlspecialchars($ctaImg) ?>-lg.webp 1600w"
          sizes="100vw">
        <img src="<?= htmlspecialchars($ctaImg) ?>.jpg" alt="La Grimouillière" loading="lazy">
      </picture>
    </div>
    <h2>Voir les tarifs</h2>
    <a href="tarifs.html" class="btn btn-primary">VOIR LES TARIFS</a>
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
          <li><a href="la-maison.html">La maison</a></li>
          <li><a href="chambres.html">Chambres</a></li>
          <li><a href="autour.html">Autour</a></li>
          <li><a href="tarifs.html">Tarifs</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="faq.html">FAQ</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4><?= htmlspecialchars($footer['info_title'] ?? 'INFORMATIONS') ?></h4>
        <p><?= htmlspecialchars($footer['checkin'] ?? '') ?></p>
        <p><?= htmlspecialchars($footer['checkout'] ?? '') ?></p>
        <p style="margin-top:1rem"><a href="contact.html"><?= htmlspecialchars($footer['cancellation_text'] ?? '') ?></a></p>
      </div>
    </div>
    <div class="footer-bottom">
      <span><?= htmlspecialchars($footer['copyright'] ?? '') ?></span>
      <a href="mentions-legales.html"><?= htmlspecialchars($footer['legal_text'] ?? 'Mentions légales') ?></a>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script src="js/lang-switcher.js"></script>
  <script src="js/lightbox.js"></script>
</body>
</html>