<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/mentions-legales.json');
$en = load_json('en/legal.json');
$saved = isset($_GET['saved']);

$fr_sections = $fr['sections'] ?? [];
$en_sections = $en['sections'] ?? [];
$max_sections = max(count($fr_sections), count($en_sections));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Mentions légales / Legal</title>
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
  .field-group input { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; color: var(--taupe); }
  .field-group input:focus { outline: none; border-color: var(--sauge); }
  .legal-section { border: 1px solid #f0ede6; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; position: relative; }
  .btn-remove { background: none; border: none; color: #ccc; cursor: pointer; font-size: 1.2rem; padding: 8px; line-height: 1; }
  .btn-remove:hover { color: var(--tuile); }
  .btn-add { background: none; border: 1px dashed var(--sauge); color: var(--sauge-dark); padding: 8px 16px; border-radius: 6px; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.8rem; margin-top: 0.5rem; }
  .btn-add:hover { background: #f0f5ed; }
  .btn-save { background: var(--tuile); color: white; border: none; padding: 14px 40px; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 500; cursor: pointer; letter-spacing: 1px; display: block; width: 100%; margin-top: 1rem; }
  .btn-save:hover { opacity: 0.9; }
  .toast { position: fixed; bottom: 2rem; right: 2rem; background: var(--sauge-dark); color: white; padding: 12px 24px; border-radius: 8px; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: fadeIn 0.3s ease; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  .ql-container { font-family: 'Poppins', sans-serif; font-size: 0.9rem; }
  .ql-editor { min-height: 60px; }
  @media (max-width: 768px) { .bilingual-row { grid-template-columns: 1fr; } }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>Mentions légales / Legal</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">Mentions légales / Legal notice</h2>

  <form method="POST" action="save-legal.php" id="legal-form">
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
    </div>

    <!-- SECTIONS -->
    <div id="sections-container">
      <?php for ($i = 0; $i < $max_sections; $i++):
        $fr_sec = $fr_sections[$i] ?? ['heading' => '', 'text' => ''];
        $en_sec = $en_sections[$i] ?? ['heading' => '', 'text' => ''];
      ?>
      <div class="section-card legal-section">
        <button type="button" class="btn-remove" style="position:absolute;top:0.5rem;right:0.5rem;" onclick="this.closest('.legal-section').remove();">&times;</button>
        <h3>Section <?= $i + 1 ?></h3>
        <div class="bilingual-row">
          <div class="lang-col">
            <h4 class="fr">FR</h4>
            <div class="field-group">
              <label>Titre</label>
              <input type="text" name="fr_sections[<?= $i ?>][heading]" value="<?= htmlspecialchars($fr_sec['heading']) ?>">
            </div>
            <div id="legal-editor-fr-<?= $i ?>"><?= $fr_sec['text'] ?></div>
            <input type="hidden" name="fr_sections[<?= $i ?>][text]" class="quill-hidden-fr" data-idx="<?= $i ?>">
          </div>
          <div class="lang-col">
            <h4 class="en">EN</h4>
            <div class="field-group">
              <label>Heading</label>
              <input type="text" name="en_sections[<?= $i ?>][heading]" value="<?= htmlspecialchars($en_sec['heading']) ?>">
            </div>
            <div id="legal-editor-en-<?= $i ?>"><?= $en_sec['text'] ?></div>
            <input type="hidden" name="en_sections[<?= $i ?>][text]" class="quill-hidden-en" data-idx="<?= $i ?>">
          </div>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    <button type="button" class="btn-add" onclick="addSection()" style="margin-bottom:1rem;">+ Ajouter une section / Add a section</button>

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
const quillInstances = { fr: {}, en: {} };

document.querySelectorAll('[id^="legal-editor-fr-"]').forEach(el => {
  const idx = el.id.replace('legal-editor-fr-', '');
  quillInstances.fr[idx] = new Quill(el, { theme: 'snow', modules: { toolbar: toolbarOpts } });
});
document.querySelectorAll('[id^="legal-editor-en-"]').forEach(el => {
  const idx = el.id.replace('legal-editor-en-', '');
  quillInstances.en[idx] = new Quill(el, { theme: 'snow', modules: { toolbar: toolbarOpts } });
});

document.getElementById('legal-form').addEventListener('submit', function() {
  document.querySelectorAll('.quill-hidden-fr').forEach(input => {
    const idx = input.dataset.idx;
    if (quillInstances.fr[idx]) input.value = quillInstances.fr[idx].root.innerHTML;
  });
  document.querySelectorAll('.quill-hidden-en').forEach(input => {
    const idx = input.dataset.idx;
    if (quillInstances.en[idx]) input.value = quillInstances.en[idx].root.innerHTML;
  });
});

let sectionIndex = <?= $max_sections ?>;

function addSection() {
  const container = document.getElementById('sections-container');
  const div = document.createElement('div');
  div.className = 'section-card legal-section';
  div.innerHTML = `
    <button type="button" class="btn-remove" style="position:absolute;top:0.5rem;right:0.5rem;" onclick="this.closest('.legal-section').remove();">&times;</button>
    <h3>Section ${sectionIndex + 1}</h3>
    <div class="bilingual-row">
      <div class="lang-col">
        <h4 class="fr" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#e8f0e4;color:var(--sauge-dark);">FR</h4>
        <div class="field-group"><label>Titre</label><input type="text" name="fr_sections[${sectionIndex}][heading]" value=""></div>
        <div id="legal-editor-fr-${sectionIndex}"></div>
        <input type="hidden" name="fr_sections[${sectionIndex}][text]" class="quill-hidden-fr" data-idx="${sectionIndex}">
      </div>
      <div class="lang-col">
        <h4 class="en" style="font-size:0.8rem;font-weight:600;display:inline-block;padding:2px 10px;border-radius:3px;background:#fde8d8;color:var(--tuile);">EN</h4>
        <div class="field-group"><label>Heading</label><input type="text" name="en_sections[${sectionIndex}][heading]" value=""></div>
        <div id="legal-editor-en-${sectionIndex}"></div>
        <input type="hidden" name="en_sections[${sectionIndex}][text]" class="quill-hidden-en" data-idx="${sectionIndex}">
      </div>
    </div>
  `;
  container.appendChild(div);
  quillInstances.fr[sectionIndex] = new Quill('#legal-editor-fr-' + sectionIndex, { theme: 'snow', modules: { toolbar: toolbarOpts } });
  quillInstances.en[sectionIndex] = new Quill('#legal-editor-en-' + sectionIndex, { theme: 'snow', modules: { toolbar: toolbarOpts } });
  sectionIndex++;
}
</script>
</body>
</html>