<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que l'e-mail est stocké dans la session
    if (!isset($_SESSION['user_email'])) {
        die("Aucune session utilisateur. Veuillez vous inscrire ou vous connecter.");
    }
    
    // Récupérer l'e-mail depuis la session
    $email = $_SESSION['user_email'];
    
    // Récupérer le code saisi, retirer les espaces et le convertir en entier
    $inputCode = (int) trim($_POST['validation_code']);
    
    $filePath = __DIR__ . '/data/users.json';
    if (!file_exists($filePath)) {
        die("Fichier d'utilisateurs introuvable.");
    }
    
    // Charger les utilisateurs depuis le fichier JSON
    $json = file_get_contents($filePath);
    $users = json_decode($json, true);
    
    $found = false;
    $index = null;
    // Rechercher l'utilisateur correspondant à l'e-mail (comparaison insensible à la casse)
    foreach ($users as $key => $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            $found = true;
            $index = $key;
            break;
        }
    }
    
    if (!$found) {
        die("Utilisateur introuvable.");
    }
    
    // Si le compte est déjà validé, afficher un message et un lien vers la connexion
    if (isset($users[$index]['validated']) && $users[$index]['validated'] === true) {
        echo "Votre compte est déjà validé. Vous pouvez maintenant vous connecter. <a href='connexion.html'>Cliquez ici pour vous connecter.</a>";
        exit();
    }
    
    // Récupérer le code stocké et le convertir en entier
    $storedCode = isset($users[$index]['validation_code']) ? (int)$users[$index]['validation_code'] : null;
    
    // (Optionnel) Débogage : enregistrer dans le log
    error_log("Email en session : " . $email);
    error_log("Code saisi : " . $inputCode);
    error_log("Code stocké : " . $storedCode);
    
    // Comparaison stricte
    if ($storedCode === $inputCode) {
        $users[$index]['validated'] = true;
        // Optionnel : supprimer le code de validation une fois validé
        unset($users[$index]['validation_code']);
        file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));
        echo "Votre inscription a été validée avec succès. Vous pouvez maintenant vous connecter. <a href='connexion.html'>Cliquez ici pour vous connecter.</a>";
        exit();
    } else {
        // En cas de code incorrect, rediriger vers la page de validation avec un paramètre d'erreur
        header("Location: validation_code.php?error=1");
        exit();
    }
}
?>
    