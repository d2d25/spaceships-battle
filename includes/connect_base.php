<?php
/* Connexion à une base MySQL avec l'invocation de pilote */
try {
    $pdo = new PDO(DSN, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}