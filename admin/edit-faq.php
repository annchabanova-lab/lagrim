<?php
require_once 'config.php';
require_login();

$fr = load_json('fr/faq.json');
$en = load_json('en/faq.json');
$saved = isset($_GET['saved']);

$fr_items = $fr['items'] ?? [];
$en_items = $en['items'] ?? [];
$max_items = max(count($fr_items), count($en_items));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — FAQ</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
  .faq-item {
    border-bottom: 1px solid #f0ede6;
    padding-bottom: 1.25rem;
    margin-bottom: 1.25rem;
    position: relative;
  }
  .faq-item:last-of-type { border-bottom: none; }
  .faq-item-number {
    font-family: 'Playfair Display', serif;
    font-size: 0.85rem;
    color: var(--tuile);
    margin-bottom: 0.5rem;
  }
  .faq-fields {
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
  .btn-remove {
    background: none;
    border: none;
    color: #ccc;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 8px;
    line-height: 1;
    position: absolute;
    top: 0;
    right: 0;
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
  @media (max-width: 768px) {
    .faq-fields { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">&larr; Retour</a>
  <h1>FAQ</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">FAQ / Frequently Asked Questions</h2>

  <form method="POST" action="save-faq.php">

    <!-- HERO -->
    <div class="section-card">
      <h3>Hero (bandeau haut / top banner)</h3>
      <div class="bilingual-row">
        <div class="lang-col">
          <h4 class="fr">FR</h4>
          <div class="field-group">
            <label>Titre</label>
            <input type="text" name="fr_hero_heading" value="<?= htmlspecialchars($fr['hero_heading'] ?? '') ?>">
          </div>
        </div>
        <div class="lang-col">
          <h4 class="en">EN</h4>
          <div class="field-group">
            <label>Heading</label>
            <input type="text" name="en_hero_heading" value="<?= htmlspecialchars($en['hero_heading'] ?? '') ?>">
          </div>
        </div>
      </div>
      <div id="hero-image-upload" style="margin-top:1rem"></div>
    </div>

    <div class="section-card">
      <h3>Questions &amp; Réponses / Questions &amp; Answers</h3>
      <div id="faq-container">
        <?php for ($i = 0; $i < $max_items; $i++): ?>
        <div class="faq-item">
          <div class="faq-item-number">Q<?= $i + 1 ?></div>
          <button type="button" class="btn-remove" onclick="this.closest('.faq-item').remove();renumber();">&times;</button>
          <div class="faq-fields">
            <div class="lang-col">
              <?php if ($i === 0): ?><h4 class="fr">FR</h4><?php endif; ?>
              <div class="field-group">
                <label>Question</label>
                <input type="text" name="fr_items[<?= $i ?>][question]" value="<?= htmlspecialchars($fr_items[$i]['question'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Réponse</label>
                <textarea name="fr_items[<?= $i ?>][answer]" rows="2"><?= htmlspecialchars($fr_items[$i]['answer'] ?? '') ?></textarea>
              </div>
            </div>
            <div class="lang-col">
              <?php if ($i === 0): ?><h4 class="en">EN</h4><?php endif; ?>
              <div class="field-group">
                <label>Question</label>
                <input type="text" name="en_items[<?= $i ?>][question]" value="<?= htmlspecialchars($en_items[$i]['question'] ?? '') ?>">
              </div>
              <div class="field-group">
                <label>Answer</label>
                <textarea name="en_items[<?= $i ?>][answer]" rows="2"><?= htmlspecialchars($en_items[$i]['answer'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="btn-add" onclick="addItem()">+ Ajouter une question / Add a question</button>
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

<script>
let itemIndex = <?= $max_items ?>;

function addItem() {
  const container = document.getElementById('faq-container');
  const item = document.createElement('div');
  item.className = 'faq-item';
  item.innerHTML = `
    <div class="faq-item-number">Q${itemIndex + 1}</div>
    <button type="button" class="btn-remove" onclick="this.closest('.faq-item').remove();renumber();">&times;</button>
    <div class="faq-fields">
      <div class="lang-col">
        <div class="field-group">
          <label>Question</label>
          <input type="text" name="fr_items[${itemIndex}][question]" value="">
        </div>
        <div class="field-group">
          <label>Réponse</label>
          <textarea name="fr_items[${itemIndex}][answer]" rows="2"></textarea>
        </div>
      </div>
      <div class="lang-col">
        <div class="field-group">
          <label>Question</label>
          <input type="text" name="en_items[${itemIndex}][question]" value="">
        </div>
        <div class="field-group">
          <label>Answer</label>
          <textarea name="en_items[${itemIndex}][answer]" rows="2"></textarea>
        </div>
      </div>
    </div>
  `;
  container.appendChild(item);
  itemIndex++;
}

function renumber() {
  document.querySelectorAll('.faq-item-number').forEach(function(el, i) {
    el.textContent = 'Q' + (i + 1);
  });
}
</script>
<script src="image-upload.js"></script>
<script>
createImageUpload(document.getElementById('hero-image-upload'), {
  name: 'hero_image',
  value: '<?= htmlspecialchars($fr['hero_image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image hero / Hero image'
});

createImageUpload(document.getElementById('cta-image-upload'), {
  name: 'cta_image',
  value: '<?= htmlspecialchars($fr['cta_image'] ?? '', ENT_QUOTES) ?>',
  label: 'Image bandeau bas / CTA band image'
});
</script>
</body>
</html>