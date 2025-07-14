<?php
session_start();
require 'fonction.php';

$conn = connecterBDD();
$categories = mysqli_query($conn, "SELECT * FROM categorie_objet");
$categories = mysqli_fetch_all($categories, MYSQLI_ASSOC);

$selected_categorie = isset($_POST['id_categorie']) ? $_POST['id_categorie'] : '';
$objects = [];

if ($selected_categorie) {
    $query = "
        SELECT o.id_objet, o.nom_objet, m.nom AS proprietaire, c.nom_categorie, i.nom_image, e.date_retour
        FROM objet o
        JOIN membre m ON o.id_membre = m.id_membre
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
        LEFT JOIN image_objet i ON o.id_objet = i.id_objet
        LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
        WHERE o.id_categorie = $selected_categorie
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
    <title>Filtre par catégorie</title>
        <meta charset="UTF-8">
  <link rel="stylesheet" href="../assets/css/1.css">
    <link rel="stylesheet" href="bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>

   
</head>
<body>
    <h2>Filtre par catégorie</h2>
    <form method="post">
        <label>Choisir une catégorie:</label>
        <select name="id_categorie" required>
            <option value="">Sélectionner une catégorie</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id_categorie']; ?>" <?php if ($selected_categorie == $cat['id_categorie']) echo 'selected'; ?>>
                <?php echo $cat['nom_categorie']; ?>
            </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Filtrer">
    </form>
    <?php if ($objects): ?>
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
    <?php endif; ?>
    <p><a href="liste_objets.php">Retour à la liste complète</a></p>
    <p><a href="login.php">Retour à la connexion</a></p>
</body>
</html>