<?php
require_once 'config.php';
require_login();

$lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
$file = $lang === 'fr' ? 'fr/tarifs.json' : 'en/rates.json';
$data = load_json($file);

$saved = isset($_GET['saved']);

$labels = [
    'fr' => [
        'title' => 'Tarifs',
        'intro' => 'Texte d\'introduction',
        'rates' => 'Grille tarifaire',
        'rate_name' => 'Nom',
        'rate_price' => 'Prix',
        'rate_detail' => 'Détail',
        'add_rate' => '+ Ajouter un tarif',
        'info_title' => 'Bon à savoir',
        'info_add' => '+ Ajouter une information',
        'save' => 'ENREGISTRER',
        'back' => '← Retour',
        'preview' => 'Aperçu',
    ],
    'en' => [
        'title' => 'Rates',
        'intro' => 'Introduction text',
        'rates' => 'Rate cards',
        'rate_name' => 'Name',
        'rate_price' => 'Price',
        'rate_detail' => 'Detail',
        'add_rate' => '+ Add a rate',
        'info_title' => 'Good to know',
        'info_add' => '+ Add an item',
        'save' => 'SAVE',
        'back' => '← Back',
        'preview' => 'Preview',
    ],
];
$l = $labels[$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — <?= $l['title'] ?></title>
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
  .admin-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
  }
  .admin-header a {
    color: var(--taupe-light);
    text-decoration: none;
    font-size: 0.85rem;
  }
  .admin-header a:hover { color: var(--tuile); }
  .editor-wrap {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem 4rem;
  }
  .editor-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    margin-bottom: 0.3rem;
  }
  .lang-badge {
    display: inline-block;
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 3px;
    font-weight: 500;
    letter-spacing: 0.5px;
    margin-bottom: 1.5rem;
  }
  .badge-fr { background: #e8f0e4; color: var(--sauge-dark); }
  .badge-en { background: #fde8d8; color: var(--tuile); }

  .section-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(74,64,56,0.06);
  }
  .section-card h3 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--sauge-dark);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.75rem;
  }

  .field-group {
    margin-bottom: 1rem;
  }
  .field-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 0.3rem;
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
  .field-group textarea { resize: vertical; min-height: 80px; }

  .rate-row {
    display: grid;
    grid-template-columns: 2fr 1fr 2fr auto;
    gap: 0.75rem;
    align-items: end;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f0ede6;
  }
  .rate-row:last-of-type { border-bottom: none; }

  .info-row {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    margin-bottom: 0.5rem;
  }
  .info-row input { flex: 1; }

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
  .ql-editor { min-height: 100px; }

  .preview-box {
    background: var(--creme);
    border: 1px solid #e0dcd5;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
  }
  .preview-box .rate-card-preview {
    display: inline-block;
    background: white;
    border-radius: 8px;
    padding: 1.5rem 2rem;
    text-align: center;
    margin: 0.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }
  .preview-box .rate-card-preview h4 {
    font-family: 'Playfair Display', serif;
    font-size: 0.9rem;
    margin-bottom: 0.3rem;
  }
  .preview-box .rate-card-preview .price {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    color: var(--tuile);
  }
  .preview-box .rate-card-preview .detail {
    font-size: 0.8rem;
    color: var(--taupe-light);
  }
</style>
</head>
<body>
<div class="admin-header">
  <div>
    <a href="dashboard.php"><?= $l['back'] ?></a>
  </div>
  <h1><?= $l['title'] ?></h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title"><?= $l['title'] ?></h2>
  <span class="lang-badge badge-<?= $lang ?>"><?= strtoupper($lang) ?></span>

  <form method="POST" action="save-tarifs.php" id="tarifs-form">
    <input type="hidden" name="lang" value="<?= $lang ?>">

    <div class="section-card">
      <h3><?= $l['intro'] ?></h3>
      <div id="intro-editor"><?= $data['intro_text'] ?? '' ?></div>
      <input type="hidden" name="intro_text" id="intro-text-input">
    </div>

    <div class="section-card">
      <h3><?= $l['rates'] ?></h3>
      <div id="rates-container">
        <?php
        $rates = $data['rates'] ?? [];
        foreach ($rates as $i => $rate): ?>
        <div class="rate-row">
          <div class="field-group">
            <label><?= $l['rate_name'] ?></label>
            <input type="text" name="rates[<?= $i ?>][name]" value="<?= htmlspecialchars($rate['name'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label><?= $l['rate_price'] ?></label>
            <input type="text" name="rates[<?= $i ?>][price]" value="<?= htmlspecialchars($rate['price'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label><?= $l['rate_detail'] ?></label>
            <input type="text" name="rates[<?= $i ?>][detail]" value="<?= htmlspecialchars($rate['detail'] ?? '') ?>">
          </div>
          <button type="button" class="btn-remove" onclick="this.closest('.rate-row').remove();updatePreview();">×</button>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn-add" onclick="addRate()"><?= $l['add_rate'] ?></button>

      <div class="preview-box" id="rates-preview"></div>
    </div>

    <div class="section-card">
      <h3><?= $l['info_title'] ?></h3>
      <div id="info-container">
        <?php
        $info = $data['info'] ?? [];
        foreach ($info as $i => $item): ?>
        <div class="info-row">
          <input type="text" name="info[]" value="<?= htmlspecialchars($item) ?>">
          <button type="button" class="btn-remove" onclick="this.closest('.info-row').remove();">×</button>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn-add" onclick="addInfo()"><?= $l['info_add'] ?></button>
    </div>

    <button type="submit" class="btn-save"><?= $l['save'] ?></button>
  </form>
</div>

<?php if ($saved): ?>
<div class="toast" id="toast">Enregistré !</div>
<script>setTimeout(() => document.getElementById('toast').remove(), 3000);</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
const quill = new Quill('#intro-editor', {
  theme: 'snow',
  modules: {
    toolbar: [
      ['bold', 'italic'],
      [{ 'header': [2, 3, false] }],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['clean']
    ]
  }
});

document.getElementById('tarifs-form').addEventListener('submit', function(e) {
  document.getElementById('intro-text-input').value = quill.root.innerHTML;
});

let rateIndex = <?= count($rates) ?>;

function addRate() {
  const container = document.getElementById('rates-container');
  const row = document.createElement('div');
  row.className = 'rate-row';
  row.innerHTML = `
    <div class="field-group">
      <label><?= $l['rate_name'] ?></label>
      <input type="text" name="rates[${rateIndex}][name]" value="">
    </div>
    <div class="field-group">
      <label><?= $l['rate_price'] ?></label>
      <input type="text" name="rates[${rateIndex}][price]" value="">
    </div>
    <div class="field-group">
      <label><?= $l['rate_detail'] ?></label>
      <input type="text" name="rates[${rateIndex}][detail]" value="">
    </div>
    <button type="button" class="btn-remove" onclick="this.closest('.rate-row').remove();updatePreview();">×</button>
  `;
  container.appendChild(row);
  rateIndex++;
  row.querySelectorAll('input').forEach(inp => inp.addEventListener('input', updatePreview));
}

function addInfo() {
  const container = document.getElementById('info-container');
  const row = document.createElement('div');
  row.className = 'info-row';
  row.innerHTML = `
    <input type="text" name="info[]" value="">
    <button type="button" class="btn-remove" onclick="this.closest('.info-row').remove();">×</button>
  `;
  container.appendChild(row);
}

function updatePreview() {
  const rows = document.querySelectorAll('.rate-row');
  let html = '';
  rows.forEach(row => {
    const inputs = row.querySelectorAll('input');
    const name = inputs[0]?.value || '';
    const price = inputs[1]?.value || '';
    const detail = inputs[2]?.value || '';
    if (name || price) {
      html += `<div class="rate-card-preview"><h4>${name}</h4><div class="price">${price}</div><div class="detail">${detail}</div></div>`;
    }
  });
  document.getElementById('rates-preview').innerHTML = html || '<em style="color:#999;font-size:0.85rem;">Aperçu des tarifs</em>';
}

document.querySelectorAll('.rate-row input').forEach(inp => inp.addEventListener('input', updatePreview));
updatePreview();
</script>
</body>
</html>