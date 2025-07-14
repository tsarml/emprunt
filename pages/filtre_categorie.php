<?php
session_start();
require 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

$conn = connecterBDD();
$categories = mysqli_query($conn, "SELECT * FROM categorie_objet");
$categories = mysqli_fetch_all($categories, MYSQLI_ASSOC);
$objects = [];

if (isset($_POST['id_categorie']) && $_POST['id_categorie']) {
    $id_categorie = (int)$_POST['id_categorie'];
    $query = "
        SELECT o.id_objet, o.nom_objet, m.id_membre, m.nom AS proprietaire, c.nom_categorie, 
               COALESCE((SELECT nom_image FROM image_objet WHERE id_objet = o.id_objet AND est_principale = 1 LIMIT 1), 'default.jpg') AS nom_image, 
               e.date_retour
        FROM objet o
        JOIN membre m ON o.id_membre = m.id_membre
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
        LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
        WHERE o.id_categorie = $id_categorie
        ORDER BY m.nom
    ";
    $result = mysqli_query($conn, $query);
    $objects = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Filtre catégorie - Needit</title>
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
                <li class="nav-item"><a class="nav-link active" href="filtre_categorie.php">Filtrer catégorie</a></li>
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
<div class="card-header"><h3>Filtre catégorie</h3></div>
<div class="card-body">
<form method="post" class="mb-4">
    <div class="row align-items-end">
        <div class="col-md-8">
            <label for="id_categorie" class="form-label">Catégorie</label>
            <select name="id_categorie" id="id_categorie" class="form-select" required>
                <option value="">Choisir catégorie</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id_categorie']; ?>" <?php if (isset($_POST['id_categorie']) && $_POST['id_categorie'] == $cat['id_categorie']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['nom_categorie']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </div>
</form>
<?php if ($objects): ?>
<div class="table-responsive">
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Objet</th>
            <th>Propriétaire</th>
            <th>Catégorie</th>
            <th>Image</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($objects as $obj): ?>
            <tr>
                <td><a href="fiche_objet.php?id_objet=<?php echo $obj['id_objet']; ?>"><?php echo htmlspecialchars($obj['nom_objet']); ?></a></td>
                <td><a href="fiche_membre.php?id_membre=<?php echo $obj['id_membre']; ?>"><?php echo htmlspecialchars($obj['proprietaire']); ?></a></td>
                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($obj['nom_categorie']); ?></span></td>
                <td><img src="Uploads/<?php echo htmlspecialchars($obj['nom_image']); ?>" alt="Image objet" style="max-width: 100px;"></td>
                <td><?php echo $obj['date_retour'] ? '<span class="badge bg-warning text-dark">Emprunté</span>' : '<span class="badge bg-success">Disponible</span>'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
</div>
<div class="card-footer">
<a href="liste_objets.php" class="btn btn-secondary">Retour liste</a>
</div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>