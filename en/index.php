<?php
$data = json_decode(file_get_contents(__DIR__ . '/../content/en/homepage.json'), true) ?: [];
$hero = $data['hero'] ?? [];
$intro = $data['intro'] ?? [];
$glance = $data['glance'] ?? [];
$why = $data['why'] ?? [];
$cta = $data['cta'] ?? [];
$heroImg = '../' . ($hero['image'] ?? 'images/lg-P06-maison-nuit');
$introImg = '../' . ($intro['image'] ?? 'images/lg-P03-facade-clematis');
$footer = json_decode(file_get_contents(__DIR__ . '/../content/en/footer.json'), true) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Grimouillière — Holiday Rental in Pays d'Auge, Normandy</title>
    <meta name="description" content="La Grimouillière, a charming holiday rental in Pays d'Auge, Normandy. Three bedrooms, six guests, curated decor and timeless atmosphere in the heart of the Norman countryside near Crouttes.">
    <link rel="alternate" hreflang="fr" href="../">
    <link rel="alternate" hreflang="en" href="./">
    <meta property="og:title" content="La Grimouillière — Holiday Rental in Pays d'Auge, Normandy">
    <meta property="og:description" content="Charming holiday rental in Pays d'Auge, Normandy. Three bedrooms, six guests, a suspended parenthesis in the heart of the Norman countryside.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                <a href="the-house.html">The house</a>
                <a href="bedrooms.html">Bedrooms</a>
                <a href="nearby.html">Nearby</a>
                <a href="rates.html">Rates</a>
                <a href="contact.html">Contact</a>
                <a href="faq.html">FAQ</a>
            </nav>
            <div class="header-actions">
                <div class="lang-switch">
                    <a href="../">FR</a>
                    <a href="./" class="active">EN</a>
                </div>
                <a href="contact.html" class="btn btn-primary btn-header">Book</a>
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
                <img src="<?= htmlspecialchars($heroImg) ?>.jpg" alt="La Grimouillière at night, illuminated half-timbered house">
            </picture>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="hero-surtitre"><?= htmlspecialchars($hero['label'] ?? 'WELCOME') ?></p>
            <h1><?= htmlspecialchars($hero['heading'] ?? 'La Grimouillière') ?></h1>
            <p class="hero-location"><?= htmlspecialchars($hero['subheading'] ?? '') ?></p>
            <p class="hero-tagline"><?= nl2br(htmlspecialchars($hero['tagline'] ?? '')) ?></p>
            <a href="contact.html" class="btn btn-primary"><?= htmlspecialchars($hero['cta_text'] ?? 'BOOK NOW') ?></a>
        </div>
    </section>

    <!-- THE HOUSE -->
    <section class="section">
        <div class="section-label">WELCOME</div>
        <div class="intro-grid">
            <div class="intro-image">
                <picture>
                    <source type="image/webp"
                        srcset="<?= htmlspecialchars($introImg) ?>-sm.webp 400w, <?= htmlspecialchars($introImg) ?>-md.webp 800w, <?= htmlspecialchars($introImg) ?>-lg.webp 1600w"
                        sizes="(max-width: 768px) 100vw, 50vw">
                    <img src="<?= htmlspecialchars($introImg) ?>.jpg" alt="La Grimouillière facade with clematis" loading="lazy">
                </picture>
            </div>
            <div class="intro-text">
                <blockquote>
                    <?= $intro['text'] ?? '' ?>
                </blockquote>
                <a href="the-house.html" class="btn btn-outline"><?= htmlspecialchars($intro['cta_text'] ?? 'DISCOVER THE HOUSE') ?></a>
            </div>
        </div>
    </section>

    <!-- AT A GLANCE -->
    <section class="section">
        <div class="text-center">
            <p class="section-label">AT A GLANCE</p>
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

    <!-- WHY THE LITTLE HOUSE -->
    <section class="section-sage">
      <div class="section-inner">
        <div class="text-center">
          <p class="section-label">WHY THE LITTLE HOUSE</p>
        </div>
        <ul class="included-list" style="max-width:600px;margin:2rem auto 0;">
          <?php foreach ($why as $reason): ?>
          <li><?= htmlspecialchars($reason) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>


    <!-- CTA BAND -->
    <?php $ctaImg = '../' . ($cta['image'] ?? 'images/lg-P13-facade-jour-banc'); ?>
    <section class="cta-band">
        <div class="hero-bg">
            <picture>
                <source type="image/webp"
                    srcset="<?= htmlspecialchars($ctaImg) ?>-sm.webp 400w, <?= htmlspecialchars($ctaImg) ?>-md.webp 800w, <?= htmlspecialchars($ctaImg) ?>-lg.webp 1600w"
                    sizes="100vw">
                <img src="<?= htmlspecialchars($ctaImg) ?>.jpg" alt="La Grimouillière from the garden" loading="lazy">
            </picture>
        </div>
        <div class="hero-overlay"></div>
        <div class="cta-band-content">
            <h2><?= htmlspecialchars($cta['heading'] ?? 'Book the little house') ?></h2>
            <p><?= htmlspecialchars($cta['text'] ?? '') ?></p>
            <a href="contact.html" class="btn btn-primary">CONTACT US</a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-col">
                <h4><?= htmlspecialchars($footer['title'] ?? 'LA GRIMOUILLIÈRE') ?></h4>
                <p><?= htmlspecialchars($footer['description'] ?? '') ?></p>
                <p style="margin-top:0.5rem"><?= nl2br(htmlspecialchars($footer['address'] ?? '')) ?></p>
                <p style="margin-top:0.5rem"><?= htmlspecialchars($footer['capacity'] ?? '') ?></p>
                <p><a href="<?= htmlspecialchars($footer['instagram_url'] ?? '') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($footer['instagram'] ?? '') ?></a></p>
            </div>
            <div class="footer-col">
                <h4>NAVIGATION</h4>
                <ul class="footer-nav">
                    <li><a href="the-house.html">The house</a></li>
                    <li><a href="bedrooms.html">Bedrooms</a></li>
                    <li><a href="nearby.html">Nearby</a></li>
                    <li><a href="rates.html">Rates</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?= htmlspecialchars($footer['info_title'] ?? 'INFORMATION') ?></h4>
                <p><?= htmlspecialchars($footer['checkin'] ?? '') ?></p>
                <p><?= htmlspecialchars($footer['checkout'] ?? '') ?></p>
                <p><a href="contact.html"><?= htmlspecialchars($footer['cancellation_text'] ?? '') ?></a></p>
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