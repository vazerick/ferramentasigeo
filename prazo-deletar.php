<?php
require_once 'users/init.php';


$db->query("SELECT * FROM prazos WHERE id = '" . $_GET["id"] . "'");

if((count($db->results()) == 0)){
    echo "PRAZO NÃO LOCALIZADO";
    die();
}

$prazo = $db->results()[0];

if(!(hasPerm($prazo->grupo))){
    echo "USUÁRIO NÃO AUTORIZADO PARA VER ESTE PRAZO";
    die();
}

dump($prazo);

$db->deleteById("prazos", $prazo->id);
header('Location: prazos.php');