<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    $user_login=$_SESSION['user_login'];
    $user_id=$_SESSION['user_id'];
} else {
    header('Location: index.php');
    exit;
}

include_once('../includes/constants.php');
require_once('../includes/functions.php');
require_once('../includes/connect_infos.php');
require_once('../includes/connect_base.php');
// traitement méthode POST
$erreur=[];
if ($_SERVER['REQUEST_METHOD']=='POST'){
    if (!empty($_POST['submit'])){
        $submit=test_input($_POST['submit']);
        
        // traitement modification login
        if ($submit=="replace_login"){
            if (!empty($_POST['new_login'])){
                $newLogin=test_input($_POST['new_login']);
                // nouveau login entre 5 et 30 caractères
                if (mb_strlen($newLogin) >= 5 && mb_strlen($newLogin) <= 30){
                    $sql=
                        'UPDATE joueurs
                        SET loginJoueur=:1
                        WHERE idJoueur=:2'
                    ;
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':1',$newLogin);
                    $stmt->bindParam(':2',$user_id);
                    $stmt->execute();

                    $_SESSION['user_login']=$newLogin;
                    $user_login=$newLogin;
                } else {
                // login trop long ou trop court
                $erreur['longueur']='Le login doit comprendre de 5 à 30 caractères';
                }
            } else {
            // champ nouveau login vide
            $erreur['champ_vide']='Remplir le champ pour modifier le login';
            }
        } // traitement modification login
        elseif ($submit=="replace_pass"){
            if (!empty($_POST['new_pass'])){
                $newPass=test_input($_POST['new_pass']);
                // nouveau pass entre 7 et 15 caractères
                if (mb_strlen($newPass) >= 7 && mb_strlen($newPass) <= 15){
                    $passwordHash=password_hash($newPass, PASSWORD_DEFAULT, ['cost' => 12]);

                    $sql=
                        'UPDATE joueurs 
                        SET motPasse=:new_pass
                        WHERE idJoueur=:id_joueur';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':new_pass', $passwordHash);
                    $stmt->bindParam(':id_joueur', $user_id);
                    $stmt->execute();
                } else {
                // pass trop long ou trop court
                $erreur['longueur_pass']='Le mot de passe doit comprendre de 7 à 15 caractères';
                }
            } else {
            // champ nouveau pass vide
            $erreur['champ_vide_pass']='Remplir le champ pour modifier le mot de passe';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Paramètres</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/homepage.css">
</head>
<body>

    <?php 
    require_once('../includes/connect_infos.php');
    require_once('../includes/connect_base.php');
    require_once('./templates/_nav.html'); ?>

    <section id="container">
        <h1>Paramètres</h1>
        <section>
            <h2>Modification des paramètres utilisateurs</h2>
            <!-- modification login -->
            <section>
                <h3>Modifier le login</h3>
                <form action="" method="POST">
                    <input type="text" name="new_login" placeholder="Nouveau login">
                    <button type="submit" name="submit" value="replace_login">Confirmer</button>
                </form>
                <?php if(!empty($erreur['longueur'])): ?>
                <p><?=$erreur['longueur']?></p>
                <?php elseif(!empty($erreur['champ_vide'])): ?>
                <p><?=$erreur['champ_vide']?></p>
                <?php endif?>
            </section>
            <section>
                <h3>Modifier le mot de passe</h3>
                <form action="" method="POST">
                    <input type="password" name="new_pass" placeholder="Nouveau mot de passe">
                    <button type="submit" name="submit" value="replace_pass">Confirmer</button>
                </form>
                <?php if(!empty($erreur['longueur_pass'])): ?>
                <p><?=$erreur['longueur_pass']?></p>
                <?php elseif(!empty($erreur['champ_vide_pass'])): ?>
                <p><?=$erreur['champ_vide_pass']?></p>
                <?php endif?>
            </section>
        </section>
    </section>
</body>
</html>