<?php
$data = json_decode(file_get_contents(__DIR__ . '/content/fr/autour.json'), true) ?: [];
$heroImg = $data['hero_image'] ?? 'images/lg-extra-vaches-normandie';
$heroHeading = $data['hero_heading'] ?? 'Autour de la maison';
$ctaImg = $data['cta_image'] ?? 'images/lg-P05-arche-jardin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Autour — La Grimouillière</title>
  <meta name="description" content="Découvrez les environs du Pays d'Auge : routes vallonnées, manoirs, producteurs locaux et artisans normands.">
  <link rel="alternate" hreflang="fr" href="autour.html">
  <link rel="alternate" hreflang="en" href="en/nearby.html">
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
        <a href="autour.html" class="active">Autour</a>
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
    <div class="hero-bg"><picture>
  <source type="image/webp"
    srcset="<?= htmlspecialchars($heroImg) ?>-sm.webp 400w, <?= htmlspecialchars($heroImg) ?>-md.webp 800w, <?= htmlspecialchars($heroImg) ?>-lg.webp 1600w"
    sizes="100vw">
  <img src="<?= htmlspecialchars($heroImg) ?>.jpg" alt="Autour de La Grimouillière" loading="lazy">
</picture></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="section-label">AUTOUR</p>
      <h1>Les environs</h1>
    </div>
  </section>

  <section class="section">
    <div class="text-center" style="max-width:700px;margin:0 auto 2rem;">
      <?= $data['intro_text'] ?? '' ?>
    </div>
    <div class="nearby-two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;max-width:900px;margin:0 auto;">
      <div>
        <p class="section-label">À VOIR ET À FAIRE</p>
        <ul class="included-list" style="margin-top:1.5rem;">
          <?php foreach (($data['see_do'] ?? []) as $item): ?>
          <li><?= htmlspecialchars($item) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <p class="section-label" style="margin-bottom:1.5rem;">QUELQUES IDÉES AUTOUR DE LA MAISON</p>
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
      <p class="section-label">ACCÈS</p>
      <h2>Comment venir</h2>
    </div>
    <div class="contact-info" style="max-width:500px;margin:2rem auto 0;text-align:center;">
      <p><strong>Adresse</strong></p>
      <p><?= nl2br(htmlspecialchars($data['access_address'] ?? '')) ?></p>
      <p style="margin-top:1rem;"><strong>Depuis Paris</strong></p>
      <p><?= htmlspecialchars($data['access_directions'] ?? '') ?></p>
      <p style="margin-top:1rem;"><a href="https://maps.google.com/?q=3+route+des+Autels+Saint-Bazile,+61200+Crouttes,+France" target="_blank" rel="noopener">Voir sur Google Maps</a></p>
    </div>
  </section>

  <?php $ctaImg = $data['cta_image'] ?? 'images/lg-P05-arche-jardin'; ?>
  <section class="cta-band">
    <div class="hero-bg"><picture>
  <source type="image/webp"
    srcset="<?= htmlspecialchars($ctaImg) ?>-sm.webp 400w, <?= htmlspecialchars($ctaImg) ?>-md.webp 800w, <?= htmlspecialchars($ctaImg) ?>-lg.webp 1600w"
    sizes="100vw">
  <img src="<?= htmlspecialchars($ctaImg) ?>.jpg" alt="La Grimouillière" loading="lazy">
</picture></div>
    <h2>Envie de réserver ?</h2>
    <a href="contact.html" class="btn btn-primary">NOUS CONTACTER</a>
  </section>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-col">
        <h4>La Grimouillière</h4>
        <p>Location saisonnière — la petite maison</p>
        <p style="margin-top:0.5rem">3 route des Autels Saint-Bazile<br>61200 Crouttes<br>Pays d'Auge, Normandie</p>
        <p style="margin-top:0.5rem">Jusqu'à 6 voyageurs · 2h15 de Paris-Porte Maillot</p>
        <p style="margin-top:1rem"><a href="https://www.instagram.com/lagrimflow" target="_blank" rel="noopener">@lagrimflow</a></p>
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
        <h4>Informations</h4>
        <p>Check-in : 16h</p>
        <p>Check-out : 11h</p>
        <p style="margin-top:1rem"><a href="contact.html">Conditions d'annulation</a></p>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 SCI Five Stars — La Grimouillière</span>
      <a href="mentions-legales.html">Mentions légales</a>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script src="js/lang-switcher.js"></script>
  <script src="js/lightbox.js"></script>
</body>
</html>