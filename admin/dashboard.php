<?php
require_once 'config.php';
require_login();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — La Grimouillière</title>
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
  }
  .admin-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    color: var(--taupe);
  }
  .admin-header a {
    color: var(--taupe-light);
    text-decoration: none;
    font-size: 0.85rem;
  }
  .admin-header a:hover { color: var(--tuile); }
  .dashboard {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
  }
  .dashboard h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
  }
  .page-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
  }
  .page-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-decoration: none;
    color: var(--taupe);
    box-shadow: 0 2px 8px rgba(74,64,56,0.06);
    transition: transform 0.15s, box-shadow 0.15s;
    display: block;
  }
  .page-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(74,64,56,0.12);
  }
  .page-card .icon {
    font-size: 2rem;
    margin-bottom: 0.75rem;
  }
  .page-card h3 {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.3rem;
  }
  .page-card p {
    font-size: 0.8rem;
    color: var(--taupe-light);
    line-height: 1.5;
  }
  .page-card .badge {
    display: inline-block;
    margin-top: 0.75rem;
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 3px;
    font-weight: 500;
    letter-spacing: 0.5px;
  }
  .badge-fr { background: #e8f0e4; color: var(--sauge-dark); }
  .badge-en { background: #fde8d8; color: var(--tuile); }
  .badge-data { background: #e8e4f0; color: #6B5580; }
  .section-label {
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 2px;
    color: var(--sauge);
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
  }
</style>
</head>
<body>
<div class="admin-header">
  <h1>La Grimouillière — Admin</h1>
  <a href="logout.php">Déconnexion</a>
</div>
<div class="dashboard">
  <h2>Gestion du contenu</h2>

  <p class="section-label">Pages principales</p>
  <div class="page-grid">
    <a href="edit-homepage.php" class="page-card">
      <div class="icon">🏠</div>
      <h3>Accueil / Homepage</h3>
      <p>Hero, introduction, en un coup d'oeil, pourquoi la petite maison</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
    <a href="edit-maison.php" class="page-card">
      <div class="icon">🏡</div>
      <h3>La maison / The house</h3>
      <p>Description, espaces de vie, confort</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
    <a href="edit-chambres.php" class="page-card">
      <div class="icon">🛏️</div>
      <h3>Chambres / Bedrooms</h3>
      <p>Descriptions et photos des chambres</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
    <a href="edit-autour.php" class="page-card">
      <div class="icon">🌳</div>
      <h3>Autour / Nearby</h3>
      <p>Activites, lieux, acces</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
  </div>

  <p class="section-label">Tarifs & Disponibilites</p>
  <div class="page-grid">
    <a href="edit-tarifs.php" class="page-card">
      <div class="icon">💰</div>
      <h3>Tarifs / Rates</h3>
      <p>Prix, informations pratiques</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
    <a href="edit-availability.php" class="page-card">
      <div class="icon">📅</div>
      <h3>Disponibilites</h3>
      <p>Calendrier, periodes disponibles et completes</p>
      <span class="badge badge-data">FR + EN</span>
    </a>
  </div>

  <p class="section-label">Autres pages</p>
  <div class="page-grid">
    <a href="edit-contact.php" class="page-card">
      <div class="icon">✉️</div>
      <h3>Contact</h3>
      <p>Textes d'introduction, informations pratiques</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
    <a href="edit-faq.php" class="page-card">
      <div class="icon">❓</div>
      <h3>FAQ</h3>
      <p>Questions et reponses</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
    <a href="edit-legal.php" class="page-card">
      <div class="icon">📜</div>
      <h3>Mentions legales / Legal</h3>
      <p>Informations juridiques</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
    <a href="edit-footer.php" class="page-card">
      <div class="icon">🦶</div>
      <h3>Pied de page / Footer</h3>
      <p>Adresse, horaires, copyright</p>
      <span class="badge badge-fr">FR</span> <span class="badge badge-en">EN</span>
    </a>
  </div>
</div>
</body>
</html>