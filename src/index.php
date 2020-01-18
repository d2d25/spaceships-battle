<?php 
include_once('../includes/constants.php');
include_once('../includes/functions.php');

$errors=[];
// traitement formulaires si methode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // traitement form sign up
    if (isset($_POST['sign_up'])) {
        if (!empty($_POST['user_login'])) {
            $userLogin = test_input($_POST['user_login']);
            if (mb_strlen($userLogin) < 5 || mb_strlen($userLogin) > 30) {
                $errors['user_login'] = 'De 5 à 30 caractères';
            }
        } else {
            $errors['user_login'] = 'Ne pas laisser vide';
        }
        if (!empty($_POST['user_password'])) {
            $userPassword = test_input($_POST['user_password']);
            if (mb_strlen($userPassword) < 7 || mb_strlen($userPassword) > 15) {
                $errors['user_password'] = 'De 7 à 15 caractères';
            }
        } else {
            $errors['user_password'] = 'Ne pas laisser vide';
        }
    // traitement form sign in
    } elseif (isset($_POST['sign_in'])) {
        echo'Sign in';
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NAME ?>: Accueil</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <section>
        <h1>Bienvenue sur <?= SITE_NAME ?></h1>
        <section>
            <h2>Je n'ai pas encore de compte :</h2>
            <form action="" method="POST">
                <input type="text" name="user_login" placeholder="JeanClaudeDuss">
                <?php if (!empty($errors['user_login'])): ?>
                    <p><?= $errors['user_login'] ?></p>
                <?php endif ?>
                <input type="password" name="user_password">
                <?php if (!empty($errors['user_password'])): ?>
                    <p><?= $errors['user_password'] ?></p>
                <?php endif ?>
                <input type="submit" name="sign_up" value="M'inscrire">
            </form>
        </section>
        <section>
            <h2>A l'attaque !</h2>
            <form action="" method="POST">
                <input type="text" name="login" placeholder="JeanClaudeDuss">
                <input type="password" name="password">
                <input type="submit" name="sign_in" value="C'est parti">
            </form>
        </section>
    </section>
</body>
</html>
