<?php
$data = json_decode(file_get_contents(__DIR__ . '/content/fr/contact.json'), true) ?: [];
$heroImg = $data['hero_image'] ?? 'images/lg-P03-facade-clematis';
$footer = json_decode(file_get_contents(__DIR__ . '/content/fr/footer.json'), true) ?: [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact — La Grimouillière</title>
  <meta name="description" content="Contactez-nous pour réserver votre séjour à La Grimouillière, location saisonnière au cœur du Pays d'Auge.">
  <link rel="alternate" hreflang="fr" href="contact.html">
  <link rel="alternate" hreflang="en" href="en/contact.html">
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
        <a href="tarifs.html">Tarifs</a>
        <a href="contact.html" class="active">Contact</a>
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
  <img src="<?= htmlspecialchars($heroImg) ?>.jpg" alt="Façade de La Grimouillière" loading="lazy">
</picture></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="section-label">CONTACT</p>
      <h1><?= htmlspecialchars($data['hero_heading'] ?? 'Nous contacter') ?></h1>
    </div>
  </section>

  <section class="section">
    <div class="text-center" style="max-width:700px;margin:0 auto 2rem;">
      <?= $data['intro_text'] ?? '' ?>
    </div>
    <div class="contact-grid" style="max-width:900px;margin:0 auto;">
      <div class="contact-form-col">
        <p style="font-size:0.85rem;color:var(--taupe-light);margin-bottom:1.5rem;"><?= htmlspecialchars($data['form_hint'] ?? '') ?></p>
        <form action="https://formsubmit.co/contact@lagrimouilliere.fr" method="POST" id="contact-form">
          <input type="hidden" name="_subject" value="Nouvelle demande — La Grimouillière">
          <input type="hidden" name="_next" value="https://lagrimouilliere.fr/merci.html">
          <input type="hidden" name="_captcha" value="false">
          <input type="text" name="_honey" style="display:none">
          <div class="form-row">
            <div class="form-group">
              <label for="name">Nom</label>
              <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="phone">Téléphone</label>
              <input type="tel" id="phone" name="phone">
            </div>
            <div class="form-group">
              <label for="guests">Nombre de voyageurs</label>
              <select id="guests" name="guests">
                <option value="2">2 personnes</option>
                <option value="3">3 personnes</option>
                <option value="4">4 personnes</option>
                <option value="5">5 personnes</option>
                <option value="6">6 personnes</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="checkin">Arrivée</label>
              <input type="date" id="checkin" name="checkin">
            </div>
            <div class="form-group">
              <label for="checkout">Départ</label>
              <input type="date" id="checkout" name="checkout">
            </div>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">ENVOYER</button>
        </form>
      </div>
      <div class="contact-info">
        <h3>Informations pratiques</h3>
        <p><?= nl2br(htmlspecialchars($data['address'] ?? '')) ?></p>
        <p><?= htmlspecialchars($data['distance'] ?? '') ?></p>
        <p style="margin-top:0.5rem;"><a href="https://maps.google.com/?q=3+route+des+Autels+Saint-Bazile,+61200+Crouttes,+France" target="_blank" rel="noopener">Voir sur Google Maps</a></p>
        <h3>Horaires</h3>
        <p>Check-in : <?= htmlspecialchars($data['checkin'] ?? '') ?></p>
        <p>Check-out : <?= htmlspecialchars($data['checkout'] ?? '') ?></p>
        <h3>Annulation</h3>
        <p><?= htmlspecialchars($data['cancellation'] ?? '') ?></p>
        <p><?= htmlspecialchars($data['pets'] ?? '') ?></p>
        <h3>Suivez-nous</h3>
        <p><a href="https://www.instagram.com/lagrimflow" target="_blank" rel="noopener"><?= htmlspecialchars($data['instagram'] ?? '') ?></a></p>
      </div>
    </div>
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