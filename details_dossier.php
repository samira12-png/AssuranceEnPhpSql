<?php
include 'check_auth.php';
include 'config.php';

// Récupérer le numéro de dossier depuis l'URL
$numdossier = isset($_GET['numdossier']) ? intval($_GET['numdossier']) : 0;

// Requête pour récupérer les rubriques
$sql = "SELECT * FROM Rubrique WHERE numdossier = $numdossier";
$result = mysqli_query($conn, $sql);

// Requête pour les infos du dossier
$dossier_info = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT d.*, m.designation_maladie 
     FROM Dossier d
     JOIN Maladie m ON d.num_maladie = m.num_maladie
     WHERE d.numdossier = $numdossier"));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Dossier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dossier-header { background: #e9f7ef; border-radius: 8px; }
        .total-box { background: #4CAF50; color: white; padding: 15px; border-radius: 5px; }
        .table-rubriques th { background: #4CAF50!important; color: white; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- En-tête du dossier -->
        <div class="dossier-header p-4 mb-4 shadow">
            <h2 class="mb-3">📄 Dossier N°<?= $numdossier ?></h2>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Maladie :</strong> <?= $dossier_info['designation_maladie'] ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Date dépôt :</strong> <?= date('d/m/Y', strtotime($dossier_info['datedepot'])) ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="total-box">
                        <h5 class="mb-0">Total dossier : 
                            <?= number_format($dossier_info['montant_remboursement'], 2, ',', ' ') ?> DH
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des rubriques -->
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">🧾 Liste des Rubriques</h4>
            </div>
            
            <div class="card-body">
                <?php if(mysqli_num_rows($result) > 0): ?>
                <table class="table table-hover table-rubriques">
                    <thead>
                        <tr>
                            <th>N° Rubrique</th>
                            <th>Nom Rubrique</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($rubrique = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $rubrique['numrubrique'] ?></td>
                            <td><?= $rubrique['nom_rubrique'] ?></td>
                            <td><?= number_format($rubrique['montant_rubrique'], 2, ',', ' ') ?> DH</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="alert alert-warning">
                    Aucune rubrique trouvée pour ce dossier
                </div>
                <?php endif; ?>
            </div>

            <div class="card-footer text-end">
                <a href="miseajour_DOSSIER.php" class="btn btn-success">
                    ← Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>