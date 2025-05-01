<?php
include 'check_auth.php';
include 'config.php';

$matricule = $_SESSION['matricule'];
$sql = "SELECT a.*, e.nom_entreprise, e.adresse, e.telephone, e.email 
        FROM Assure a 
        JOIN Entreprise e ON a.num_entreprise = e.num_entreprise 
        WHERE a.matricule='$matricule'";
$result = mysqli_query($conn, $sql);
$assure = mysqli_fetch_assoc($result);

// Formatage de la date de naissance
$date_naissance = date_create($assure['date_naissance']);
$date_formatted = date_format($date_naissance, 'd/m/Y');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        body{
            background-image: url(https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTHIwFlgaEgk8vmsXDX7eDCOwgxXS4ir1didQ&s);
            background-size: cover;
        }
        h2{
            color:rgb(25, 199, 49);
        }
        strong{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color: rgb(0, 255, 13);
        }
        .info-block {
            margin: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 50%;
            margin: auto;
            margin-top: 15px;
        }
        .info-item {
            margin: 10px 0;
        }
    </style>

</head>
<body>
    <h2 class="text-center p-4">Bienvenue <?php echo $assure['prenom_ass'] . ' ' . $assure['nom_ass']; ?></h2>
    
    <div class="info-block">
        <h3>📋 Vos informations personnelles</h3>
        <div class="info-item">
            <strong>Matricule:</strong> <?php echo $assure['matricule']; ?>
        </div>
        <div class="info-item">
            <strong>Date de naissance:</strong> <?php echo $date_formatted; ?>
        </div>
        <div class="info-item">
            <strong>Enfants à charge:</strong> <?php echo $assure['nb_enfant']; ?>
        </div>
        <div class="info-item">
            <strong>Situation familiale:</strong> <?php echo $assure['situation_familiale']; ?>
        </div>
    </div>

    <div class="info-block">
        <h3>🏢 Votre entreprise</h3>
        <div class="info-item">
            <strong>Nom:</strong> <?php echo $assure['nom_entreprise']; ?>
        </div>
        <div class="info-item">
            <strong>Adresse:</strong> <?php echo $assure['adresse']; ?>
        </div>
        <div class="info-item">
            <strong>Téléphone:</strong> <?php echo $assure['telephone']; ?>
        </div>
        <div class="info-item">
            <strong>Email:</strong> <?php echo $assure['email']; ?>
        </div>
    </div>

    <!-- Menu déroulant -->
    <div style="margin-top: 20px;">
        <select class="form-select m-auto" onchange="location = this.value;" style="padding: 8px; width: 200px;">
            <option value="">🔍 Choisir une action...</option>
            <option value="ajouter_dossier.php">➕ Ajouter dossier</option>
            <option value="miseajour_DOSSIER.php">📂 Gérer dossiers</option>
            <option value="logout.php">🚪 Déconnexion</option>
        </select>
    </div>
</body>
</html>