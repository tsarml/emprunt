<?php
session_start();
require_once 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

// Vérifier si id_membre est défini dans la session
if (!isset($_SESSION['id_membre']) || !is_numeric($_SESSION['id_membre'])) {
    die("Erreur : Identifiant de membre non valide dans la session.");
}

$conn = connecterBDD();
if ($conn === false) {
    die("Erreur de connexion à la base de données.");
}

// Requête pour les objets intacts
$query_intacts = "
    SELECT o.nom_objet, e.date_emprunt, e.date_retour
    FROM emprunt e
    JOIN objet o ON e.id_objet = o.id_objet
    WHERE e.id_membre = " . mysqli_real_escape_string($conn, $_SESSION['id_membre']) . " 
    AND e.etat = 'ok' AND e.date_retour IS NOT NULL
    ORDER BY e.date_retour DESC
";
$result_intacts = mysqli_query($conn, $query_intacts);
if ($result_intacts === false) {
    die("Erreur dans la requête pour objets intacts : " . mysqli_error($conn));
}
$intacts = mysqli_fetch_all($result_intacts, MYSQLI_ASSOC);

// Requête pour les objets abîmés
$query_abimes = "
    SELECT o.nom_objet, e.date_emprunt, e.date_retour
    FROM emprunt e
    JOIN objet o ON e.id_objet = o.id_objet
    WHERE e.id_membre = " . mysqli_real_escape_string($conn, $_SESSION['id_membre']) . " 
    AND e.etat = 'abime' AND e.date_retour IS NOT NULL
    ORDER BY e.date_retour DESC
";
$result_abimes = mysqli_query($conn, $query_abimes);
if ($result_abimes === false) {
    die("Erreur dans la requête pour objets abîmés : " . mysqli_error($conn));
}
$abimes = mysqli_fetch_all($result_abimes, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>État des objets - Needit</title>
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
                <div class="card-header"><h3>État des objets retournés</h3></div>
                <div class="card-body">
                    <h4>Objets intacts</h4>
                    <?php if ($intacts): ?>
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Objet</th>
                                    <th>Date emprunt</th>
                                    <th>Date retour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($intacts as $intact): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($intact['nom_objet']); ?></td>
                                        <td><?php echo htmlspecialchars($intact['date_emprunt']); ?></td>
                                        <td><?php echo htmlspecialchars($intact['date_retour']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Aucun objet intact retourné.</p>
                    <?php endif; ?>

                    <h4 class="mt-4">Objets abîmés</h4>
                    <?php if ($abimes): ?>
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Objet</th>
                                    <th>Date emprunt</th>
                                    <th>Date retour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($abimes as $abime): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($abime['nom_objet']); ?></td>
                                        <td><?php echo htmlspecialchars($abime['date_emprunt']); ?></td>
                                        <td><?php echo htmlspecialchars($abime['date_retour']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Aucun objet abîmé retourné.</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="fiche_membre.php?id_membre=<?php echo $_SESSION['id_membre']; ?>" class="btn btn-secondary">Retour au profil</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>