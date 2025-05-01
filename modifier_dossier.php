<?php
include 'config.php';
include 'check_auth.php';

// Récupérer le numéro de dossier
$numdossier = isset($_GET['numdossier']) ? intval($_GET['numdossier']) : 0;

// Charger les données du dossier
$dossier = mysqli_fetch_assoc(mysqli_query(
    $conn, 
    "SELECT * FROM Dossier WHERE numdossier = $numdossier"
));

// Charger la liste des maladies
$maladies = mysqli_query($conn, "SELECT * FROM Maladie");

// Traitement du formulaire
$error = '';
$success = '';

if (isset($_POST['modifier'])) {
    $datedepot = mysqli_real_escape_string($conn, $_POST['datedepot']);
    $num_maladie = intval($_POST['num_maladie']);
    
    // Validation
    if (empty($datedepot)) {
        $error = "La date de dépôt est obligatoire";
    } elseif ($num_maladie <= 0) {
        $error = "Veuillez sélectionner une maladie valide";
    } else {
        // Mise à jour
        $sql = "UPDATE Dossier SET 
                datedepot = '$datedepot',
                num_maladie = $num_maladie
                WHERE numdossier = $numdossier";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Dossier mis à jour avec succès";
            // Recharger les données
            $dossier = mysqli_fetch_assoc(mysqli_query(
                $conn, 
                "SELECT * FROM Dossier WHERE numdossier = $numdossier"
            ));
        } else {
            $error = "Erreur de mise à jour : " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Dossier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .green-theme { background-color: #f8fff0; }
        .card-header { background: #4CAF50!important; color: white; }
        .required::after { content: "*"; color: red; margin-left: 3px; }
    </style>
</head>
<body class="green-theme">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="mb-0">✏️ Modification du dossier #<?= $numdossier ?></h3>
                    </div>
                    
                    <div class="card-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <!-- Date de dépôt -->
                            <div class="mb-4">
                                <label class="form-label required">Date de dépôt</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="datedepot" 
                                       value="<?= $dossier['datedepot'] ?? '' ?>" 
                                       required>
                            </div>

                            <!-- Sélection maladie -->
                            <div class="mb-4">
                                <label class="form-label required">Type de maladie</label>
                                <select class="form-select" name="num_maladie" required>
                                    <option value="">Sélectionnez une maladie</option>
                                    <?php while($maladie = mysqli_fetch_assoc($maladies)): ?>
                                        <option value="<?= $maladie['num_maladie'] ?>" 
                                            <?= ($maladie['num_maladie'] == $dossier['num_maladie']) ? 'selected' : '' ?>>
                                            <?= $maladie['designation_maladie'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" 
                                        name="modifier" 
                                        class="btn btn-success btn-lg">
                                    💾 Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer text-end">
                        <a href="miseajour_DOSSIER.php" class="btn btn-outline-success">
                            ← Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>