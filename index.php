<?php
$data = json_decode(file_get_contents(__DIR__ . '/content/fr/homepage.json'), true) ?: [];
$hero = $data['hero'] ?? [];
$intro = $data['intro'] ?? [];
$glance = $data['glance'] ?? [];
$why = $data['why'] ?? [];
$cta = $data['cta'] ?? [];
$heroImg = $hero['image'] ?? 'images/lg-P06-maison-nuit';
$introImg = $intro['image'] ?? 'images/lg-P03-facade-clematis';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Grimouillière — Location saisonnière Pays d'Auge, Normandie</title>
    <meta name="description" content="La Grimouillière, maison de charme à louer dans le Pays d'Auge, Normandie. Location saisonnière avec trois chambres, décor chiné, au cœur de la campagne normande près de Crouttes.">
    <link rel="alternate" hreflang="fr" href="./">
    <link rel="alternate" hreflang="en" href="en/">
    <meta property="og:title" content="La Grimouillière — Location saisonnière Pays d'Auge, Normandie">
    <meta property="og:description" content="Maison de charme à louer dans le Pays d'Auge, Normandie. Trois chambres, six voyageurs, une parenthèse enchantée au cœur de la campagne normande.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

    <!-- HEADER -->
    <header class="site-header">
        <div class="header-inner">
            <a href="./" class="site-logo">
                La Grimouillière
                <span>Pays d'Auge · Normandie</span>
            </a>
            <button class="hamburger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav class="main-nav">
                <a href="la-maison.html">La maison</a>
                <a href="chambres.html">Chambres</a>
                <a href="autour.html">Autour</a>
                <a href="tarifs.html">Tarifs</a>
                <a href="contact.html">Contact</a>
                <a href="faq.html">FAQ</a>
            </nav>
            <div class="header-actions">
                <div class="lang-switch">
                    <a href="./" class="active">FR</a>
                    <a href="en/">EN</a>
                </div>
                <a href="contact.html" class="btn btn-primary btn-header">Réserver</a>
            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg">
            <picture>
                <source type="image/webp"
                    srcset="<?= htmlspecialchars($heroImg) ?>-sm.webp 400w, <?= htmlspecialchars($heroImg) ?>-md.webp 800w, <?= htmlspecialchars($heroImg) ?>-lg.webp 1600w"
                    sizes="100vw">
                <img src="<?= htmlspecialchars($heroImg) ?>.jpg" alt="La Grimouillière de nuit, maison à colombages illuminée" width="1600" height="900">
            </picture>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="hero-surtitre"><?= htmlspecialchars($hero['label'] ?? 'BIENVENUE') ?></p>
            <h1><?= htmlspecialchars($hero['heading'] ?? 'La Grimouillière') ?></h1>
            <p class="hero-location"><?= htmlspecialchars($hero['subheading'] ?? '') ?></p>
            <p class="hero-tagline"><?= nl2br(htmlspecialchars($hero['tagline'] ?? '')) ?></p>
            <a href="contact.html" class="btn btn-primary"><?= htmlspecialchars($hero['cta_text'] ?? 'RÉSERVER') ?></a>
        </div>
    </section>

    <!-- LA MAISON -->
    <section class="section">
        <div class="section-label">BIENVENUE</div>
        <div class="intro-grid">
            <div class="intro-image">
                <picture>
                    <source type="image/webp"
                        srcset="<?= htmlspecialchars($introImg) ?>-sm.webp 400w, <?= htmlspecialchars($introImg) ?>-md.webp 800w, <?= htmlspecialchars($introImg) ?>-lg.webp 1600w"
                        sizes="(max-width: 768px) 100vw, 50vw">
                    <img src="<?= htmlspecialchars($introImg) ?>.jpg" alt="Façade de La Grimouillière avec clématite" loading="lazy" width="800" height="1000">
                </picture>
            </div>
            <div class="intro-text">
                <blockquote>
                    <?= $intro['text'] ?? '' ?>
                </blockquote>
                <a href="la-maison.html" class="btn btn-outline"><?= htmlspecialchars($intro['cta_text'] ?? 'DÉCOUVRIR LA MAISON') ?></a>
            </div>
        </div>
    </section>

    <!-- EN UN COUP D'ŒIL -->
    <section class="section">
      <div class="text-center">
        <p class="section-label">EN UN COUP D'ŒIL</p>
      </div>
      <div class="nearby-grid" style="margin-top:2rem">
        <?php foreach ($glance as $card): ?>
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

    <!-- POURQUOI LA PETITE MAISON -->
    <section class="section-sage">
      <div class="section-inner">
        <div class="text-center">
          <p class="section-label">POURQUOI LA PETITE MAISON</p>
        </div>
        <ul class="included-list" style="max-width:600px;margin:2rem auto 0;">
          <?php foreach ($why as $reason): ?>
          <li><?= htmlspecialchars($reason) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>


    <!-- CTA BAND -->
    <?php $ctaImg = $cta['image'] ?? 'images/lg-P13-facade-jour-banc'; ?>
    <section class="cta-band">
        <div class="hero-bg">
            <picture>
                <source type="image/webp"
                    srcset="<?= htmlspecialchars($ctaImg) ?>-sm.webp 400w, <?= htmlspecialchars($ctaImg) ?>-md.webp 800w, <?= htmlspecialchars($ctaImg) ?>-lg.webp 1600w"
                    sizes="100vw">
                <img src="<?= htmlspecialchars($ctaImg) ?>.jpg" alt="La Grimouillière vue du jardin" loading="lazy" width="1600" height="900">
            </picture>
        </div>
        <div class="hero-overlay"></div>
        <div class="cta-band-content">
            <h2><?= htmlspecialchars($cta['heading'] ?? 'Réservez la petite maison') ?></h2>
            <p><?= htmlspecialchars($cta['text'] ?? '') ?></p>
            <a href="contact.html" class="btn btn-primary">NOUS CONTACTER</a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-col">
                <h4>LA GRIMOUILLIÈRE</h4>
                <p>Location saisonnière — la petite maison</p>
                <p style="margin-top:0.5rem">3 route des Autels Saint-Bazile<br>61200 Crouttes<br>Pays d'Auge, Normandie</p>
                <p style="margin-top:0.5rem">Jusqu'à 6 voyageurs · 2h15 de Paris-Porte Maillot</p>
                <p><a href="https://www.instagram.com/lagrimflow" target="_blank" rel="noopener">@lagrimflow</a></p>
            </div>
            <div class="footer-col">
                <h4>NAVIGATION</h4>
                <ul class="footer-nav">
                    <li><a href="la-maison.html">La maison</a></li>
                    <li><a href="chambres.html">Chambres</a></li>
                    <li><a href="autour.html">Autour</a></li>
                    <li><a href="tarifs.html">Tarifs</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>INFORMATIONS</h4>
                <p>Check-in : 16h</p>
                <p>Check-out : 11h</p>
                <p><a href="contact.html">Conditions d'annulation</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; 2026 SCI Five Stars — La Grimouillière</span>
            <a href="mentions-legales.html">Mentions légales</a>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/lang-switcher.js"></script>
    <script src="js/lightbox.js"></script>
</body>
</html>