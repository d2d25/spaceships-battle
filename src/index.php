<?php 
include_once('../includes/constants.php');
include_once('../includes/functions.php');

// traitement formulaires si methode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // traitement form sign up
    if (isset($_POST['sign_up'])) {
        if (!empty($_POST['user_login']) && !empty($_POST['user_password'])) {
            $userLogin = test_input($_POST['user_login']);
            $userPassword = test_input($_POST['user_password']);

            // enregistrement en base
        } else {
            // creer messages d'erreurs
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
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <section>
        <h1>Bienvenue sur <?= SITE_NAME ?></h1>
        <section>
            <h2>Je n'ai pas encore de compte :</h2>
            <form action="" method="POST">
                <input type="text" name="user_login" placeholder="JeanClaudeDuss">
                <input type="password" name="user_password">
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
