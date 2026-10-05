<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/homepage.json');
$en = load_json('en/homepage.json');
$saved = isset($_GET['saved']);

$fr_glance = $fr['glance'] ?? [];
$en_glance = $en['glance'] ?? [];
$max_glance = max(count($fr_glance), count($en_glance), 1);

$fr_why = $fr['why'] ?? [];
$en_why = $en['why'] ?? [];
$max_why = max(count($fr_why), count($en_why), 1);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Accueil / Homepage</title>
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
  .field-group textarea { resize: vertical; min-height: 60px; }
  .glance-card {
    border: 1px solid #f0ede6;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    position: relative;
  }
  .glance-card .bilingual-row { margin-bottom: 0.75rem; }
  .item-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 0.75rem;
    align-items: center;
    margin-bottom: 0.4rem;
  }
  .why-item {
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
  .btn-remove-card { position: absolute; top: 0.5rem; right: 0.5rem; }
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
  .help-text { font-size: 0.8rem; color: var(--taupe-light); margin-bottom: 0.75rem; }
  @media (max-width: 768px) {
    .bilingual-row, .item-row, .why-item { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>Accueil / Homepage</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">Accueil / Homepage</h2>

  <form method="POST" action="save-homepage.php" id="homepage-form">

    <!-- HERO -->
    <div class="section-card">
      <h3>Hero</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group">
            <label>Label</label>
            <input type="text" name="fr_hero_label" value="<?= htmlspecialchars($fr['hero']['label'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Titre</label>
            <input type="text" name="fr_hero_heading" value="<?= htmlspecialchars($fr['hero']['heading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Sous-titre</label>
            <input type="text" name="fr_hero_subheading" value="<?= htmlspecialchars($fr['hero']['subheading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Accroche</label>
            <textarea name="fr_hero_tagline" rows="3"><?= htmlspecialchars($fr['hero']['tagline'] ?? '') ?></textarea>
          </div>
          <div class="field-group">
            <label>Bouton</label>
            <input type="text" name="fr_hero_cta" value="<?= htmlspecialchars($fr['hero']['cta_text'] ?? '') ?>">
          </div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Label</label>
            <input type="text" name="en_hero_label" value="<?= htmlspecialchars($en['hero']['label'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Heading</label>
            <input type="text" name="en_hero_heading" value="<?= htmlspecialchars($en['hero']['heading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Subheading</label>
            <input type="text" name="en_hero_subheading" value="<?= htmlspecialchars($en['hero']['subheading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Tagline</label>
            <textarea name="en_hero_tagline" rows="3"><?= htmlspecialchars($en['hero']['tagline'] ?? '') ?></textarea>
          </div>
          <div class="field-group">
            <label>Button</label>
            <input type="text" name="en_hero_cta" value="<?= htmlspecialchars($en['hero']['cta_text'] ?? '') ?>">
          </div>
        </div>
      </div>
      <div id="hero-image-upload" style="margin-top:1rem"></div>
    </div>

    <!-- INTRO -->
    <div class="section-card">
      <h3>Introduction</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div id="intro-editor-fr"><?= $fr['intro']['text'] ?? '' ?></div>
          <input type="hidden" name="fr_intro_text" id="fr-intro-input">
          <div class="field-group" style="margin-top:0.75rem">
            <label>Bouton</label>
            <input type="text" name="fr_intro_cta" value="<?= htmlspecialchars($fr['intro']['cta_text'] ?? '') ?>">
          </div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div id="intro-editor-en"><?= $en['intro']['text'] ?? '' ?></div>
          <input type="hidden" name="en_intro_text" id="en-intro-input">
          <div class="field-group" style="margin-top:0.75rem">
            <label>Button</label>
            <input type="text" name="en_intro_cta" value="<?= htmlspecialchars($en['intro']['cta_text'] ?? '') ?>">
          </div>
        </div>
      </div>
      <div id="intro-image-upload" style="margin-top:1rem"></div>
    </div>

    <!-- AT A GLANCE -->
    <div class="section-card">
      <h3>En un coup d'œil / At a glance</h3>
      <div id="glance-container">
        <?php for ($g = 0; $g < $max_glance; $g++): ?>
        <div class="glance-card">
          <button type="button" class="btn-remove btn-remove-card" onclick="this.closest('.glance-card').remove();">&times;</button>
          <div class="bilingual-row">
            <div class="field-group">
              <label>Titre FR</label>
              <input type="text" name="fr_glance[<?= $g ?>][title]" value="<?= htmlspecialchars($fr_glance[$g]['title'] ?? '') ?>">
            </div>
            <div class="field-group">
              <label>Title EN</label>
              <input type="text" name="en_glance[<?= $g ?>][title]" value="<?= htmlspecialchars($en_glance[$g]['title'] ?? '') ?>">
            </div>
          </div>
          <div class="glance-items" data-index="<?= $g ?>">
            <?php
            $fr_items = $fr_glance[$g]['items'] ?? [];
            $en_items = $en_glance[$g]['items'] ?? [];
            $max_items = max(count($fr_items), count($en_items), 1);
            for ($it = 0; $it < $max_items; $it++): ?>
            <div class="item-row">
              <input type="text" name="fr_glance[<?= $g ?>][items][]" value="<?= htmlspecialchars($fr_items[$it] ?? '') ?>">
              <input type="text" name="en_glance[<?= $g ?>][items][]" value="<?= htmlspecialchars($en_items[$it] ?? '') ?>">
              <button type="button" class="btn-remove" onclick="this.closest('.item-row').remove();">&times;</button>
            </div>
            <?php endfor; ?>
          </div>
          <button type="button" class="btn-add" onclick="addGlanceItem(this)">+ Ajouter / Add item</button>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addGlanceCard()" style="margin-top:0.5rem">+ Ajouter une carte / Add a card</button>
    </div>

    <!-- WHY -->
    <div class="section-card">
      <h3>Pourquoi la petite maison / Why the little house</h3>
      <div class="why-header" style="display:grid;grid-template-columns:1fr 1fr auto;gap:1rem;margin-bottom:0.5rem;">
        <h4 class="fr" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#e8f0e4;color:var(--sauge-dark);width:fit-content;">FR</h4>
        <h4 class="en" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#fde8d8;color:var(--tuile);width:fit-content;">EN</h4>
        <div></div>
      </div>
      <div id="why-container">
        <?php for ($w = 0; $w < $max_why; $w++): ?>
        <div class="why-item">
          <input type="text" name="fr_why[]" value="<?= htmlspecialchars($fr_why[$w] ?? '') ?>">
          <input type="text" name="en_why[]" value="<?= htmlspecialchars($en_why[$w] ?? '') ?>">
          <button type="button" class="btn-remove" onclick="this.closest('.why-item').remove();">&times;</button>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addWhy()">+ Ajouter / Add</button>
    </div>

    <!-- CTA BAND -->
    <div class="section-card">
      <h3>Bandeau d'appel / CTA Band</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group">
            <label>Titre</label>
            <input type="text" name="fr_cta_heading" value="<?= htmlspecialchars($fr['cta']['heading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Texte</label>
            <input type="text" name="fr_cta_text" value="<?= htmlspecialchars($fr['cta']['text'] ?? '') ?>">
          </div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Heading</label>
            <input type="text" name="en_cta_heading" value="<?= htmlspecialchars($en['cta']['heading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Text</label>
            <input type="text" name="en_cta_text" value="<?= htmlspecialchars($en['cta']['text'] ?? '') ?>">
          </div>
        </div>
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
const toolbarOpts = [['bold', 'italic'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['clean']];
const quillFr = new Quill('#intro-editor-fr', { theme: 'snow', modules: { toolbar: toolbarOpts } });
const quillEn = new Quill('#intro-editor-en', { theme: 'snow', modules: { toolbar: toolbarOpts } });

document.getElementById('homepage-form').addEventListener('submit', function() {
  document.getElementById('fr-intro-input').value = quillFr.root.innerHTML;
  document.getElementById('en-intro-input').value = quillEn.root.innerHTML;
});

let glanceIndex = <?= $max_glance ?>;

function addGlanceItem(btn) {
  const container = btn.previousElementSibling;
  const idx = container.dataset.index;
  const row = document.createElement('div');
  row.className = 'item-row';
  row.innerHTML = `
    <input type="text" name="fr_glance[${idx}][items][]" value="">
    <input type="text" name="en_glance[${idx}][items][]" value="">
    <button type="button" class="btn-remove" onclick="this.closest('.item-row').remove();">&times;</button>
  `;
  container.appendChild(row);
}

function addGlanceCard() {
  const container = document.getElementById('glance-container');
  const card = document.createElement('div');
  card.className = 'glance-card';
  card.innerHTML = `
    <button type="button" class="btn-remove btn-remove-card" onclick="this.closest('.glance-card').remove();">&times;</button>
    <div class="bilingual-row">
      <div class="field-group"><label>Titre FR</label><input type="text" name="fr_glance[${glanceIndex}][title]" value=""></div>
      <div class="field-group"><label>Title EN</label><input type="text" name="en_glance[${glanceIndex}][title]" value=""></div>
    </div>
    <div class="glance-items" data-index="${glanceIndex}">
      <div class="item-row">
        <input type="text" name="fr_glance[${glanceIndex}][items][]" value="">
        <input type="text" name="en_glance[${glanceIndex}][items][]" value="">
        <button type="button" class="btn-remove" onclick="this.closest('.item-row').remove();">&times;</button>
      </div>
    </div>
    <button type="button" class="btn-add" onclick="addGlanceItem(this)">+ Ajouter / Add item</button>
  `;
  container.appendChild(card);
  glanceIndex++;
}

function addWhy() {
  const container = document.getElementById('why-container');
  const row = document.createElement('div');
  row.className = 'why-item';
  row.innerHTML = `
    <input type="text" name="fr_why[]" value="">
    <input type="text" name="en_why[]" value="">
    <button type="button" class="btn-remove" onclick="this.closest('.why-item').remove();">&times;</button>
  `;
  container.appendChild(row);
}
</script>
<script src="image-upload.js"></script>
<script>
createImageUpload(document.getElementById('hero-image-upload'), {
  name: 'hero_image',
  value: '<?= htmlspecialchars($fr['hero']['image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image hero / Hero image'
});
createImageUpload(document.getElementById('intro-image-upload'), {
  name: 'intro_image',
  value: '<?= htmlspecialchars($fr['intro']['image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image introduction / Intro image'
});
</script>
</body>
</html>