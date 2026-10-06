<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/la-maison.json');
$en = load_json('en/the-house.json');
$saved = isset($_GET['saved']);

$fr_summary = $fr['summary'] ?? [];
$en_summary = $en['summary'] ?? [];
$max_summary = max(count($fr_summary), count($en_summary));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — La maison / The house</title>
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
  body { font-family: 'Poppins', sans-serif; background: var(--creme); min-height: 100vh; color: var(--taupe); }
  .admin-header { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 4px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
  .admin-header h1 { font-family: 'Playfair Display', serif; font-size: 1.2rem; }
  .admin-header a { color: var(--taupe-light); text-decoration: none; font-size: 0.85rem; }
  .admin-header a:hover { color: var(--tuile); }
  .editor-wrap { max-width: 1100px; margin: 2rem auto; padding: 0 1rem 4rem; }
  .editor-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1.5rem; }
  .section-card { background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(74,64,56,0.06); }
  .section-card h3 { font-weight: 600; margin-bottom: 1rem; color: var(--sauge-dark); text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; }
  .bilingual-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
  .lang-col h4 { font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; display: inline-block; padding: 2px 10px; border-radius: 3px; }
  .lang-col h4.fr { background: #e8f0e4; color: var(--sauge-dark); }
  .lang-col h4.en { background: #fde8d8; color: var(--tuile); }
  .field-group { margin-bottom: 0.75rem; }
  .field-group label { display: block; font-size: 0.75rem; font-weight: 500; margin-bottom: 0.2rem; color: var(--taupe-light); }
  .field-group input, .field-group textarea { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; color: var(--taupe); }
  .field-group input:focus, .field-group textarea:focus { outline: none; border-color: var(--sauge); }
  .summary-card { border: 1px solid #f0ede6; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; position: relative; }
  .summary-card .bilingual-row { margin-bottom: 0.5rem; }
  .list-item { display: grid; grid-template-columns: 1fr 1fr auto; gap: 0.75rem; align-items: center; margin-bottom: 0.4rem; }
  .list-item input { padding: 8px 10px; font-size: 0.85rem; }
  .btn-remove { background: none; border: none; color: #ccc; cursor: pointer; font-size: 1.2rem; padding: 8px; line-height: 1; }
  .btn-remove:hover { color: var(--tuile); }
  .btn-add { background: none; border: 1px dashed var(--sauge); color: var(--sauge-dark); padding: 8px 16px; border-radius: 6px; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.8rem; margin-top: 0.5rem; }
  .btn-add:hover { background: #f0f5ed; }
  .btn-save { background: var(--tuile); color: white; border: none; padding: 14px 40px; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 500; cursor: pointer; letter-spacing: 1px; display: block; width: 100%; margin-top: 1rem; }
  .btn-save:hover { opacity: 0.9; }
  .toast { position: fixed; bottom: 2rem; right: 2rem; background: var(--sauge-dark); color: white; padding: 12px 24px; border-radius: 8px; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: fadeIn 0.3s ease; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  .ql-container { font-family: 'Poppins', sans-serif; font-size: 0.9rem; }
  .ql-editor { min-height: 80px; }
  @media (max-width: 768px) { .bilingual-row, .list-item { grid-template-columns: 1fr; } }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>La maison / The house</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">La maison / The house</h2>

  <form method="POST" action="save-maison.php" id="maison-form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

    <!-- HERO -->
    <div class="section-card">
      <h3>Hero</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group">
            <label>Label</label>
            <input type="text" name="fr_hero_label" value="<?= htmlspecialchars($fr['hero_label'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Titre</label>
            <input type="text" name="fr_hero_heading" value="<?= htmlspecialchars($fr['hero_heading'] ?? '') ?>">
          </div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Label</label>
            <input type="text" name="en_hero_label" value="<?= htmlspecialchars($en['hero_label'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Heading</label>
            <input type="text" name="en_hero_heading" value="<?= htmlspecialchars($en['hero_heading'] ?? '') ?>">
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
          <div class="field-group">
            <label>Titre</label>
            <input type="text" name="fr_intro_heading" value="<?= htmlspecialchars($fr['intro_heading'] ?? '') ?>">
          </div>
          <div id="intro-editor-fr"><?= $fr['intro_text'] ?? '' ?></div>
          <input type="hidden" name="fr_intro_text" id="fr-intro-input">
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Heading</label>
            <input type="text" name="en_intro_heading" value="<?= htmlspecialchars($en['intro_heading'] ?? '') ?>">
          </div>
          <div id="intro-editor-en"><?= $en['intro_text'] ?? '' ?></div>
          <input type="hidden" name="en_intro_text" id="en-intro-input">
        </div>
      </div>
      <div id="intro-image-upload" style="margin-top:1rem"></div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="section-card">
      <h3>En bref / In brief</h3>
      <div id="summary-container">
        <?php for ($i = 0; $i < $max_summary; $i++):
          $fr_card = $fr_summary[$i] ?? ['title' => '', 'items' => []];
          $en_card = $en_summary[$i] ?? ['title' => '', 'items' => []];
          $max_items = max(count($fr_card['items']), count($en_card['items']));
        ?>
        <div class="summary-card" data-index="<?= $i ?>">
          <button type="button" class="btn-remove" style="position:absolute;top:0.5rem;right:0.5rem;" onclick="this.closest('.summary-card').remove();">&times;</button>
          <div class="bilingual-row" style="margin-bottom:0.75rem;">
            <div class="field-group">
              <label>Titre FR</label>
              <input type="text" name="fr_summary[<?= $i ?>][title]" value="<?= htmlspecialchars($fr_card['title']) ?>">
            </div>
            <div class="field-group">
              <label>Title EN</label>
              <input type="text" name="en_summary[<?= $i ?>][title]" value="<?= htmlspecialchars($en_card['title']) ?>">
            </div>
          </div>
          <div class="items-container" data-card="<?= $i ?>">
            <?php for ($j = 0; $j < $max_items; $j++): ?>
            <div class="list-item">
              <input type="text" name="fr_summary[<?= $i ?>][items][]" value="<?= htmlspecialchars($fr_card['items'][$j] ?? '') ?>">
              <input type="text" name="en_summary[<?= $i ?>][items][]" value="<?= htmlspecialchars($en_card['items'][$j] ?? '') ?>">
              <button type="button" class="btn-remove" onclick="this.closest('.list-item').remove();">&times;</button>
            </div>
            <?php endfor; ?>
          </div>
          <button type="button" class="btn-add" onclick="addSummaryItem(this, <?= $i ?>)">+ Ajouter / Add</button>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addSummaryCard()" style="margin-top:1rem;">+ Ajouter une catégorie / Add a category</button>
    </div>

    <!-- COMFORT -->
    <div class="section-card">
      <h3>Confort / Comfort</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group">
            <label>Titre</label>
            <input type="text" name="fr_comfort_heading" value="<?= htmlspecialchars($fr['comfort_heading'] ?? '') ?>">
          </div>
          <div id="comfort-editor-fr"><?= $fr['comfort_text'] ?? '' ?></div>
          <input type="hidden" name="fr_comfort_text" id="fr-comfort-input">
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Heading</label>
            <input type="text" name="en_comfort_heading" value="<?= htmlspecialchars($en['comfort_heading'] ?? '') ?>">
          </div>
          <div id="comfort-editor-en"><?= $en['comfort_text'] ?? '' ?></div>
          <input type="hidden" name="en_comfort_text" id="en-comfort-input">
        </div>
      </div>
    </div>

    <div class="section-card">
      <h3>Image bandeau bas / CTA band image</h3>
      <div id="cta-image-upload"></div>
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

const quillIntroFr = new Quill('#intro-editor-fr', { theme: 'snow', modules: { toolbar: toolbarOpts } });
const quillIntroEn = new Quill('#intro-editor-en', { theme: 'snow', modules: { toolbar: toolbarOpts } });
const quillComfortFr = new Quill('#comfort-editor-fr', { theme: 'snow', modules: { toolbar: toolbarOpts } });
const quillComfortEn = new Quill('#comfort-editor-en', { theme: 'snow', modules: { toolbar: toolbarOpts } });

document.getElementById('maison-form').addEventListener('submit', function() {
  document.getElementById('fr-intro-input').value = quillIntroFr.root.innerHTML;
  document.getElementById('en-intro-input').value = quillIntroEn.root.innerHTML;
  document.getElementById('fr-comfort-input').value = quillComfortFr.root.innerHTML;
  document.getElementById('en-comfort-input').value = quillComfortEn.root.innerHTML;
});

let summaryIndex = <?= $max_summary ?>;

function addSummaryItem(btn, cardIdx) {
  const container = btn.previousElementSibling;
  const item = document.createElement('div');
  item.className = 'list-item';
  item.innerHTML = `
    <input type="text" name="fr_summary[${cardIdx}][items][]" value="">
    <input type="text" name="en_summary[${cardIdx}][items][]" value="">
    <button type="button" class="btn-remove" onclick="this.closest('.list-item').remove();">&times;</button>
  `;
  container.appendChild(item);
}

function addSummaryCard() {
  const container = document.getElementById('summary-container');
  const card = document.createElement('div');
  card.className = 'summary-card';
  card.innerHTML = `
    <button type="button" class="btn-remove" style="position:absolute;top:0.5rem;right:0.5rem;" onclick="this.closest('.summary-card').remove();">&times;</button>
    <div class="bilingual-row" style="margin-bottom:0.75rem;">
      <div class="field-group"><label>Titre FR</label><input type="text" name="fr_summary[${summaryIndex}][title]" value=""></div>
      <div class="field-group"><label>Title EN</label><input type="text" name="en_summary[${summaryIndex}][title]" value=""></div>
    </div>
    <div class="items-container" data-card="${summaryIndex}"></div>
    <button type="button" class="btn-add" onclick="addSummaryItem(this, ${summaryIndex})">+ Ajouter / Add</button>
  `;
  container.appendChild(card);
  summaryIndex++;
}
</script>
<script src="image-upload.js"></script>
<script>
createImageUpload(document.getElementById('hero-image-upload'), {
  name: 'hero_image',
  value: '<?= htmlspecialchars($fr['hero_image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image hero / Hero image'
});
createImageUpload(document.getElementById('intro-image-upload'), {
  name: 'intro_image',
  value: '<?= htmlspecialchars($fr['intro_image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image introduction / Intro image'
});
createImageUpload(document.getElementById('cta-image-upload'), {
  name: 'cta_image',
  value: '<?= htmlspecialchars($fr['cta_image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image bandeau bas / CTA band image'
});
</script>
</body>
</html>