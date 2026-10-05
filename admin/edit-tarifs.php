<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/tarifs.json');
$en = load_json('en/rates.json');
$saved = isset($_GET['saved']);

$fr_rates = $fr['rates'] ?? [];
$en_rates = $en['rates'] ?? [];
$max_rates = max(count($fr_rates), count($en_rates));

$fr_info = $fr['info'] ?? [];
$en_info = $en['info'] ?? [];
$max_info = max(count($fr_info), count($en_info));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Tarifs / Rates</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
  :root {
    --creme: #F7F2E9;
    --sauge: #92A17F;
    --sauge-dark: #778A65;
    --tuile: #B5622E;
    --taupe: #4A4038;
    --taupe-light: #6B5F55;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Poppins', sans-serif;
    background: var(--creme);
    min-height: 100vh;
    color: var(--taupe);
  }
  .admin-header {
    background: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 100;
  }
  .admin-header h1 { font-family: 'Playfair Display', serif; font-size: 1.2rem; }
  .admin-header a { color: var(--taupe-light); text-decoration: none; font-size: 0.85rem; }
  .admin-header a:hover { color: var(--tuile); }
  .editor-wrap {
    max-width: 1100px;
    margin: 2rem auto;
    padding: 0 1rem 4rem;
  }
  .editor-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }
  .section-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(74,64,56,0.06);
  }
  .section-card h3 {
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--sauge-dark);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.75rem;
  }
  .bilingual-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }
  .lang-col h4 {
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: inline-block;
    padding: 2px 10px;
    border-radius: 3px;
  }
  .lang-col h4.fr { background: #e8f0e4; color: var(--sauge-dark); }
  .lang-col h4.en { background: #fde8d8; color: var(--tuile); }
  .field-group {
    margin-bottom: 0.75rem;
  }
  .field-group label {
    display: block;
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 0.2rem;
    color: var(--taupe-light);
  }
  .field-group input, .field-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    color: var(--taupe);
  }
  .field-group input:focus, .field-group textarea:focus {
    outline: none;
    border-color: var(--sauge);
  }
  .rate-item {
    border-bottom: 1px solid #f0ede6;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    position: relative;
  }
  .rate-item:last-of-type { border-bottom: none; }
  .rate-item .rate-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }
  .rate-item .rate-side {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 0.5rem;
  }
  .info-item {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 1rem;
    align-items: center;
    margin-bottom: 0.5rem;
  }
  .btn-remove {
    background: none;
    border: none;
    color: #ccc;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 8px;
    line-height: 1;
  }
  .btn-remove:hover { color: var(--tuile); }
  .btn-remove-rate {
    position: absolute;
    top: 0;
    right: 0;
  }
  .btn-add {
    background: none;
    border: 1px dashed var(--sauge);
    color: var(--sauge-dark);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 0.8rem;
    margin-top: 0.5rem;
  }
  .btn-add:hover { background: #f0f5ed; }
  .btn-save {
    background: var(--tuile);
    color: white;
    border: none;
    padding: 14px 40px;
    border-radius: 6px;
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    letter-spacing: 1px;
    display: block;
    width: 100%;
    margin-top: 1rem;
  }
  .btn-save:hover { opacity: 0.9; }
  .toast {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: var(--sauge-dark);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 0.85rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: fadeIn 0.3s ease;
  }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  .ql-container { font-family: 'Poppins', sans-serif; font-size: 0.9rem; }
  .ql-editor { min-height: 80px; }
  @media (max-width: 768px) {
    .bilingual-row, .rate-item .rate-fields, .info-item { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>Tarifs / Rates</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">Tarifs / Rates</h2>

  <form method="POST" action="save-tarifs.php" id="tarifs-form">

    <!-- INTRO TEXT -->
    <div class="section-card">
      <h3>Texte d'introduction / Introduction text</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div id="intro-editor-fr"><?= $fr['intro_text'] ?? '' ?></div>
          <input type="hidden" name="fr_intro_text" id="fr-intro-input">
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div id="intro-editor-en"><?= $en['intro_text'] ?? '' ?></div>
          <input type="hidden" name="en_intro_text" id="en-intro-input">
        </div>
      </div>
    </div>

    <!-- RATES -->
    <div class="section-card">
      <h3>Grille tarifaire / Rate cards</h3>
      <div id="rates-container">
        <?php for ($i = 0; $i < $max_rates; $i++): ?>
        <div class="rate-item">
          <button type="button" class="btn-remove btn-remove-rate" onclick="this.closest('.rate-item').remove();">&times;</button>
          <div class="rate-fields">
            <div class="rate-side">
              <?php if ($i === 0): ?><div style="grid-column:1/-1"><h4 class="fr" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#e8f0e4;color:var(--sauge-dark);">FR</h4></div><?php endif; ?>
              <div class="field-group">
                <label>Nom</label>
                <input type="text" name="fr_rates[<?= $i ?>][name]" value="<?= htmlspecialchars($fr_rates[$i]['name'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Prix</label>
                <input type="text" name="fr_rates[<?= $i ?>][price]" value="<?= htmlspecialchars($fr_rates[$i]['price'] ?? '') ?>">
              </div>
              <div class="field-group" style="grid-column:1/-1">
                <label>Détail</label>
                <input type="text" name="fr_rates[<?= $i ?>][detail]" value="<?= htmlspecialchars($fr_rates[$i]['detail'] ?? '') ?>">
              </div>
            </div>
            <div class="rate-side">
              <?php if ($i === 0): ?><div style="grid-column:1/-1"><h4 class="en" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#fde8d8;color:var(--tuile);">EN</h4></div><?php endif; ?>
              <div class="field-group">
                <label>Name</label>
                <input type="text" name="en_rates[<?= $i ?>][name]" value="<?= htmlspecialchars($en_rates[$i]['name'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Price</label>
                <input type="text" name="en_rates[<?= $i ?>][price]" value="<?= htmlspecialchars($en_rates[$i]['price'] ?? '') ?>">
              </div>
              <div class="field-group" style="grid-column:1/-1">
                <label>Detail</label>
                <input type="text" name="en_rates[<?= $i ?>][detail]" value="<?= htmlspecialchars($en_rates[$i]['detail'] ?? '') ?>">
              </div>
            </div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addRate()">+ Ajouter un tarif / Add a rate</button>
    </div>

    <!-- INFO -->
    <div class="section-card">
      <h3>Bon à savoir / Good to know</h3>
      <div class="info-header" style="display:grid;grid-template-columns:1fr 1fr auto;gap:1rem;margin-bottom:0.5rem;">
        <h4 class="fr" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#e8f0e4;color:var(--sauge-dark);width:fit-content;">FR</h4>
        <h4 class="en" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#fde8d8;color:var(--tuile);width:fit-content;">EN</h4>
        <div></div>
      </div>
      <div id="info-container">
        <?php for ($i = 0; $i < $max_info; $i++): ?>
        <div class="info-item">
          <input type="text" name="fr_info[]" value="<?= htmlspecialchars($fr_info[$i] ?? '') ?>">
          <input type="text" name="en_info[]" value="<?= htmlspecialchars($en_info[$i] ?? '') ?>">
          <button type="button" class="btn-remove" onclick="this.closest('.info-item').remove();">&times;</button>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addInfo()">+ Ajouter / Add</button>
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
const toolbarOpts = [
  ['bold', 'italic'],
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  ['clean']
];

const quillFr = new Quill('#intro-editor-fr', { theme: 'snow', modules: { toolbar: toolbarOpts } });
const quillEn = new Quill('#intro-editor-en', { theme: 'snow', modules: { toolbar: toolbarOpts } });

document.getElementById('tarifs-form').addEventListener('submit', function() {
  document.getElementById('fr-intro-input').value = quillFr.root.innerHTML;
  document.getElementById('en-intro-input').value = quillEn.root.innerHTML;
});

let rateIndex = <?= $max_rates ?>;

function addRate() {
  const container = document.getElementById('rates-container');
  const item = document.createElement('div');
  item.className = 'rate-item';
  item.innerHTML = `
    <button type="button" class="btn-remove btn-remove-rate" onclick="this.closest('.rate-item').remove();">&times;</button>
    <div class="rate-fields">
      <div class="rate-side">
        <div class="field-group"><label>Nom</label><input type="text" name="fr_rates[${rateIndex}][name]"></div>
        <div class="field-group"><label>Prix</label><input type="text" name="fr_rates[${rateIndex}][price]"></div>
        <div class="field-group" style="grid-column:1/-1"><label>Détail</label><input type="text" name="fr_rates[${rateIndex}][detail]"></div>
      </div>
      <div class="rate-side">
        <div class="field-group"><label>Name</label><input type="text" name="en_rates[${rateIndex}][name]"></div>
        <div class="field-group"><label>Price</label><input type="text" name="en_rates[${rateIndex}][price]"></div>
        <div class="field-group" style="grid-column:1/-1"><label>Detail</label><input type="text" name="en_rates[${rateIndex}][detail]"></div>
      </div>
    </div>
  `;
  container.appendChild(item);
  rateIndex++;
}

function addInfo() {
  const container = document.getElementById('info-container');
  const item = document.createElement('div');
  item.className = 'info-item';
  item.innerHTML = `
    <input type="text" name="fr_info[]" value="">
    <input type="text" name="en_info[]" value="">
    <button type="button" class="btn-remove" onclick="this.closest('.info-item').remove();">&times;</button>
  `;
  container.appendChild(item);
}
</script>
</body>
</html>