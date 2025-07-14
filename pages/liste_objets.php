<?php
session_start();
require 'fonction.php';

$conn = connecterBDD();
$query = "
    SELECT o.id_objet, o.nom_objet, m.nom AS proprietaire, c.nom_categorie, i.nom_image, e.date_retour
    FROM objet o
    JOIN membre m ON o.id_membre = m.id_membre
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    LEFT JOIN image_objet i ON o.id_objet = i.id_objet
    LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
    ORDER BY m.nom, c.nom_categorie
";
$result = mysqli_query($conn, $query);
$objects = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Liste des objets</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Liste des objets</h3>
                        <div>
                            <a href="filtre_categorie.php" class="btn btn-info me-2">Filtrer</a>
                            <a href="deconnexion.php" class="btn btn-danger">Déconnexion</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Objet</th>
                                        <th>Propriétaire</th>
                                        <th>Catégorie</th>
                                        <th>Image</th>
                                        <th>Date de retour</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($objects as $obj): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($obj['nom_objet']); ?></td>
                                        <td><?php echo htmlspecialchars($obj['proprietaire']); ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($obj['nom_categorie']); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($obj['nom_image']): ?>
                                                <i class="bi bi-image"></i> <?php echo htmlspecialchars($obj['nom_image']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Aucune image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($obj['date_retour']): ?>
                                                <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($obj['date_retour']); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Disponible</span>
                                            <?php endif; ?>
                                        </td>
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