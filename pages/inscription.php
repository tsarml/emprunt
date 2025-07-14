<?php
require_once 'fonction.php';

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $email = $_POST['email'];
    $ville = $_POST['ville'];
    $mdp = $_POST['mdp'];
    $image_profil = $_POST['image_profil'];
    
    if (inscription($nom, $date_naissance, $genre, $email, $ville, $mdp, $image_profil)) {
        $succes = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    } else {
        $erreur = "Erreur lors de l'inscription. Email déjà utilisé.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
  <link rel="stylesheet" href="../assets/css/1.css">
    <link rel="stylesheet" href="bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <h2>Inscription</h2>
    
    <?php if ($erreur): ?>
        <div class="error"><?php echo $erreur; ?></div>
    <?php endif; ?>
    
    <?php if ($succes): ?>
        <div class="success"><?php echo $succes; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        
        <div class="form-group">
            <label for="date_naissance">Date de naissance:</label>
            <input type="date" id="date_naissance" name="date_naissance" required>
        </div>
        
        <div class="form-group">
            <label for="genre">Genre:</label>
            <select id="genre" name="genre" required>
                <option value="">Sélectionnez</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="ville">Ville:</label>
            <input type="text" id="ville" name="ville" required>
        </div>
        
        <div class="form-group">
            <label for="mdp">Mot de passe:</label>
            <input type="password" id="mdp" name="mdp" required>
        </div>
        
        <div class="form-group">
            <label for="image_profil">Image de profil:</label>
            <input type="text" id="image_profil" name="image_profil" placeholder="Nom du fichier image">
        </div>
        
        <button type="submit">S'inscrire</button>
    </form>
    
    <div class="link">
        <a href="login.php">Déjà inscrit ? Se connecter</a>
    </div>
</body>
</html>