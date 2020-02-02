<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    $user_id=$_SESSION['user_id'];
    $user_login=$_SESSION['user_login'];
} else {
    header('Location: index.php');
    exit;
}

require_once('../includes/functions.php');
include_once('../includes/constants.php');
require_once('../includes/connect_infos.php');
require_once('../includes/connect_base.php');

$sql=
    'SELECT idJoueur,loginJoueur,niveau
    FROM joueurs
    WHERE idJoueur in (
        SELECT distinct idJoueur
        FROM joueurs_vaisseaux
        where activite=1
        and idJoueur <> :user_id
    )'
;
$stmt=$pdo->prepare($sql);
$stmt->bindParam(':user_id',$user_id);
$stmt->execute();
$joueursDisponibles=$stmt->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Combats</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/fight.css">
</head>
<body>

    <?php require_once('./templates/_nav.html') ?>

    <section id="container">
        <h1>Combats</h1>
        <section>
            <h2>Joueurs prêts pour le combat</h2>
            <div id="available-players">
            <?php foreach($joueursDisponibles as $joueur):?>
                <section>
                    <h2><?=$joueur->loginJoueur?></h2>
                    <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fkooledge.com%2Fassets%2Fdefault_medium_avatar-57d58da4fc778fbd688dcbc4cbc47e14ac79839a9801187e42a796cbd6569847.png&f=1&nofb=1" alt="">
                    <p>Niveau: <?=$joueur->niveau?></p>
                    <form action="battleground.php" method="GET">
                        <button type="submit" name="fight" value="<?=$joueur->idJoueur?>">Affronter</button>
                    </form>
                </section>
            <?php endforeach?>
            </div>
        </section>
    </section>
</body>
</html>