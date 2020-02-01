<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    $user_login=$_SESSION['user_login'];
    $user_id=$_SESSION['user_id'];
} else {
    header('Location: index.php');
    exit;
}

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
                <?php endif?>
            </section>
        </section>
    </section>
</body>
</html>