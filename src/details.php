<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    // $user_id=$_SESSION['user_id'];
    // $user_login=$_SESSION['user_login'];
} else {
    header('Location: index.php');
    exit;
}

require_once('../includes/functions.php');
include_once('../includes/constants.php');
require_once('../includes/connect_infos.php');
require_once('../includes/connect_base.php');

if (!empty($_GET['id'])) {
    $idVaisseau=test_input($_GET['id']);

    $detailsVaisseau=[];

    $sql=
        'SELECT *
        FROM vaisseaux
        NATURAL JOIN types
        where idVaisseau=:id_vaisseau'
    ;
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id_vaisseau',$idVaisseau);
    $stmt->execute();
    $detailsVaisseau=$stmt->fetch(PDO::FETCH_OBJ);
} else {
    header('Location: shop.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Détails du vaisseau</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/details.css">
</head>
<body>
    
    <?php 
    require_once('./templates/_nav.html'); ?>

    <section id="container">
        <?php if($detailsVaisseau): ?>
        <h1>Détails du vaisseau: <?= $detailsVaisseau->nomVaisseau ?></h1>
        <div id="details-vaisseau">
            <section>
                <h1>Statistiques du vaisseau</h1>
                <table>
                    <thead>
                        <th>Attaque</th>
                        <th>Défense</th>
                        <th>Rapidité</th>
                        <th>Solidité</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $detailsVaisseau->attaque ?></td>
                            <td><?= $detailsVaisseau->defense ?></td>
                            <td><?= $detailsVaisseau->rapidite ?></td>
                            <td><?= $detailsVaisseau->solidite ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <div>
                <img src="<?=$detailsVaisseau->lienImage?>" alt="">
            </div>
        </div>
        <section>
            <h1>Type du vaisseau</h1>
            <p>Vaisseau de type: <?= $detailsVaisseau->nomType ?></p>
            <p><?= $detailsVaisseau->detail ?></p>
        </section>
        <?php else: ?>
        <h1>Pas de vaisseau correspondant !</h1>
        <?php endif ?>
    </section>
</body>
</html>