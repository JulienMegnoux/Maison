<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    die("Aucune session utilisateur trouvée. Veuillez vous inscrire ou vous connecter.");
}

$errorMessage = "";
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $errorMessage = "Code de validation incorrect. Veuillez réessayer.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validation de votre inscription</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 400px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border: 1px solid #ddd;
      box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    label {
      margin-top: 10px;
      display: block;
    }
    input[type="text"] {
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 100%;
    }
    input[type="submit"] {
      margin-top: 20px;
      padding: 10px;
      background: #3a3a3a;
      color: #fff;
      border: none;
      cursor: pointer;
      border-radius: 4px;
      width: 100%;
    }
    input[type="submit"]:hover {
      background: #555;
    }
    .error {
      color: red;
      margin-top: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Validation de votre inscription</h1>
    <form action="traitement_validation.php" method="POST">
      <label for="validation_code">Code de validation :</label>
      <input type="text" id="validation_code" name="validation_code" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required title="Veuillez entrer un code à 6 chiffres">
      <input type="submit" value="Valider">
    </form>
    <?php if ($errorMessage): ?>
      <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
