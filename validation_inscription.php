<?php
session_start();
ob_start();

// Inclusion des classes PHPMailer depuis le dossier "PHPMailer"
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $email         = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password      = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nom           = htmlspecialchars($_POST['nom']);
    $prenom        = htmlspecialchars($_POST['prenom']);
    $age           = intval($_POST['age']);
    $genre         = htmlspecialchars($_POST['genre']);
    $dateNaissance = $_POST['date_naissance'];
    $typeMembre    = htmlspecialchars($_POST['type_membre']);
    
    // Gestion du téléchargement de la photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photoName = basename($_FILES['photo']['name']);
        $targetDir = __DIR__ . '/uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $targetFile = $targetDir . $photoName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
    } else {
        $targetFile = '';
    }
    
    // Générer un code de validation à 6 chiffres
    $validationCode = random_int(100000, 999999);
    
    // Préparer les données utilisateur avec le code de validation et le statut non validé
    $userData = [
       'email'           => $email,
       'password'        => $password,
       'nom'             => $nom,
       'prenom'          => $prenom,
       'age'             => $age,
       'genre'           => $genre,
       'date_naissance'  => $dateNaissance,
       'type_membre'     => $typeMembre,
       'photo'           => $targetFile,
       'validation_code' => $validationCode,
       'validated'       => false
    ];
    
    // Stocker les données dans le fichier JSON situé dans le dossier "data"
    $filePath = __DIR__ . '/data/users.json';
    if (file_exists($filePath)) {
        $json = file_get_contents($filePath);
        $users = json_decode($json, true);
        if (!$users) {
            $users = [];
        }
    } else {
        $users = [];
    }
    $users[] = $userData;
    file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));
    
    // Stocker l'adresse e-mail dans la session pour la validation ultérieure
    $_SESSION['user_email'] = $email;
    
    // Préparer l'envoi de l'e-mail de validation via PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Configuration du serveur SMTP (exemple avec Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'julien.megnoux@gmail.com';  // Adresse d'expéditeur fixe
        $mail->Password   = 'dlsa vzxj qbrz adxy'; // Remplacez par votre mot de passe d'application
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        
        // Définir l'expéditeur et le destinataire
        $mail->setFrom('julien.megnoux@gmail.com', 'Maison Connecte');
        $mail->addAddress($email, $prenom);
        
        // Définir le contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Validation de votre inscription sur Maison Connecte';
        $mail->Body    = "Bonjour $prenom,<br><br>
                          Merci de vous être inscrit sur Maison Connecte.<br>
                          Votre code de validation est : <strong>$validationCode</strong><br>
                          Veuillez vous rendre sur <a href='http://localhost/maisonconnectee/validation_code.php'>cette page</a> et saisir ce code pour valider votre inscription.<br><br>
                          Cordialement,<br>L'équipe Maison Connecte";
        $mail->AltBody = "Bonjour $prenom,\n\nMerci de vous être inscrit sur Maison Connecte.\nVotre code de validation est : $validationCode\nVeuillez vous rendre sur http://localhost/maisonconnectee/validation_code.php et saisir ce code pour valider votre inscription.\n\nCordialement,\nL'équipe Maison Connecte";
        
        $mail->send();
    } catch (Exception $e) {
        error_log("Erreur d'envoi de mail : " . $mail->ErrorInfo);
    }
    
    // Rediriger l'utilisateur vers la page de validation du code
    header("Location: validation_code.php");
    ob_end_flush();
    exit();
}
?>
