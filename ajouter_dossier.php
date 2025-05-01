<?php
include 'check_auth.php';
include 'config.php';

// Récupérer la liste des maladies
$maladies = mysqli_query($conn, "SELECT * FROM Maladie");

if (isset($_POST['ajouter'])) {
    $numdossier = $_POST['numdossier'];
    $datedepot = $_POST['datedepot'];
    $montant = $_POST['montant'];
    $date_traitement = $_POST['date_traitement'];
    $lien_malade = $_POST['lien_malade'];
    $num_maladie = $_POST['num_maladie'];

    // Validation
    $error = '';
    if (!is_numeric($numdossier)) {
        $error = "Le numéro de dossier doit être un entier";
    } elseif (empty($datedepot) || empty($montant) || empty($num_maladie)) {
        $error = "Les champs obligatoires (*) doivent être remplis";
    } else {
        $sql = "INSERT INTO Dossier 
                (numdossier, datedepot, montant_remboursement, date_traitement, lien_malade, num_maladie)
                VALUES
                ('$numdossier', '$datedepot', '$montant', '$date_traitement', '$lien_malade', '$num_maladie')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Dossier ajouté avec succès";
        } else {
            $error = "Erreur: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Dossier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .green-theme { background-color: #f8fff0; }
        .card-header { background: #4CAF50 !important; color: white; }
        .btn-success { background: #4CAF50; border: none; }
        .btn-success:hover { background: #45a049; }
        .required::after { content: "*"; color: red; margin-left: 3px; }
    </style>
</head>
<body class="green-theme vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h3 class="mb-0">📁 Nouveau Dossier Médical</h3>
                    </div>
                    <div class="card-body">
                        <!-- Messages d'alerte -->
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>

                        <!-- Formulaire -->
                        <form method="post">
                            <!-- Numéro dossier -->
                            <div class="mb-4">
                                <label class="form-label required">Numéro dossier</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="numdossier" 
                                       required
                                       placeholder="Ex: 1001">
                            </div>

                            <!-- Date dépôt -->
                            <div class="mb-4">
                                <label class="form-label required">Date dépôt</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="datedepot" 
                                       required>
                            </div>

                            <!-- Montant -->
                            <div class="mb-4">
                                <label class="form-label required">Montant (DH)</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="montant" 
                                       step="0.01"
                                       required
                                       placeholder="Ex: 2500.00">
                            </div>

                            <!-- Maladie -->
                            <div class="mb-4">
                                <label class="form-label required">Type de maladie</label>
                                <select class="form-select" name="num_maladie" required>
                                    <option value="">Sélectionner une maladie</option>
                                    <?php while($maladie = mysqli_fetch_assoc($maladies)): ?>
                                        <option value="<?= $maladie['num_maladie'] ?>">
                                            <?= $maladie['designation_maladie'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Date traitement -->
                            <div class="mb-4">
                                <label class="form-label">Date traitement</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="date_traitement">
                            </div>

                            <!-- Lien malade -->
                            <div class="mb-4">
                                <label class="form-label">Lien avec le malade</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="lien_malade" 
                                       placeholder="Ex: Patient lui-même">
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" 
                                        name="ajouter" 
                                        class="btn btn-success btn-lg">
                                    💾 Enregistrer le dossier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>