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

$vaisseauAdverse=null;
$vaisseauUser=null;
if (!empty($_GET['fight'])){
    $idJoueurAdverse=(int)test_input($_GET['fight']);

    $sql=
        'SELECT loginJoueur,niveau,idVaisseau,nomVaisseau,rapidite,attaque,solidite,defense
        FROM joueurs_vaisseaux
        NATURAL JOIN joueurs
        NATURAL JOIN vaisseaux
        WHERE activite=1
        AND idJoueur=:id_joueur_adverse';
    ;
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id_joueur_adverse',$idJoueurAdverse);
    $stmt->execute();
    $vaisseauxAdversesDispos=$stmt->fetchAll(PDO::FETCH_OBJ);
    // dump('vaisseauxAdvDispo',$vaisseauxAdversesDispos);
    // min 1 vaisseau disponible
    if (count($vaisseauxAdversesDispos)!=0){
        // 1 vaisseau disponible
        if (count($vaisseauxAdversesDispos)==1){
            $vaisseauAdverse=$vaisseauxAdversesDispos[0];
        // plus d'un vaisseau disponible
        } else {
            shuffle($vaisseauxAdversesDispos);
            $vaisseauAdverse=$vaisseauxAdversesDispos[0];
        }
        
        $sql=
        'SELECT loginJoueur,niveau,idVaisseau,nomVaisseau,rapidite,attaque,solidite,defense
        FROM joueurs_vaisseaux
        NATURAL JOIN joueurs
        NATURAL JOIN vaisseaux
        WHERE activite=1
        AND idJoueur=:user_id';
        ;
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->execute();
        $vaisseauxUserDispos=$stmt->fetchAll(PDO::FETCH_OBJ);
        // dump('vaisseauxAdvDispo',$vaisseauxAdversesDispos);
        // min 1 vaisseau disponible
        if (count($vaisseauxUserDispos)!=0){
            // 1 vaisseau disponible
            if (count($vaisseauxUserDispos)==1){
                $vaisseauUser=$vaisseauxUserDispos[0];
            // plus d'un vaisseau disponible
            } else {
                shuffle($vaisseauxUserDispos);
                $vaisseauUser=$vaisseauxUserDispos[0];
            }
        }
    } else {
    // pas de vaisseaux adverses disponible, erreur
    }
}
// dump('vaisseau user',$vaisseauUser);
// dump('vaisseau adv',$vaisseauAdverse);
// die;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Champ de bataille</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/battleground.css">
</head>
<body>
    <?php require_once('./templates/_nav.html') ?>

    <section id="container">
        <h1>Nouvelles du champ de bataille</h1>
        <?php
        // stats vaisseau USER
        $vieUser=1000;
        $bouclierUser=$vaisseauUser->defense;
        $defUser=True;
        // stats vaisseau adverse
        $vieAdv=1000;
        $bouclierAdv=$vaisseauAdverse->defense;
        $defAdv=True;

        // premier attaquant
        $user=rand(3,10);
        $adversaire=rand(1,8);

        // user attaque le premier
        if ($user>=$adversaire){
            $bouclierAdv-=$vaisseauUser->attaque;
            echo '<p>Bien joué ! Le vaisseau ennemi ne vous a pas détecté, vous attaquez le premier !</p>';
            if ($bouclierAdv>0){
                echo "<p>Le bouclier ennemi a pris $vaisseauUser->attaque points de dégats ($bouclierAdv restant(s))";
            } else {
                $defAdv=False;
                echo 'La défense ennemie est complètement détruite ! Youhouuu ! Plus que cette satannée coque !';
            }
        // adversaire attaque le premier
        } else {
            $bouclierUser-=$vaisseauAdverse->attaque;
            echo '<p>Le vaisseau ennemi vous a totalement vu arriver ! Il vous a attaqué !</p>';
            if ($bouclierUser>0){
                echo "<p>Votre défense a pris $vaisseauAdv->attaque points de dégats ($bouclierUser restant(s))";
            } else {
                $defUser=False;
                echo '<p>Eulah ! Votre bouclier vole en éclats magnétiques ! Vous ne vous sentez plus très bien là...</p>';
            }
        }

        // combat jusqu'à vainqueur
        while ($vieAdv>0 && $vieUser>0){
            // attaquer bouclier adverse
            if ($defAdv){
                $bouclierAdv-=$vaisseauUser->attaque;
                // bouclier resiste
                if ($bouclierAdv>0){
                    echo "<p>Le bouclier ennemi a pris $vaisseauUser->attaque points de dégats ($bouclierAdv restant(s))";
                // bouclier détruit
                } else {
                    $defAdv=False;
                    echo '<p>La défense ennemie est complètement détruite ! Youhouuu ! Plus que cette satannée coque !</p>';
                }
            }
            // attaquer vaisseau adverse
            else {
                $vieAdv-=$vaisseauUser->attaque;
                // vaisseau résiste
                if ($vieAdv>0){
                    echo "<p>Le vaisseau ennemi a pris $vaisseauUser->attaque points de dégats ($vieAdv restant(s))";
                // vaisseau trop endommagé
                } else {
                    echo 'C\'est une Victoire !';
                    echo "<p>Le vaisseau ennemi est salement amoché, il repart en boitant... je crois avoir vu le capitaine 
                    $vaisseauAdverse->nomLogin verser une larme...</p>";
                } 
            }
            // attaquer bouclier user
            if ($defUser){
                // bouclier résiste
                $bouclierUser-=$vaisseauAdverse->attaque;
                if ($bouclierUser>0){
                    echo "<p>Votre bouclier a pris $vaisseauAdverse->attaque points de dégats ($bouclierUser restant(s))";
                // bouclier détruit
                } else {
                    $defUser=False;
                    echo '<p>L\'ennemi a brisé votre faible défense ! Vous voyez votre vie défiler sous vos yeux !</p>';
                }
            }
            // attaquer vaisseau user
            else {
                $vieUser-=$vaisseauAdverse->attaque;
                // $vaisseau résiste
                if ($vieUser>0){
                    echo "<p>Votre vaisseau a pris $vaisseauAdverse->attaque points de dégats ($vieAdv restant(s))";
                // $vaisseau trop endommagé
                } else {
                    echo '<p>C\'est une défaite !</p>';
                    echo "<p>Votre vaisseau fait peine à voir... Vous imaginez déjà tagué sur votre vaisseau: $vaisseauUser->loginJoueur la victime...</p>";
                } 
            }
        }

        if ($vieUser > 0) {
            echo "</p>Victoire totale de $user_login sur $vaisseauAdverse->loginJoueur</p>";
        } else {
            echo "</p>Victoire totale de $vaisseauAdverse->loginJoueur sur $user_login</p>";
        }
        ?>
    </section>
</body>
</html>