<?php
session_start();
if (!empty($_SESSION['user_id']) && $_GET['session-off']){
    session_destroy();
    session_start();
} elseif (!empty($_SESSION['user_id'])) {
    header('Location: homepage.php?identified=1');
    exit;
}

include_once('../includes/constants.php');
include_once('../includes/functions.php');

$errors=[];
$savedContent['user_login']=null;
$savedContent['login']=null;
// traitement formulaires si methode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // traitement form sign up
    if (isset($_POST['sign_up'])) {
        $userLogin=null;
        if (!empty($_POST['user_login'])) {
            $userLogin = test_input($_POST['user_login']);
            // champ user_login incorrecte
            if (mb_strlen($userLogin) < 5 || mb_strlen($userLogin) > 30) {
                $errors['user_login'] = 'De 5 à 30 caractères';
            } else {
                $savedContent['user_login']=$userLogin;
            }
        } else {
            // champ user_login vide
            $errors['user_login'] = 'Ne pas laisser vide';
        }
        if (!empty($_POST['user_password'])) {
            $userPassword = test_input($_POST['user_password']);
            // champ user_password incorrecte
            if (mb_strlen($userPassword) < 7 || mb_strlen($userPassword) > 15) {
                $errors['user_password'] = 'De 7 à 15 caractères';
            }
        } else {
            // champ user_password vide
            $errors['user_password'] = 'Ne pas laisser vide';
        }
        // enregistrement du compte en base
        if (count($errors) == 0) {
            // connexion base
            require_once('../includes/connect_infos.php');
            require_once('../includes/connect_base.php');

            $passwordHash=password_hash($userPassword, PASSWORD_DEFAULT, ['cost' => 12]);

            $stmt = $pdo->prepare("INSERT INTO joueurs (idJoueur, motPasse) VALUES (:name, :value)");
            $stmt->bindParam(':name', $userLogin);
            $stmt->bindParam(':value', $passwordHash);
            if ($stmt->execute()) {
                // redirection page personnelle
                $_SESSION['user_id']=$userLogin;
                // var_dump('ok'); die;
                header('Location: homepage.php?identified=1');
                // var_dump('ok1'); die;
                exit;
            }
        } 
    // traitement form sign in
    } elseif (isset($_POST['sign_in'])) {
        $password=null;
        $login=null;
        if (!empty($_POST['login'])) {
            $login = test_input($_POST['login']);
            $savedContent['login']=$login;
        } else {
            // champ login vide
            $errors['login'] = 'Saisir votre identifiant';
        }
        if (!empty($_POST['password'])) {
            $password = test_input($_POST['password']);
            if ($login==null) {
                $errors['password'] = 'Saisir votre mot de passe';
            }
        } else {
            // champ password vide
            $errors['password'] = 'Saisir votre mot de passe';
        }
        // enregistrement du compte en base
        if (count($errors) == 0) {
            // connexion base
            require_once('../includes/connect_infos.php');
            require_once('../includes/connect_base.php');

            $stmt = $pdo->prepare("SELECT motPasse FROM joueurs WHERE idJoueur=:login");
            $stmt->bindParam(':login', $login);
            if ($stmt->execute()) {
                $passwordHash=$stmt->fetch(PDO::FETCH_ASSOC)['motPasse'];
                if (!empty($passwordHash)) {
                    if (password_verify($password,$passwordHash)) {
                        // redirection page personnelle
                        $_SESSION['user_id']=$login;
                        header('Location: homepage.php?identified=1');
                        exit;
                    } else {
                        $errors['password']='Mot de passe incorrect';
                    }
                } else {
                    $errors['login']='Login incorrect';
                }
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
    <title><?= SITE_NAME ?>: Accueil</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <section>
        <h1>Bienvenue sur <?= SITE_NAME ?></h1>
        <section>
            <h2>Je n'ai pas encore de compte :</h2>
            <form action="" method="POST">
                <input type="text" name="user_login" placeholder="JeanClaudeDuss" value="<?= !empty($savedContent['user_login']) ? $savedContent['user_login']:''; ?>">
                <?= !empty($errors['user_login']) ? '<p>'.$errors['user_login'].'</p>': null; ?>
                <input type="password" name="user_password">
                <?= !empty($errors['user_password']) ? '<p>'.$errors['user_password'].'</p>': null; ?>
                <input type="submit" name="sign_up" value="M'inscrire">
            </form>
        </section>
        <section>
            <h2>A l'attaque !</h2>
            <form action="" method="POST">
                <input type="text" name="login" placeholder="JeanClaudeDuss" value="<?= !empty($savedContent['login']) ? $savedContent['login']:''; ?>">
                <?= !empty($errors['login']) ? '<p>'.$errors['login'].'</p>': null; ?>
                <?= !empty($errors['user_not_found']) ? '<p>'.$errors['user_not_found'].'</p>': null; ?>
                <input type="password" name="password">
                <?= !empty($errors['password']) ? '<p>'.$errors['password'].'</p>': null; ?>
                <input type="submit" name="sign_in" value="C'est parti">
            </form>
        </section>
    </section>
</body>
</html>
