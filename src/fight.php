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

    <?php 
    require_once('../includes/connect_infos.php');
    require_once('../includes/connect_base.php');
    require_once('./templates/_nav.html'); ?>

    <section id="container">
        <h1>Combats</h1>
    </section>
</body>
</html>