<?php
$tarifs = json_decode(file_get_contents(__DIR__ . '/content/fr/tarifs.json'), true) ?: [];
$avail = json_decode(file_get_contents(__DIR__ . '/content/availability.json'), true) ?: [];
$statusLabels = ['available' => 'Disponible', 'booked' => 'Complet', 'on_request' => 'Sur demande'];
$heroImg = $tarifs['hero_image'] ?? 'images/lg-P13-facade-jour-banc';
$heroHeading = $tarifs['hero_heading'] ?? 'Tarifs & disponibilités';
$footer = json_decode(file_get_contents(__DIR__ . '/content/fr/footer.json'), true) ?: [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tarifs — La Grimouillière</title>
  <meta name="description" content="Tarifs de location de La Grimouillière : 250€ la nuit, 1500€ la semaine. Linge, Wi-Fi et accès piscine inclus.">
  <link rel="alternate" hreflang="fr" href="tarifs.html">
  <link rel="alternate" hreflang="en" href="en/rates.html">
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
        <a href="chambres.html">Chambres</a>
        <a href="autour.html">Autour</a>
        <a href="tarifs.html" class="active">Tarifs</a>
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
    <div class="hero-bg"><picture>
  <source type="image/webp"
    srcset="<?= htmlspecialchars($heroImg) ?>-sm.webp 400w, <?= htmlspecialchars($heroImg) ?>-md.webp 800w, <?= htmlspecialchars($heroImg) ?>-lg.webp 1600w"
    sizes="100vw">
  <img src="<?= htmlspecialchars($heroImg) ?>.jpg" alt="La Grimouillière vue du jardin" loading="lazy">
</picture></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="section-label">TARIFS</p>
      <h1><?= htmlspecialchars($heroHeading) ?></h1>
    </div>
  </section>

  <section class="section">
    <div class="text-center" style="max-width:700px;margin:0 auto 2rem;">
      <?= $tarifs['intro_text'] ?? '' ?>
    </div>
    <div class="rates-grid">
      <?php foreach (($tarifs['rates'] ?? []) as $rate): ?>
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
      <p class="section-label">BON À SAVOIR</p>
      <h2>Avant de réserver</h2>
    </div>
    <ul class="included-list" style="max-width:600px;margin:2rem auto 0;">
      <?php foreach (($tarifs['info'] ?? []) as $item): ?>
      <li><?= htmlspecialchars($item) ?></li>
      <?php endforeach; ?>
    </ul>
  </section>

  <section class="section">
    <p class="section-label">DISPONIBILITÉS</p>
    <h2>Calendrier</h2>
    <table class="availability-table">
      <thead>
        <tr>
          <th>Période</th>
          <th>Statut</th>
          <th>Tarif</th>
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
    <p>Le calendrier ci-dessous donne un aperçu des disponibilités. Pour confirmer des dates précises, envoyez-nous une demande avec vos dates souhaitées et le nombre de voyageurs.</p>
  </section>

  <?php $ctaImg = $tarifs['cta_image'] ?? 'images/lg-P06-maison-nuit'; ?>
  <section class="cta-band">
    <div class="hero-bg"><picture>
  <source type="image/webp"
    srcset="<?= htmlspecialchars($ctaImg) ?>-sm.webp 400w, <?= htmlspecialchars($ctaImg) ?>-md.webp 800w, <?= htmlspecialchars($ctaImg) ?>-lg.webp 1600w"
    sizes="100vw">
  <img src="<?= htmlspecialchars($ctaImg) ?>.jpg" alt="La Grimouillière" loading="lazy">
</picture></div>
    <h2>Réservez votre séjour</h2>
    <a href="contact.html" class="btn btn-primary">NOUS CONTACTER</a>
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
