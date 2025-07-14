<?php
session_start();
require_once 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id_objet']) && isset($_GET['nom_image'])) {
    $conn = connecterBDD();
    $id_objet = (int)$_GET['id_objet'];
    $nom_image = mysqli_real_escape_string($conn, $_GET['nom_image']);
    $file_path = "Uploads/" . $nom_image;

    if (file_exists($file_path)) unlink($file_path);
    mysqli_query($conn, "DELETE FROM image_objet WHERE id_objet = $id_objet AND nom_image = '$nom_image'");
    $result = mysqli_query($conn, "SELECT * FROM image_objet WHERE id_objet = $id_objet AND est_principale = 1");
    if (mysqli_num_rows($result) == 0) {
        mysqli_query($conn, "UPDATE image_objet SET est_principale = 1 WHERE id_objet = $id_objet LIMIT 1");
    }
    mysqli_close($conn);
    header("Location: liste_objets.php?succes=Image supprimée");
} else {
    header("Location: liste_objets.php?erreur=Paramètres manquants");
}
exit();
?>