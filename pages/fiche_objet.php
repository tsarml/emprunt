<?php
session_start();
require_once 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_objet'])) {
    header("Location: liste_objets.php");
    exit();
}

$conn = connecterBDD();
$id_objet = (int)$_GET['id_objet'];

$result_objet = mysqli_query($conn, "
    SELECT o.id_objet, o.nom_objet, m.nom AS proprietaire, c.nom_categorie,
           COALESCE((SELECT nom_image FROM image_objet WHERE id_objet = o.id_objet AND est_principale = 1 LIMIT 1), 'default.jpg') AS image_principale
    FROM objet o
    JOIN membre m ON o.id_membre = m.id_membre
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    WHERE o.id_objet = $id_objet
");
$objet = mysqli_fetch_assoc($result_objet);

if (!$objet) {
    header("Location: liste_objets.php");
    exit();
}

$images = mysqli_query($conn, "SELECT nom_image, est_principale FROM image_objet WHERE id_objet = $id_objet ORDER BY est_principale DESC");
$images = mysqli_fetch_all($images, MYSQLI_ASSOC);

$emprunts = mysqli_query($conn, "
    SELECT e.date_emprunt, e.date_retour, m.nom AS emprunteur
    FROM emprunt e
    JOIN membre m ON e.id_membre = m.id_membre
    WHERE e.id_objet = $id_objet
    ORDER BY e.date_emprunt DESC
");
$emprunts = mysqli_fetch_all($emprunts, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fiche objet - Needit</title>
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
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5 pt-5">
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
<h3>Fiche : <?php echo htmlspecialchars($objet['nom_objet']); ?></h3>
<a href="liste_objets.php" class="btn btn-secondary">Retour</a>
</div>
<div class="card-body">
<div class="row">
    <div class="col-md-6">
        <h4>Infos</h4>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($objet['nom_objet']); ?></p>
        <p><strong>Propriétaire :</strong> <?php echo htmlspecialchars($objet['proprietaire']); ?></p>
        <p><strong>Catégorie :</strong> <span class="badge bg-secondary"><?php echo htmlspecialchars($objet['nom_categorie']); ?></span></p>
    </div>
    <div class="col-md-6">
        <h4>Image principale</h4>
        <img src="Uploads/<?php echo htmlspecialchars($objet['image_principale']); ?>" alt="Image principale" class="img-fluid" style="max-width: 300px;">
    </div>
</div>
<hr>
<h4>Autres images</h4>
<?php if (count($images) > 1): ?>
    <div class="row">
        <?php foreach ($images as $img): if (!$img['est_principale']): ?>
            <div class="col-md-3 mb-3">
                <img src="Uploads/<?php echo htmlspecialchars($img['nom_image']); ?>" alt="Image objet" class="img-fluid" style="max-width: 150px;">
                <a href="supprimer_image.php?id_objet=<?php echo $id_objet; ?>&nom_image=<?php echo urlencode($img['nom_image']); ?>" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Supprimer image ?');">Supprimer</a>
            </div>
        <?php endif; endforeach; ?>
    </div>
<?php else: ?>
    <p>Aucune autre image.</p>
<?php endif; ?>
<hr>
<h4>Historique emprunts</h4>
<?php if ($emprunts): ?>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Date emprunt</th>
                <th>Date retour</th>
                <th>Emprunteur</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emprunts as $emprunt): ?>
                <tr>
                    <td><?php echo htmlspecialchars($emprunt['date_emprunt']); ?></td>
                    <td><?php echo $emprunt['date_retour'] ?: '<span class="badge bg-warning text-dark">En cours</span>'; ?></td>
                    <td><?php echo htmlspecialchars($emprunt['emprunteur']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun emprunt.</p>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>