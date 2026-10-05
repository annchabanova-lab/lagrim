<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/contact.json');
$en = load_json('en/contact.json');
$saved = isset($_GET['saved']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Contact</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
  :root { --creme:#F7F2E9; --sauge:#92A17F; --sauge-dark:#778A65; --tuile:#B5622E; --taupe:#4A4038; --taupe-light:#6B5F55; }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Poppins',sans-serif; background:var(--creme); min-height:100vh; color:var(--taupe); }
  .admin-header { background:white; padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 1px 4px rgba(0,0,0,0.05); position:sticky; top:0; z-index:100; }
  .admin-header h1 { font-family:'Playfair Display',serif; font-size:1.2rem; }
  .admin-header a { color:var(--taupe-light); text-decoration:none; font-size:0.85rem; }
  .admin-header a:hover { color:var(--tuile); }
  .editor-wrap { max-width:1100px; margin:2rem auto; padding:0 1rem 4rem; }
  .editor-title { font-family:'Playfair Display',serif; font-size:1.5rem; margin-bottom:1.5rem; }
  .section-card { background:white; border-radius:10px; padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 2px 8px rgba(74,64,56,0.06); }
  .section-card h3 { font-weight:600; margin-bottom:1rem; color:var(--sauge-dark); text-transform:uppercase; letter-spacing:1px; font-size:0.75rem; }
  .bilingual-row { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
  .lang-col h4 { font-size:0.8rem; font-weight:600; margin-bottom:0.5rem; display:inline-block; padding:2px 10px; border-radius:3px; }
  .lang-col h4.fr { background:#e8f0e4; color:var(--sauge-dark); }
  .lang-col h4.en { background:#fde8d8; color:var(--tuile); }
  .field-group { margin-bottom:0.75rem; }
  .field-group label { display:block; font-size:0.75rem; font-weight:500; margin-bottom:0.2rem; color:var(--taupe-light); }
  .field-group input, .field-group textarea { width:100%; padding:10px 14px; border:1px solid #ddd; border-radius:6px; font-family:'Poppins',sans-serif; font-size:0.9rem; color:var(--taupe); }
  .field-group input:focus, .field-group textarea:focus { outline:none; border-color:var(--sauge); }
  .field-group textarea { resize:vertical; min-height:60px; }
  .btn-save { background:var(--tuile); color:white; border:none; padding:14px 40px; border-radius:6px; font-family:'Poppins',sans-serif; font-size:0.9rem; font-weight:500; cursor:pointer; letter-spacing:1px; display:block; width:100%; margin-top:1rem; }
  .btn-save:hover { opacity:0.9; }
  .toast { position:fixed; bottom:2rem; right:2rem; background:var(--sauge-dark); color:white; padding:12px 24px; border-radius:8px; font-size:0.85rem; box-shadow:0 4px 12px rgba(0,0,0,0.15); animation:fadeIn 0.3s ease; }
  @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
  .ql-container { font-family:'Poppins',sans-serif; font-size:0.9rem; }
  .ql-editor { min-height:80px; }
  @media (max-width:768px) { .bilingual-row { grid-template-columns:1fr; } }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>Contact</h1>
  <a href="logout.php">Déconnexion</a>
</div>
<div class="editor-wrap">
  <h2 class="editor-title">Contact</h2>
  <form method="POST" action="save-contact.php" id="contact-form">

    <div class="section-card">
      <h3>Texte d'introduction / Introduction text</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div id="intro-fr"><?= $fr['intro_text'] ?? '' ?></div>
          <input type="hidden" name="fr_intro_text" id="fr-intro-input">
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div id="intro-en"><?= $en['intro_text'] ?? '' ?></div>
          <input type="hidden" name="en_intro_text" id="en-intro-input">
        </div>
      </div>
    </div>

    <div class="section-card">
      <h3>Consigne du formulaire / Form hint</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group"><textarea name="fr_form_hint" rows="2"><?= htmlspecialchars($fr['form_hint'] ?? '') ?></textarea></div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group"><textarea name="en_form_hint" rows="2"><?= htmlspecialchars($en['form_hint'] ?? '') ?></textarea></div>
        </div>
      </div>
    </div>

    <div class="section-card">
      <h3>Informations pratiques / Practical info</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group"><label>Adresse</label><textarea name="fr_address" rows="2"><?= htmlspecialchars($fr['address'] ?? '') ?></textarea></div>
          <div class="field-group"><label>Distance</label><input type="text" name="fr_distance" value="<?= htmlspecialchars($fr['distance'] ?? '') ?>"></div>
          <div class="field-group"><label>Check-in</label><input type="text" name="fr_checkin" value="<?= htmlspecialchars($fr['checkin'] ?? '') ?>"></div>
          <div class="field-group"><label>Check-out</label><input type="text" name="fr_checkout" value="<?= htmlspecialchars($fr['checkout'] ?? '') ?>"></div>
          <div class="field-group"><label>Annulation</label><input type="text" name="fr_cancellation" value="<?= htmlspecialchars($fr['cancellation'] ?? '') ?>"></div>
          <div class="field-group"><label>Animaux</label><input type="text" name="fr_pets" value="<?= htmlspecialchars($fr['pets'] ?? '') ?>"></div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group"><label>Address</label><textarea name="en_address" rows="2"><?= htmlspecialchars($en['address'] ?? '') ?></textarea></div>
          <div class="field-group"><label>Distance</label><input type="text" name="en_distance" value="<?= htmlspecialchars($en['distance'] ?? '') ?>"></div>
          <div class="field-group"><label>Check-in</label><input type="text" name="en_checkin" value="<?= htmlspecialchars($en['checkin'] ?? '') ?>"></div>
          <div class="field-group"><label>Check-out</label><input type="text" name="en_checkout" value="<?= htmlspecialchars($en['checkout'] ?? '') ?>"></div>
          <div class="field-group"><label>Cancellation</label><input type="text" name="en_cancellation" value="<?= htmlspecialchars($en['cancellation'] ?? '') ?>"></div>
          <div class="field-group"><label>Pets</label><input type="text" name="en_pets" value="<?= htmlspecialchars($en['pets'] ?? '') ?>"></div>
        </div>
      </div>
      <div class="field-group" style="margin-top:1rem;">
        <label>Instagram (FR + EN)</label>
        <input type="text" name="instagram" value="<?= htmlspecialchars($fr['instagram'] ?? '') ?>">
      </div>
    </div>

    <button type="submit" class="btn-save">ENREGISTRER / SAVE</button>
  </form>
</div>

<?php if ($saved): ?>
<div class="toast" id="toast">Enregistré / Saved !</div>
<script>setTimeout(() => document.getElementById('toast').remove(), 3000);</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
const opts = { theme:'snow', modules:{ toolbar:[['bold','italic'],[{'list':'ordered'},{'list':'bullet'}],['clean']] } };
const qFr = new Quill('#intro-fr', opts);
const qEn = new Quill('#intro-en', opts);
document.getElementById('contact-form').addEventListener('submit', function() {
  document.getElementById('fr-intro-input').value = qFr.root.innerHTML;
  document.getElementById('en-intro-input').value = qEn.root.innerHTML;
});
</script>
</body>
</html>