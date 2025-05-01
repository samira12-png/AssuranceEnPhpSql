<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $matricule = $_POST['matricule'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM Assure WHERE matricule='$matricule' AND mot_de_passe='$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['matricule'] = $matricule;
        header("Location: accueil.php");
    } else {
        $error = "Matricule ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authentification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        body{
            background:linear-gradient(90deg,rgb(104, 180, 123),black) ;
            font-family: 'Times New Roman', Times, serif;
            color:white;
        }
        form{
            position: relative;
            padding: 30px;
            z-index: 1;
        }
        h2{
            font-family:Verdana, Geneva, Tahoma, sans-serif;
            color: #d6c41c;
            font-size: 25px;
        }


        form::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url(https://img.freepik.com/free-vector/abstract-technology-green-background_1035-17926.jpg);
            background-size: cover;
            background-position: center;
            opacity: 0.6; /* Adjust opacity here */
            z-index: -1;
            border-radius: 8px; /* Optional */
            }
        .container{
            margin-top: 55px;
        }

    </style>
</head>
<body>
<div class="container">
<h2 class="text-center p-3">Connexion Assuré</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post" class="border border-3 p-4 w-50 m-auto">
        Matricule: <input class="form-control" type="text" name="matricule" required><br>
        Mot de passe: <input class="form-control" type="password" name="password" required><br>
        <button class="btn btn-primary " type="submit" name="login">Se connecter</button>
    </form>
</div>
</body>
</html>