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
    $query = "SELECT * FROM membre WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    
    if ($user && $user['mdp'] === $mdp) {
        session_start();
        $_SESSION['id_membre'] = $user['id_membre'];
        $_SESSION['nom'] = $user['nom'];
        return true;
    }
    return false;
}

function inscription($nom, $date_naissance, $genre, $email, $ville, $mdp, $image_profil) {
    $conn = connecterBDD();
    
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
    session_start();
    return isset($_SESSION['id_membre']);
}

function deconnexion() {
    session_start();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>