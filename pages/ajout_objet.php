<?php
session_start();
require_once 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

$conn = connecterBDD();
$categories = mysqli_query($conn, "SELECT * FROM categorie_objet");
$categories = mysqli_fetch_all($categories, MYSQLI_ASSOC);
$erreur = '';
$succes = '';

$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    $erreur = "Dossier uploads/ introuvable.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$erreur) {
    $nom_objet = mysqli_real_escape_string($conn, $_POST['nom_objet']);
    $id_categorie = (int)$_POST['id_categorie'];
    $id_membre = (int)$_SESSION['id_membre'];

    $query = "INSERT INTO objet (nom_objet, id_membre, id_categorie) VALUES ('$nom_objet', $id_membre, $id_categorie)";
if (mysqli_query($conn, $query)) {
    $id_objet = mysqli_insert_id($conn);
    if (!empty($_FILES['images']['name'][0])) {
        $first = true;
        foreach ($_FILES['images']['name'] as $index => $name) {
            if ($_FILES['images']['error'][$index] == UPLOAD_ERR_OK) {
                $file_name = time() . '_' . basename($name);
                if (move_uploaded_file($_FILES['images']['tmp_name'][$index], $upload_dir . $file_name)) {
                    $est_principale = $first ? 1 : 0;
                    mysqli_query($conn, "INSERT INTO image_objet (id_objet, nom_image, est_principale) VALUES ($id_objet, '$file_name', $est_principale)");
                    $first = false;
                } else {
                    $erreur = "Erreur upload $name.";
                }
            }
        }
    }
        if (!$erreur) $succes = "Objet ajouté !";
    } else {
        $erreur = "Erreur ajout objet.";
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter objet - Needit</title>
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
<li class="nav-item"><a class="nav-link active" href="ajout_objet.php">Ajouter objet</a></li>
<li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
</ul>
</div>
</div>
</nav>
<div class="container mt-5 pt-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
<div class="card-header text-center"><h3>Ajouter objet</h3></div>
<div class="card-body">
    <?php if ($erreur): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erreur); ?></div><?php endif; ?>
    <?php if ($succes): ?><div class="alert alert-success"><?php echo htmlspecialchars($succes); ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom_objet" class="form-label">Nom objet</label>
            <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
        </div>
        <div class="mb-3">
            <label for="id_categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="id_categorie" name="id_categorie" required>
                <option value="">Choisir catégorie</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id_categorie']; ?>"><?php echo htmlspecialchars($cat['nom_categorie']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Images</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
        </div>
        <button type="submit" class="btn btn-success w-100">Ajouter</button>
    </form>
</div>
<div class="card-footer text-center">
    <a href="liste_objets.php" class="btn btn-secondary">Retour</a>
</div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>