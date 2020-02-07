<?php
/* Connexion à une base MySQL avec l'invocation de pilote */
include "connect_infos.php";
try {
    $pdo = new PDO("mysql:host=$server;dbname=$base", $user, $mdp);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<script>console.log("connexion reussie")</script>';
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}
