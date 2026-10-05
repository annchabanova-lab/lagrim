<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/autour.json');
$en = load_json('en/nearby.json');
$saved = isset($_GET['saved']);

$fr_see = $fr['see_do'] ?? [];
$en_see = $en['see_do'] ?? [];
$max_see = max(count($fr_see), count($en_see));

$fr_ideas = $fr['ideas'] ?? [];
$en_ideas = $en['ideas'] ?? [];
$max_ideas = max(count($fr_ideas), count($en_ideas));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Autour / Nearby</title>
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
  .list-item { display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: center; margin-bottom: 0.5rem; }
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
  .list-header { display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; margin-bottom: 0.5rem; }
  .list-header span { font-size: 0.8rem; font-weight: 600; display: inline-block; padding: 2px 10px; border-radius: 3px; width: fit-content; }
  .list-header .fr { background: #e8f0e4; color: var(--sauge-dark); }
  .list-header .en { background: #fde8d8; color: var(--tuile); }
  @media (max-width: 768px) { .bilingual-row, .list-item, .list-header { grid-template-columns: 1fr; } }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>Autour / Nearby</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">Autour / Nearby</h2>

  <form method="POST" action="save-autour.php" id="autour-form">

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

    <!-- SEE & DO -->
    <div class="section-card">
      <h3>À voir et à faire / Things to see and do</h3>
      <div class="list-header">
        <span class="fr">FR</span>
        <span class="en">EN</span>
        <div></div>
      </div>
      <div id="see-container">
        <?php for ($i = 0; $i < $max_see; $i++): ?>
        <div class="list-item">
          <input type="text" name="fr_see_do[]" value="<?= htmlspecialchars($fr_see[$i] ?? '') ?>">
          <input type="text" name="en_see_do[]" value="<?= htmlspecialchars($en_see[$i] ?? '') ?>">
          <button type="button" class="btn-remove" onclick="this.closest('.list-item').remove();">&times;</button>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addItem('see-container', 'fr_see_do[]', 'en_see_do[]')">+ Ajouter / Add</button>
    </div>

    <!-- IDEAS NEARBY -->
    <div class="section-card">
      <h3>Idées autour / Ideas nearby</h3>
      <div class="list-header">
        <span class="fr">FR</span>
        <span class="en">EN</span>
        <div></div>
      </div>
      <div id="ideas-container">
        <?php for ($i = 0; $i < $max_ideas; $i++): ?>
        <div class="list-item">
          <input type="text" name="fr_ideas[]" value="<?= htmlspecialchars($fr_ideas[$i] ?? '') ?>">
          <input type="text" name="en_ideas[]" value="<?= htmlspecialchars($en_ideas[$i] ?? '') ?>">
          <button type="button" class="btn-remove" onclick="this.closest('.list-item').remove();">&times;</button>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addItem('ideas-container', 'fr_ideas[]', 'en_ideas[]')">+ Ajouter / Add</button>
    </div>

    <!-- ACCESS -->
    <div class="section-card">
      <h3>Accès / Access</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group">
            <label>Adresse</label>
            <textarea name="fr_access_address" rows="2"><?= htmlspecialchars($fr['access_address'] ?? '') ?></textarea>
          </div>
          <div class="field-group">
            <label>Itinéraire</label>
            <input type="text" name="fr_access_directions" value="<?= htmlspecialchars($fr['access_directions'] ?? '') ?>">
          </div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Address</label>
            <textarea name="en_access_address" rows="2"><?= htmlspecialchars($en['access_address'] ?? '') ?></textarea>
          </div>
          <div class="field-group">
            <label>Directions</label>
            <input type="text" name="en_access_directions" value="<?= htmlspecialchars($en['access_directions'] ?? '') ?>">
          </div>
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
const quillFr = new Quill('#intro-editor-fr', { theme: 'snow', modules: { toolbar: toolbarOpts } });
const quillEn = new Quill('#intro-editor-en', { theme: 'snow', modules: { toolbar: toolbarOpts } });

document.getElementById('autour-form').addEventListener('submit', function() {
  document.getElementById('fr-intro-input').value = quillFr.root.innerHTML;
  document.getElementById('en-intro-input').value = quillEn.root.innerHTML;
});

function addItem(containerId, frName, enName) {
  const container = document.getElementById(containerId);
  const item = document.createElement('div');
  item.className = 'list-item';
  item.innerHTML = `
    <input type="text" name="${frName}" value="">
    <input type="text" name="${enName}" value="">
    <button type="button" class="btn-remove" onclick="this.closest('.list-item').remove();">&times;</button>
  `;
  container.appendChild(item);
}
</script>
<script src="image-upload.js"></script>
<script>
createImageUpload(document.getElementById('cta-image-upload'), {
  name: 'cta_image',
  value: '<?= htmlspecialchars($fr['cta_image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image bandeau bas / CTA band image'
});
</script>
</body>
</html>