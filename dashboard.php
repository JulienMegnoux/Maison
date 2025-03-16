<?php
session_start();
// Vérification de la connexion
if (!isset($_SESSION['user_email'])) {
    header("Location: connexion.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maison Connectée - Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f2f2f2;
      color: #333;
    }
    header {
      background: #3a3a3a;
      color: #fff;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      margin: 0;
    }
    .header-buttons a {
      background: #fff;
      color: #3a3a3a;
      text-decoration: none;
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
    }
    .header-buttons a:hover {
      background: #ccc;
    }
    .nav {
      display: flex;
      justify-content: center;
      background: #e0e0e0;
      padding: 10px 0;
    }
    .nav button {
      background: #fff;
      border: none;
      padding: 10px 20px;
      margin: 0 10px;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s;
    }
    .nav button:hover {
      background: #ccc;
    }
    .nav button.active {
      background: #3a3a3a;
      color: #fff;
    }
    .container {
      padding: 20px;
    }
    /* Exemple de contenu pour la maison connectée */
  </style>
</head>
<body>
  <header>
    <div>
      <h1>Maison Connectée</h1>
      <p>Bienvenue dans votre espace personnel</p>
    </div>
    <div class="header-buttons">
      <a href="deconnexion.php" class="button">Déconnexion</a>
    </div>
  </header>
  <!-- Navigation entre étages par exemple -->
  <div class="nav">
    <button class="floor-btn active" data-floor="rez-de-chaussée">Rez-de-chaussée</button>
    <button class="floor-btn" data-floor="premier-etage">1er Étage</button>
    <button class="floor-btn" data-floor="deuxieme-etage">2ème Étage</button>
  </div>
  <div class="container">
    <!-- Contenu de la maison connectée -->
    <h2>Votre maison</h2>
    <p>Ici s'affichent les informations et commandes relatives à votre domicile connecté.</p>
    <!-- Vous pouvez ici intégrer le code de la maison (étages, pièces, etc.) -->
  </div>
  <script>
    // Exemple simple de gestion des onglets pour la navigation entre étages
    const floorButtons = document.querySelectorAll('.floor-btn');
    floorButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        floorButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // Ajoutez ici la logique d'affichage pour chaque étage
      });
    });
  </script>
</body>
</html>
