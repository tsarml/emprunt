<?php
session_start();
require 'fonction.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    if (connexion($email, $mdp)) {
        header("Location: accueil.php");
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <label>Mot de passe:</label>
        <input type="password" name="mdp" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
    <?php if (isset($erreur)) echo "<p>$erreur</p>"; ?>
    <p><a href="inscription.php">S'inscrire</a></p>
</body>
</html>