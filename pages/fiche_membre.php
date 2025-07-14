<?php
session_start();
require_once 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_membre'])) {
    header("Location: liste_objets.php");
    exit();
}

$conn = connecterBDD();
$id_membre = (int)$_GET['id_membre'];

$result_membre = mysqli_query($conn, "SELECT nom, date_de_naissance, genre, email, ville, image_profil FROM membre WHERE id_membre = $id_membre");
$membre = mysqli_fetch_assoc($result_membre);

if (!$membre) {
    header("Location: liste_objets.php");
    exit();
}

$result_objets = mysqli_query($conn, "
    SELECT o.id_objet, o.nom_objet, c.nom_categorie, 
           COALESCE((SELECT nom_image FROM image_objet WHERE id_objet = o.id_objet AND est_principale = 1 LIMIT 1), 'default.jpg') AS nom_image,
           e.date_retour
    FROM objet o
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
    WHERE o.id_membre = $id_membre
    ORDER BY c.nom_categorie, o.nom_objet
");
$objects = mysqli_fetch_all($result_objets, MYSQLI_ASSOC);
mysqli_close($conn);

$objects_by_category = [];
foreach ($objects as $obj) {
    $objects_by_category[$obj['nom_categorie']][] = $obj;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fiche membre - Needit</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="liste_objets.php">
            <img src="Uploads/logo.png" alt="Needit Logo" style="height: 40px;">
            Needit
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="liste_objets.php">Liste objets</a></li>
                <li class="nav-item"><a class="nav-link" href="ajout_objet.php">Ajouter objet</a></li>
                <li class="nav-item"><a class="nav-link active" href="fiche_membre.php?id_membre=<?php echo $_SESSION['id_membre']; ?>">Profil</a></li>
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
        <h3>Fiche : <?php echo htmlspecialchars($membre['nom']); ?></h3>
        <a href="liste_objets.php" class="btn btn-secondary">Retour</a>
</div>
<div class="card-body">
<h4>Infos membre</h4>
<p><strong>Nom :</strong> <?php echo htmlspecialchars($membre['nom']); ?></p>
<p><strong>Date de naissance :</strong> <?php echo htmlspecialchars($membre['date_de_naissance']); ?></p>
<p><strong>Genre :</strong> <?php echo htmlspecialchars($membre['genre']); ?></p>
<p><strong>Email :</strong> <?php echo htmlspecialchars($membre['email']); ?></p>
<p><strong>Ville :</strong> <?php echo htmlspecialchars($membre['ville']); ?></p>
<p><strong>Photo :</strong> <img src="Uploads/<?php echo htmlspecialchars($membre['image_profil'] ?: 'default.jpg'); ?>" alt="Photo profil" style="max-width: 100px;"></p>
<hr>
<h4>Objets</h4>
<?php if ($objects): ?>
<?php foreach ($objects_by_category as $categorie => $objets): ?>
    <h5><?php echo htmlspecialchars($categorie); ?></h5>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Objet</th>
                <th>Image</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($objets as $obj): ?>
                <tr>
                    <td><a href="fiche_objet.php?id_objet=<?php echo $obj['id_objet']; ?>"><?php echo htmlspecialchars($obj['nom_objet']); ?></a></td>
                    <td><img src="Uploads/<?php echo htmlspecialchars($obj['nom_image']); ?>" alt="Image objet" style="max-width: 100px;"></td>
                    <td><?php echo $obj['date_retour'] ? '<span class="badge bg-warning text-dark">Emprunté</span>' : '<span class="badge bg-success">Disponible</span>'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>
<?php else: ?>
    <p>Aucun objet.</p>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>