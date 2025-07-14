<?php
session_start();
require_once 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['id_emprunt'])) {
    header("Location: fiche_membre.php?id_membre=" . $_SESSION['id_membre']);
    exit();
}

$id_emprunt = (int)$_POST['id_emprunt'];
$conn = connecterBDD();
$result_emprunt = mysqli_query($conn, "SELECT id_objet, id_membre FROM emprunt WHERE id_emprunt = $id_emprunt");
$emprunt = mysqli_fetch_assoc($result_emprunt);

if (!$emprunt || $emprunt['id_membre'] != $_SESSION['id_membre']) {
    header("Location: fiche_membre.php?id_membre=" . $_SESSION['id_membre']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['etat'])) {
    $etat = $_POST['etat'];
    $date_retour = date('Y-m-d');
    mysqli_query($conn, "UPDATE emprunt SET date_retour = '$date_retour', etat = '$etat' WHERE id_emprunt = $id_emprunt");
    mysqli_close($conn);
    header("Location: etat_objets.php"); // Redirige vers etat_objets.php après soumission
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Retour d'objet - Needit</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="liste_objets.php">
            <img src="../assets/images/logo.jpg" alt="Needit Logo" style="height: 40px;">
            Needit
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="liste_objets.php">Liste objets</a></li>
                <li class="nav-item"><a class="nav-link" href="ajout_objet.php">Ajouter objet</a></li>
                <li class="nav-item"><a class="nav-link" href="fiche_membre.php?id_membre=<?php echo $_SESSION['id_membre']; ?>">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h3>Retour d'objet</h3></div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">État de l'objet :</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="etat" value="ok" id="etat_ok" required>
                                <label class="form-check-label" for="etat_ok">Intact</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="etat" value="abime" id="etat_abime">
                                <label class="form-check-label" for="etat_abime">Abîmé</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Confirmer</button>
                        <a href="fiche_membre.php?id_membre=<?php echo $_SESSION['id_membre']; ?>" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>