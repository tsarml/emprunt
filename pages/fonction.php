<?php
function connecterBDD() {
    $serveur = "localhost";
    $utilisateur = "root";
    $motdepasse = "";
    $basededonnees = "emprunt";
    
    $conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $basededonnees);
    
    if (!$conn) {
        die("Erreur de connexion : " . mysqli_connect_error());
    }
    
    return $conn;
}

function connexion($email, $mdp) {
    $conn = connecterBDD();
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM membre WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    
    if ($user && $user['mdp'] === $mdp) {
        $_SESSION['id_membre'] = $user['id_membre'];
        $_SESSION['nom'] = $user['nom'];
        return true;
    }
    return false;
}

function inscription($nom, $date_naissance, $genre, $email, $ville, $mdp, $image_profil) {
    $conn = connecterBDD();
    
    $nom = mysqli_real_escape_string($conn, $nom);
    $date_naissance = mysqli_real_escape_string($conn, $date_naissance);
    $genre = mysqli_real_escape_string($conn, $genre);
    $email = mysqli_real_escape_string($conn, $email);
    $ville = mysqli_real_escape_string($conn, $ville);
    $mdp = mysqli_real_escape_string($conn, $mdp);
    $image_profil = mysqli_real_escape_string($conn, $image_profil);
    
    $query_check = "SELECT * FROM membre WHERE email = '$email'";
    $result_check = mysqli_query($conn, $query_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        mysqli_close($conn);
        return false;
    }
    
    $query = "INSERT INTO membre (nom, date_de_naissance, genre, email, ville, mdp, image_profil) VALUES ('$nom', '$date_naissance', '$genre', '$email', '$ville', '$mdp', '$image_profil')";
    
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    
    return $result;
}

function estConnecte() {
    return isset($_SESSION['id_membre']);
}

function supprimerImage($id_objet, $nom_image) {
    $conn = connecterBDD();
    $id_objet = mysqli_real_escape_string($conn, $id_objet);
    $nom_image = mysqli_real_escape_string($conn, $nom_image);
    
    $file_path = "Uploads/" . $nom_image;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    $query = "DELETE FROM image_objet WHERE id_objet = $id_objet AND nom_image = '$nom_image'";
    $result = mysqli_query($conn, $query);
    
    $query_check = "SELECT * FROM image_objet WHERE id_objet = $id_objet AND est_principale = 1";
    $result_check = mysqli_query($conn, $query_check);
    if (mysqli_num_rows($result_check) == 0) {
        $query_new_principale = "UPDATE image_objet SET est_principale = 1 WHERE id_objet = $id_objet LIMIT 1";
        mysqli_query($conn, $query_new_principale);
    }
    
    mysqli_close($conn);
    return $result;
}
?>      