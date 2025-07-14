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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="text-center">Inscription</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($erreur): ?>
                            <div class="alert alert-danger"><?php echo $erreur; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($succes): ?>
                            <div class="alert alert-success"><?php echo $succes; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_naissance" class="form-label">Date de naissance</label>
                                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="genre" class="form-label">Genre</label>
                                        <select class="form-select" id="genre" name="genre" required>
                                            <option value="">Sélectionnez</option>
                                            <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ville" class="form-label">Ville</label>
                                        <input type="text" class="form-control" id="ville" name="ville" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mdp" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="mdp" name="mdp" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image_profil" class="form-label">Image de profil</label>
                                <input type="text" class="form-control" id="image_profil" name="image_profil" placeholder="Nom du fichier image">
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="login.php" class="text-decoration-none">Déjà inscrit ? Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>