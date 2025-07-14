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

$nom_objet = isset($_POST['nom_objet']) ? $_POST['nom_objet'] : '';
$id_categorie = isset($_POST['id_categorie']) ? (int)$_POST['id_categorie'] : '';
$disponible = isset($_POST['disponible']) ? true : false;

$conditions = [];
if ($nom_objet) $conditions[] = "o.nom_objet LIKE '%" . mysqli_real_escape_string($conn, $nom_objet) . "%'";
if ($id_categorie) $conditions[] = "o.id_categorie = $id_categorie";
if ($disponible) $conditions[] = "e.date_retour IS NULL";

$query = "
    SELECT o.id_objet, o.nom_objet, m.id_membre, m.nom AS proprietaire, c.nom_categorie, 
           COALESCE((SELECT nom_image FROM image_objet WHERE id_objet = o.id_objet AND est_principale = 1 LIMIT 1), 'assets/images/default.jpg') AS nom_image, 
           e.date_emprunt, e.date_retour
    FROM objet o
    JOIN membre m ON o.id_membre = m.id_membre
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    LEFT JOIN emprunt e ON o.id_objet = e.id_objet
";
if ($conditions) $query .= " WHERE " . implode(" AND ", $conditions);
$query .= " ORDER BY m.nom, c.nom_categorie";

$result = mysqli_query($conn, $query);
$objects = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste objets - Needit</title>
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
                <li class="nav-item"><a class="nav-link active" href="liste_objets.php">Liste objets</a></li>
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
<div class="card-header"><h3>Liste objets</h3></div>
<div class="card-body">
<form method="post" class="mb-4">
<div class="row align-items-end">
<div class="col-md-4">
    <label for="nom_objet" class="form-label">Nom objet</label>
    <input type="text" class="form-control" id="nom_objet" name="nom_objet" value="<?php echo htmlspecialchars($nom_objet); ?>">
</div>
<div class="col-md-4">
    <label for="id_categorie" class="form-label">Catégorie</label>
    <select name="id_categorie" id="id_categorie" class="form-select">
        <option value="">Toutes catégories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id_categorie']; ?>" <?php if ($id_categorie == $cat['id_categorie']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['nom_categorie']); ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-2">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="disponible" name="disponible" <?php if ($disponible) echo 'checked'; ?>>
        <label class="form-check-label" for="disponible">Disponible</label>
    </div>
</div>
<div class="col-md-2">
    <button type="submit" class="btn btn-primary">Rechercher</button>
</div>
</div>
</form>
<div class="table-responsive">
<table class="table table-striped">
<thead class="table-dark">
    <tr>
        <th>Objet</th>
        <th>Propriétaire</th>
        <th>Catégorie</th>
        <th>Image</th>
        <th>Date emprunt</th>
        <th>Date retour</th>
        <th>Statut</th>
    </tr>
</thead>
<tbody>
<?php foreach ($objects as $obj): ?>
    <tr>
        <td><a href="fiche_objet.php?id_objet=<?php echo $obj['id_objet']; ?>"><?php echo htmlspecialchars($obj['nom_objet']); ?></a></td>
        <td><a href="fiche_membre.php?id_membre=<?php echo $obj['id_membre']; ?>"><?php echo htmlspecialchars($obj['proprietaire']); ?></a></td>
        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($obj['nom_categorie']); ?></span></td>
        <td><img src="<?php echo htmlspecialchars($obj['nom_image']); ?>" alt="Image objet" style="max-width: 100px;"></td>
        <td><?php echo $obj['date_emprunt'] ? htmlspecialchars($obj['date_emprunt']) : '-'; ?></td>
        <td><?php echo $obj['date_retour'] ? htmlspecialchars($obj['date_retour']) : '-'; ?></td>
        <td><?php echo $obj['date_retour'] ? '<span class="badge bg-warning text-dark">Emprunté</span>' : '<span class="badge bg-success">Disponible</span>'; ?></td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>