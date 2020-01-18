<?php 
if ($_GET['identified'] != true) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Journal de bord</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <h1>Bienvenue sur votre page personnelle !</h1>
</body>
</html>