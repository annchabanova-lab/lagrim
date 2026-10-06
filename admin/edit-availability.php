<?php
require_once 'config.php';
require_login();

$data = load_json('availability.json');
$periods = $data['periods'] ?? [];
$saved = isset($_GET['saved']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Disponibilités</title>
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
    max-width: 900px;
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
  .period-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.2fr 1fr auto;
    gap: 0.75rem;
    align-items: end;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f0ede6;
  }
  @media (max-width: 768px) {
    .period-row {
      grid-template-columns: 1fr 1fr;
    }
  }
  .field-group { margin-bottom: 0; }
  .field-group label {
    display: block;
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 0.2rem;
    color: var(--taupe-light);
  }
  .field-group input, .field-group select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: 'Poppins', sans-serif;
    font-size: 0.85rem;
    color: var(--taupe);
  }
  .field-group input:focus, .field-group select:focus {
    outline: none;
    border-color: var(--sauge);
  }
  .btn-remove {
    background: none;
    border: none;
    color: #ccc;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 8px;
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
  .status-available { color: var(--sauge-dark); font-weight: 500; }
  .status-booked { color: var(--tuile); font-weight: 500; }
  .status-on_request { color: #9B8A5B; font-weight: 500; }
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
  .help-text {
    font-size: 0.8rem;
    color: var(--taupe-light);
    margin-bottom: 1rem;
    line-height: 1.6;
  }
</style>
</head>
<body>
<div class="admin-header">
  <a href="dashboard.php">← Retour</a>
  <h1>Disponibilités</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<div class="editor-wrap">
  <h2 class="editor-title">Calendrier des disponibilités</h2>
  <p class="help-text">Ajoutez ou modifiez les périodes. Le statut « Complet » bloque la période, « Disponible » la montre ouverte, « Sur demande » invite à nous contacter.</p>

  <form method="POST" action="save-availability.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <div class="section-card">
      <div id="periods-container">
        <?php foreach ($periods as $i => $p): ?>
        <div class="period-row">
          <div class="field-group">
            <label>Période</label>
            <input type="text" name="periods[<?= $i ?>][name]" value="<?= htmlspecialchars($p['name'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Début</label>
            <input type="date" name="periods[<?= $i ?>][start]" value="<?= htmlspecialchars($p['start'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Fin</label>
            <input type="date" name="periods[<?= $i ?>][end]" value="<?= htmlspecialchars($p['end'] ?? '') ?>">
          </div>
          <div class="field-group">
            <label>Statut</label>
            <select name="periods[<?= $i ?>][status]">
              <option value="available" <?= ($p['status'] ?? '') === 'available' ? 'selected' : '' ?>>✅ Disponible</option>
              <option value="booked" <?= ($p['status'] ?? '') === 'booked' ? 'selected' : '' ?>>🔴 Complet</option>
              <option value="on_request" <?= ($p['status'] ?? '') === 'on_request' ? 'selected' : '' ?>>🟡 Sur demande</option>
            </select>
          </div>
          <div class="field-group">
            <label>Tarif</label>
            <input type="text" name="periods[<?= $i ?>][rate]" value="<?= htmlspecialchars($p['rate'] ?? '') ?>">
          </div>
          <button type="button" class="btn-remove" onclick="this.closest('.period-row').remove();">×</button>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn-add" onclick="addPeriod()">+ Ajouter une période</button>
    </div>

    <button type="submit" class="btn-save">ENREGISTRER</button>
  </form>
</div>

<?php if ($saved): ?>
<div class="toast" id="toast">Enregistré !</div>
<script>setTimeout(() => document.getElementById('toast').remove(), 3000);</script>
<?php endif; ?>

<script>
let periodIndex = <?= count($periods) ?>;

function addPeriod() {
  const container = document.getElementById('periods-container');
  const row = document.createElement('div');
  row.className = 'period-row';
  row.innerHTML = `
    <div class="field-group">
      <label>Période</label>
      <input type="text" name="periods[${periodIndex}][name]" value="">
    </div>
    <div class="field-group">
      <label>Début</label>
      <input type="date" name="periods[${periodIndex}][start]" value="">
    </div>
    <div class="field-group">
      <label>Fin</label>
      <input type="date" name="periods[${periodIndex}][end]" value="">
    </div>
    <div class="field-group">
      <label>Statut</label>
      <select name="periods[${periodIndex}][status]">
        <option value="available">✅ Disponible</option>
        <option value="booked">🔴 Complet</option>
        <option value="on_request">🟡 Sur demande</option>
      </select>
    </div>
    <div class="field-group">
      <label>Tarif</label>
      <input type="text" name="periods[${periodIndex}][rate]" value="">
    </div>
    <button type="button" class="btn-remove" onclick="this.closest('.period-row').remove();">×</button>
  `;
  container.appendChild(row);
  periodIndex++;
}
</script>
</body>
</html>