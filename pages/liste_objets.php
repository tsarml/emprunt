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
     <link rel="stylesheet" href="../assets/css/1.css">
    <link rel="stylesheet" href="bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">


</head>
<body>
    <p><a href="deconnexion.php">Deconnexion</a></p>
    <p><a href="filtre_categorie.php">Filtre</a></p>
    <h2>Liste des objets</h2>
    <table>
        <tr>
            <th>Objet</th>
            <th>Propriétaire</th>
            <th>Catégorie</th>
            <th>Image</th>
            <th>Date de retour</th>
        </tr>
        <?php foreach ($objects as $obj): ?>
        <tr>
            <td><?php echo $obj['nom_objet']; ?></td>
            <td><?php echo $obj['proprietaire']; ?></td>
            <td><?php echo $obj['nom_categorie']; ?></td>
            <td><?php echo $obj['nom_image'] ? $obj['nom_image'] : 'Aucune image'; ?></td>
            <td><?php echo $obj['date_retour'] ? $obj['date_retour'] : 'Non emprunté'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
   
</body>
</html>