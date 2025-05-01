<?php
include 'check_auth.php';
include 'config.php';

// Gestion suppression avec transaction
if (isset($_GET['supprimer'])) {
    $numdossier = $_GET['supprimer'];
    
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'oui') {
        mysqli_begin_transaction($conn);
        
        try {
            // 1. Supprimer les rubriques associées
            $sql_delete_rubriques = "DELETE FROM Rubrique WHERE numdossier='$numdossier'";
            if (!mysqli_query($conn, $sql_delete_rubriques)) {
                throw new Exception("Erreur suppression rubriques: " . mysqli_error($conn));
            }
            
            // 2. Supprimer le dossier
            $sql_delete_dossier = "DELETE FROM Dossier WHERE numdossier='$numdossier'";
            if (!mysqli_query($conn, $sql_delete_dossier)) {
                throw new Exception("Erreur suppression dossier: " . mysqli_error($conn));
            }
            
            mysqli_commit($conn);
            $_SESSION['success'] = "Dossier supprimé avec succès";
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: miseajour_DOSSIER.php");
        exit();
    } else {
        echo "<script>
            if (confirm('Voulez-vous vraiment supprimer ce dossier et toutes ses rubriques ?')) {
                window.location.href = 'miseajour_DOSSIER.php?supprimer=$numdossier&confirm=oui';
            }
        </script>";
    }
}

// Récupérer les dossiers avec jointure
$sql = "SELECT d.*, m.designation_maladie, COUNT(r.numrubrique) as nb_rubriques
        FROM Dossier d
        JOIN Maladie m ON d.num_maladie = m.num_maladie
        LEFT JOIN Rubrique r ON d.numdossier = r.numdossier
        GROUP BY d.numdossier";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Dossiers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-hover tbody tr:hover { background-color: #f8fff0; }
        .badge-rubrique { background-color: #4CAF50; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid mt-4">
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">📑 Gestion des Dossiers</h3>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>N° Dossier</th>
                                <th>Date Dépôt</th>
                                <th>Maladie</th>
                                <th>Rubriques</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['numdossier'] ?></td>
                                <td><?= date('d/m/Y', strtotime($row['datedepot'])) ?></td>
                                <td><?= $row['designation_maladie'] ?></td>
                                <td>
                                    <span class="badge badge-rubrique text-white">
                                        <?= $row['nb_rubriques'] ?> rubrique(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="miseajour_DOSSIER.php?supprimer=<?= $row['numdossier'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           title="Supprimer">
                                            🗑️
                                        </a>
                                        <a href="modifier_dossier.php?numdossier=<?= $row['numdossier'] ?>" 
                                           class="btn btn-warning btn-sm"
                                           title="Modifier">
                                            ✏️
                                        </a>
                                        <a href="details_dossier.php?numdossier=<?= $row['numdossier'] ?>" 
                                           class="btn btn-info btn-sm"
                                           title="Détails">
                                            👁️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer text-end">
                <a href="accueil.php" class="btn btn-success">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>