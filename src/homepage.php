<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    $user_id=$_SESSION['user_id'];
} else {
    header('Location: index.php');
    exit;
}

include_once('../includes/constants.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Journal de bord</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/homepage.css">
</head>
<body>

    <?php 
    require_once('../includes/connect_infos.php');
    require_once('../includes/connect_base.php');
    require_once('./templates/_nav.html'); ?>

    <section id="container">
        <h1>Accueil</h1>
        <div>
            <section id="stats-user">
                <h2>Statistiques de jeu</h2>
                <?php
                // statistiques jeu USER
                $stmt = $pdo->prepare("SELECT argent,niveau,experience,nbPointsReparation FROM joueurs WHERE idJoueur=:user_id");
                $stmt->bindParam(':user_id', $user_id);

                $statsJoueur=[];
                if ($stmt->execute()) {
                    $statsJoueur=$stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                ?>
                <p>Joueur: <?= $user_id ?></p>
                <p>Niveau: <?= $statsJoueur[0]['niveau'] ?></p>
                <p>Argent: <?= $statsJoueur[0]['argent'] ?></p>
                <p>Expérience: <?= $statsJoueur[0]['experience'] ?></p>
                <p>Points de réparation: <?= $statsJoueur[0]['nbPointsReparation'] ?></p>
            </section>
            <section id="new-features">
                <h2>Nouveautés</h2>
                <ul>
                    <li>Achetez votre premier vaisseau dans l'onglet acheter du menu !</li>
                    <li>Connectez vous 3 jours d'affilés pour débloquer des points de réparation supplémentaires !</li>
                    <li>Rendre votre nouveau vaisseau disponible pour partir en mission en modifiant son activité !</li>
                    <li>Réparez votre vaisseau en échange de points de réparation !</li>
                </ul>
            </section>
        </div>
        <section id="owned-spaceships">
            <h2>Vaisseaux possédés</h2>
            <?php
            $sql=
                'SELECT lienImage,joueurs_vaisseaux.idVaisseau,idType,nbVictoires,nbDefaites,dommages,activite
                FROM joueurs_vaisseaux
                LEFT JOIN vaisseaux
                    ON joueurs_vaisseaux.idVaisseau = vaisseaux.idVaisseau
                    WHERE idJoueur=:user_id
                    AND possede=1'
            ;

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);

            $vaisseaux=[];
            if ($stmt->execute()) {
                $vaisseaux=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            ?>
            <table>
                <tr>
                    <th>Vaisseau</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Victoires</th>
                    <th>Défaites</th>
                    <th>Dommages</th>
                    <th>Activité</th>
                </tr>
                <?php foreach ($vaisseaux as $vaisseau): ?>
                <tr>
                    <td><img src="<?= $vaisseau['lienImage'] ?>" width="100px"/></td>
                    <td><?= $vaisseau['idVaisseau'] ?></td>
                    <td><?= $vaisseau['idType'] ?></td>
                    <td><?= $vaisseau['nbVictoires'] ?></td>
                    <td><?= $vaisseau['nbDefaites'] ?></td>
                    <td><?= $vaisseau['dommages'] ?></td>
                    <td><?= $vaisseau['activite'] ?></td>
                </tr>
                <?php endforeach ?>
            </table>
        </section>
    </section>
</body>
</html>