<?php
//fonction pour sécuriser les données
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// var_dump amélioré
function dump($label,$data){
    echo '<h1>'.$label.'</h1>';
    echo '<pre>';
    var_dump($data);
    echo '</pre><br>';
}