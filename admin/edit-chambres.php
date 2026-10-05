<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/chambres.json');
$en = load_json('en/bedrooms.json');
$saved = isset($_GET['saved']);

$fr_rooms = $fr['rooms'] ?? [];
$en_rooms = $en['rooms'] ?? [];
$max_rooms = max(count($fr_rooms), count($en_rooms));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Chambres / Bedrooms</title>
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
  .room-item { border: 1px solid #f0ede6; border-radius: 8px; padding: 1.25rem; margin-bottom: 1rem; position: relative; background: #fafaf7; }
  .room-number { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--tuile); margin-bottom: 0.75rem; }
  .btn-remove { background: none; border: none; color: #ccc; cursor: pointer; font-size: 1.2rem; padding: 8px; line-height: 1; }
  .btn-remove:hover { color: var(--tuile); }
  .btn-remove-room { position: absolute; top: 0.75rem; right: 0.75rem; }
  .btn-add { background: none; border: 1px dashed var(--sauge); color: var(--sauge-dark); padding: 8px 16px; border-radius: 6px; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.8rem; margin-top: 0.5rem; }
  .btn-add:hover { background: #f0f5ed; }
  .btn-save { background: var(--tuile); color: white; border: none; padding: 14px 40px; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 500; cursor: pointer; letter-spacing: 1px; display: block; width: 100%; margin-top: 1rem; }
  .btn-save:hover { opacity: 0.9; }
  .toast { position: fixed; bottom: 2rem; right: 2rem; background: var(--sauge-dark); color: white; padding: 12px 24px; border-radius: 8px; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: fadeIn 0.3s ease; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  .ql-container { font-family: 'Poppins', sans-serif; font-size: 0.9rem; }
  .ql-editor { min-height: 80px; }
  @media (max-width: 768px) { .bilingual-row { grid-template-columns: 1fr; } }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>Chambres / Bedrooms</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">Chambres / Bedrooms</h2>

  <form method="POST" action="save-chambres.php" id="chambres-form">

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

    <!-- ROOMS -->
    <div class="section-card">
      <h3>Chambres / Rooms</h3>
      <div id="rooms-container">
        <?php for ($i = 0; $i < $max_rooms; $i++): ?>
        <div class="room-item">
          <div class="room-number">Chambre / Room <?= $i + 1 ?></div>
          <button type="button" class="btn-remove btn-remove-room" onclick="this.closest('.room-item').remove();">&times;</button>
          <div class="bilingual-row">
            <div class="lang-col">
              <h4 class="fr">FR</h4>
              <div class="field-group">
                <label>Label</label>
                <input type="text" name="fr_rooms[<?= $i ?>][label]" value="<?= htmlspecialchars($fr_rooms[$i]['label'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Titre</label>
                <input type="text" name="fr_rooms[<?= $i ?>][heading]" value="<?= htmlspecialchars($fr_rooms[$i]['heading'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Description</label>
                <textarea name="fr_rooms[<?= $i ?>][description]" rows="3"><?= htmlspecialchars($fr_rooms[$i]['description'] ?? '') ?></textarea>
              </div>
              <div class="field-group">
                <label>Détail</label>
                <input type="text" name="fr_rooms[<?= $i ?>][detail]" value="<?= htmlspecialchars($fr_rooms[$i]['detail'] ?? '') ?>">
              </div>
            </div>
            <div class="lang-col">
              <h4 class="en">EN</h4>
              <div class="field-group">
                <label>Label</label>
                <input type="text" name="en_rooms[<?= $i ?>][label]" value="<?= htmlspecialchars($en_rooms[$i]['label'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Heading</label>
                <input type="text" name="en_rooms[<?= $i ?>][heading]" value="<?= htmlspecialchars($en_rooms[$i]['heading'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Description</label>
                <textarea name="en_rooms[<?= $i ?>][description]" rows="3"><?= htmlspecialchars($en_rooms[$i]['description'] ?? '') ?></textarea>
              </div>
              <div class="field-group">
                <label>Detail</label>
                <input type="text" name="en_rooms[<?= $i ?>][detail]" value="<?= htmlspecialchars($en_rooms[$i]['detail'] ?? '') ?>">
              </div>
            </div>
          </div>
          <div class="room-image-upload" id="room-image-<?= $i ?>" style="margin-top:0.75rem;"
               data-name-fr="fr_rooms[<?= $i ?>][image]"
               data-name-en="en_rooms[<?= $i ?>][image]"
               data-value="<?= htmlspecialchars($fr_rooms[$i]['image'] ?? '', ENT_QUOTES) ?>">
          </div>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addRoom()">+ Ajouter une chambre / Add a room</button>
    </div>

    <!-- IDEAL SETUP -->
    <div class="section-card">
      <h3>Configuration idéale / Ideal setup</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group">
            <label>Titre</label>
            <input type="text" name="fr_ideal_heading" value="<?= htmlspecialchars($fr['ideal_heading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Texte</label>
            <textarea name="fr_ideal_text" rows="4"><?= htmlspecialchars($fr['ideal_text'] ?? '') ?></textarea>
          </div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Heading</label>
            <input type="text" name="en_ideal_heading" value="<?= htmlspecialchars($en['ideal_heading'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Text</label>
            <textarea name="en_ideal_text" rows="4"><?= htmlspecialchars($en['ideal_text'] ?? '') ?></textarea>
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

document.getElementById('chambres-form').addEventListener('submit', function() {
  document.getElementById('fr-intro-input').value = quillFr.root.innerHTML;
  document.getElementById('en-intro-input').value = quillEn.root.innerHTML;
});

let roomIndex = <?= $max_rooms ?>;

function addRoom() {
  const container = document.getElementById('rooms-container');
  const item = document.createElement('div');
  item.className = 'room-item';
  item.innerHTML = `
    <div class="room-number">Chambre / Room ${roomIndex + 1}</div>
    <button type="button" class="btn-remove btn-remove-room" onclick="this.closest('.room-item').remove();">&times;</button>
    <div class="bilingual-row">
      <div class="lang-col">
        <h4 class="fr">FR</h4>
        <div class="field-group"><label>Label</label><input type="text" name="fr_rooms[${roomIndex}][label]"></div>
        <div class="field-group"><label>Titre</label><input type="text" name="fr_rooms[${roomIndex}][heading]"></div>
        <div class="field-group"><label>Description</label><textarea name="fr_rooms[${roomIndex}][description]" rows="3"></textarea></div>
        <div class="field-group"><label>Détail</label><input type="text" name="fr_rooms[${roomIndex}][detail]"></div>
      </div>
      <div class="lang-col">
        <h4 class="en">EN</h4>
        <div class="field-group"><label>Label</label><input type="text" name="en_rooms[${roomIndex}][label]"></div>
        <div class="field-group"><label>Heading</label><input type="text" name="en_rooms[${roomIndex}][heading]"></div>
        <div class="field-group"><label>Description</label><textarea name="en_rooms[${roomIndex}][description]" rows="3"></textarea></div>
        <div class="field-group"><label>Detail</label><input type="text" name="en_rooms[${roomIndex}][detail]"></div>
      </div>
    </div>
    <div class="room-image-upload" id="room-image-${roomIndex}" style="margin-top:0.75rem;"
         data-name-fr="fr_rooms[${roomIndex}][image]"
         data-name-en="en_rooms[${roomIndex}][image]"
         data-value="">
    </div>
  `;
  container.appendChild(item);
  initRoomImageUpload(document.getElementById('room-image-' + roomIndex));
  roomIndex++;
}
</script>
<script src="image-upload.js"></script>
<script>
function initRoomImageUpload(el) {
  const nameFr = el.dataset.nameFr;
  const nameEn = el.dataset.nameEn;
  const value = el.dataset.value || '';
  createImageUpload(el, {
    name: nameFr,
    value: value,
    label: 'Image chambre / Room image'
  });
  // Add a hidden input that syncs the EN image to the same value
  const form = document.getElementById('chambres-form') || el.closest('form');
  if (form) {
    const observer = new MutationObserver(function() {
      const frInput = el.querySelector('input[name="' + nameFr + '"]');
      let enInput = el.querySelector('input[name="' + nameEn + '"]');
      if (frInput && !enInput) {
        enInput = document.createElement('input');
        enInput.type = 'hidden';
        enInput.name = nameEn;
        el.appendChild(enInput);
      }
      if (frInput && enInput) enInput.value = frInput.value;
    });
    observer.observe(el, { childList: true, subtree: true, attributes: true });
    // Also sync on form submit
    form.addEventListener('submit', function() {
      const frInput = el.querySelector('input[name="' + nameFr + '"]');
      let enInput = el.querySelector('input[name="' + nameEn + '"]');
      if (frInput && !enInput) {
        enInput = document.createElement('input');
        enInput.type = 'hidden';
        enInput.name = nameEn;
        el.appendChild(enInput);
      }
      if (frInput && enInput) enInput.value = frInput.value;
    });
  }
}

// Initialize all existing room image uploads
document.querySelectorAll('.room-image-upload').forEach(initRoomImageUpload);
</script>
</body>
</html>