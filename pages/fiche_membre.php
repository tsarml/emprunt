<?php
session_start();
require_once 'fonction.php';

if (!estConnecte()) {
    header("Location: login.php");
    exit();
}

$conn = connecterBDD();
$id_membre = (int)$_SESSION['id_membre']; // Utilise l'ID du membre connecté directement

$result_membre = mysqli_query($conn, "SELECT nom, date_de_naissance, genre, email, ville, image_profil FROM membre WHERE id_membre = $id_membre");
$membre = mysqli_fetch_assoc($result_membre);

if (!$membre) {
    header("Location: liste_objets.php");
    exit();
}

$result_emprunts = mysqli_query($conn, "
    SELECT e.id_emprunt, o.id_objet, o.nom_objet, 
           COALESCE((SELECT nom_image FROM image_objet WHERE id_objet = o.id_objet AND est_principale = 1 LIMIT 1), 
                    (SELECT nom_image FROM image_objet WHERE id_objet = o.id_objet LIMIT 1)) AS nom_image,
           e.date_emprunt, e.date_retour
    FROM emprunt e
    JOIN objet o ON e.id_objet = o.id_objet
    WHERE e.id_membre = $id_membre AND e.date_retour IS NULL
    ORDER BY e.date_emprunt DESC
");
$emprunts = mysqli_fetch_all($result_emprunts, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fiche membre - Needit</title>
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
                <li class="nav-item"><a class="nav-link active" href="fiche_membre.php?id_membre=<?php echo $_SESSION['id_membre']; ?>">Mon profil</a></li>
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Fiche : <?php echo htmlspecialchars($membre['nom']); ?></h3>
                    <a href="liste_objets.php" class="btn btn-secondary">Retour</a>
                </div>
                <div class="card-body">
                    <h4>Infos membre</h4>
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($membre['nom']); ?></p>
                    <p><strong>Date de naissance :</strong> <?php echo htmlspecialchars($membre['date_de_naissance']); ?></p>
                    <p><strong>Genre :</strong> <?php echo htmlspecialchars($membre['genre']); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($membre['email']); ?></p>
                    <p><strong>Ville :</strong> <?php echo htmlspecialchars($membre['ville']); ?></p>
                    <p><strong>Photo :</strong> 
                        <img src="../assets/images/<?php echo htmlspecialchars($membre['image_profil'] ?: 'default.jpg'); ?>" 
                             alt="Photo profil" 
                             style="max-width: 100px;" 
                             onerror="this.src='../assets/images/default.jpg';"> <!-- Fallback image -->
                    </p>
                    <hr>
                    <h4>Emprunts en cours</h4>
                    <?php if ($emprunts): ?>
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Objet</th>
                                    <th>Image</th>
                                    <th>Date emprunt</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emprunts as $emprunt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($emprunt['nom_objet']); ?></td>
                                        <td><img src="<?php echo htmlspecialchars($emprunt['nom_image']); ?>" 
                                                 alt="Image objet" 
                                                 style="max-width: 100px;" 
                                                 onerror="this.src='../assets/images/default.jpg';"> <!-- Fallback image -->
                                        </td>
                                        <td><?php echo htmlspecialchars($emprunt['date_emprunt']); ?></td>
                                        <td>
                                            <form method="post" action="retour_objet.php" style="display:inline;">
                                                <input type="hidden" name="id_emprunt" value="<?php echo $emprunt['id_emprunt']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Retour</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Aucun emprunt pour le moment.</p>
                    <?php endif; ?>
                    <div class="mt-3">
                        <a href="etat_objets.php" class="btn btn-info">Voir l'état des objets retournés</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>