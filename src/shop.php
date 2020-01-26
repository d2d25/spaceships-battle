<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    $user_id=$_SESSION['user_id'];
} else {
    header('Location: index.php');
    exit;
}

require_once('../includes/functions.php');
require_once('../includes/connect_infos.php');
require_once('../includes/connect_base.php');

// récupère les vaisseaux fantôme de USER avec tableau des vaisseaux possédés et tableau des vaisseaux non disponibles à l'achat
$sql=
    'SELECT jv.idVaisseau,nomVaisseau,possede,disponibleAchat,niveau,prix,lienImage
    FROM joueurs_vaisseaux as jv
    INNER JOIN vaisseaux as v
    ON jv.idVaisseau=v.idVaisseau
    WHERE idJoueur = :1'
    ;
$stmt=$pdo->prepare($sql);
$stmt->bindParam(':1',$user_id);
$stmt->execute();
$vaisseaux=$stmt->fetchAll(PDO::FETCH_ASSOC);
// dump('',$vaisseaux);die;

$vaisseauxFantomes=[];
$vaisseauxPossedes=[];
$vaisseauxDisponibleAchat=[];
foreach ($vaisseaux as $vaisseau){
    $vaisseauxFantomes[$vaisseau['idVaisseau']]=$vaisseau;
    if ($vaisseau['possede']){
        $vaisseauxPossedes[]=$vaisseau['idVaisseau'];
    }
    if ($vaisseau['disponibleAchat']){
        $vaisseauxDisponibleAchat[]=$vaisseau['idVaisseau'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['vaisseau_choisi'])) {
        include_once('../includes/functions.php');
        $vaisseauChoisi = test_input($_POST['vaisseau_choisi']);

        // USER ne possède pas le vaisseau choisi
        if (!in_array($vaisseauChoisi,$vaisseauxPossedes)) {
            $sql=
                'SELECT joueurs.argent 
                FROM joueurs  
                WHERE joueurs.idJoueur = :user_id'
                ;
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':user_id',$user_id);
            $stmt->execute();

            // argent USER
            $argentJoueur=$stmt->fetch(PDO::FETCH_ASSOC)['argent'];

            $niveauVaisseauChoisi=$vaisseauxFantomes[$vaisseauChoisi]['niveau'];
            $prixVaisseauChoisi=$vaisseauxFantomes[$vaisseauChoisi]['prix'];
            
            // user peut acheter le vaisseau
            if ($prixVaisseauChoisi <= $argentJoueur) {
                $reste=$argentJoueur - $prixVaisseauChoisi;
                $sql=
                    'UPDATE joueurs_vaisseaux
                    SET possede = 1
                    WHERE idJoueur = :1
                    AND idVaisseau = :2'
                    ;
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':1',$user_id);
                $stmt->bindParam(':2',$vaisseauChoisi);
                $stmt->execute();

                // ajout vaisseau choisi dans les vaisseaux possédés
                $vaisseauxPossedes[] = $vaisseauChoisi;

                // débit USER : argent - prix vaiseau choisi
                $sql=
                    'UPDATE `joueurs`
                    SET `argent` = :1
                    WHERE `idJoueur` = :2'
                    ;
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':1',$reste,PDO::PARAM_INT);
                $stmt->bindParam(':2',$user_id);
                $stmt->execute();

                // rend les x vaisseaux du même niveau non choisis indisponibles à l'achat
                $sql=
                    'UPDATE joueurs_vaisseaux as jv
                    INNER JOIN vaisseaux as v
                    ON jv.idVaisseau=v.idVaisseau
                    SET `disponibleAchat` = 0
                    WHERE jv.idJoueur = :1
                    AND jv.idVaisseau <> :2
                    AND `niveau` = :3'
                    ;
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':1',$user_id);
                $stmt->bindParam(':2',$vaisseauChoisi);
                $stmt->bindParam(':3',$niveauVaisseauChoisi);
                $stmt->execute();

                // retirer les vaisseaux non disponibles de la liste des vaisseaux disponibles
                $sql=
                    'SELECT idVaisseau
                    FROM joueurs_vaisseaux as jv
                    WHERE disponibleAchat=0
                    AND idJoueur=:1'
                    ;
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':1',$user_id);
                $stmt->execute();

                header('Location: shop.php');
            }
        }        
    }
}
include_once('../includes/constants.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Shop</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/shop.css">
</head>
<body>

    <?php 
    require_once('./templates/_nav.html');
    ?>
    <section id="container">
        <h1>Bienvenue au shop des vaisseaux !</h1>
        <section>
            <section>
                <h2>Vaisseaux en stocks</h2>
                <div id="shop-spaceships">
                <?php foreach ($vaisseauxFantomes as $vaisseauFantome): ?>
                    <section class="relative">
                        <h3><?= $vaisseauFantome['nomVaisseau'] ?></h3>
                    <!-- vaisseau non possédé par USER et disponible à l'achat -->
                    <?php if (!in_array($vaisseauFantome['idVaisseau'],$vaisseauxPossedes) && in_array($vaisseauFantome['idVaisseau'],$vaisseauxDisponibleAchat)): ?>
                        <img src="<?= $vaisseauFantome['lienImage'] ?>" alt="" width="300px">
                        <form action="" method="POST">
                            <input type="hidden" name="vaisseau_choisi" value="<?= $vaisseauFantome['idVaisseau'] ?>">
                            <input type="submit" name="achat_vaisseau" value="Acheter">
                        </form>
                    <!-- vaisseau possédé par USER -->
                    <?php elseif (in_array($vaisseauFantome['idVaisseau'],$vaisseauxPossedes)): ?>
                        <img src="<?= $vaisseauFantome['lienImage'] ?>" class="owned" alt="" width="300px"></a>
                        <p class="absolute">Possédé</p>
                    <?php else: ?>
                    <!-- vaisseau indisponible à l'achat -->
                        <img src="<?= $vaisseauFantome['lienImage'] ?>" class="unavailable" alt="" width="300px"></a>
                        <p class="absolute">A débloquer</p>
                    <?php endif ?>
                        <p>Niveau: <?= $vaisseauFantome['niveau'] ?></p>
                        <p>Prix: <?= $vaisseauFantome['prix'] ?></p>
                    </section>
                <?php endforeach ?>
                </div>
            </section>
        </section>
    </section>
</body>
</html>