<?php
$host = 'localhost';
$dbname = 'emprunt';
$username = 'root';
$password = '';

function connecterBDD() {
    global $host, $dbname, $username, $password;
    $conn = mysqli_connect($host, $username, $password, $dbname);
    if (!$conn) {
        die("Connexion échouée: " . mysqli_connect_error());
    }
    return $conn;
}

function inscription($nom, $date_de_naissance, $genre, $email, $ville, $mdp) {
    $conn = connecterBDD();
    $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
    $query = "INSERT INTO membre (nom, date_de_naissance, genre, email, ville, mdp) VALUES ('$nom', '$date_de_naissance', '$genre', '$email', '$ville', '$mdp_hash')";
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    return $result;
}

function connexion($email, $mdp) {
    $conn = connecterBDD();
    $query = "SELECT * FROM membre WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    if ($user && password_verify($mdp, $user['mdp'])) {
        session_start();
        $_SESSION['id_membre'] = $user['id_membre'];
        $_SESSION['nom'] = $user['nom'];
        return true;
    }
    return false;
}
?>