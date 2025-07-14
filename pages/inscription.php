<?php
require 'fonction.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $date_de_naissance = $_POST['date_de_naissance'];
    $genre = $_POST['genre'];
    $email = $_POST['email'];
    $ville = $_POST['ville'];
    $mdp = $_POST['mdp'];
    if (inscription($nom, $date_de_naissance, $genre, $email, $ville, $mdp)) {
        header("Location: login.php");
        exit();
    } else {
        $erreur = "Erreur lors de l'inscription";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form method="post">
        <label>Nom:</label>
        <input type="text" name="nom" required><br><br>
        <label>Date de naissance:</label>
        <input type="date" name="date_de_naissance" required><br><br>
        <label>Genre:</label>
        <select name="genre" required>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
            <option value="Autre">Autre</option>
        </select><br><br>
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <label>Ville:</label>
        <input type="text" name="ville" required><br><br>
        <label>Mot de passe:</label>
        <input type="password" name="mdp" required><br><br>
        <input type="submit" value="S'inscrire">
    </form>
    <?php if (isset($erreur)) echo "<p>$erreur</p>"; ?>
    <p><a href="login.php">Se connecter</a></p>
</body>
</html>