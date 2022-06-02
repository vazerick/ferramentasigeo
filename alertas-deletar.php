<?php
require_once 'users/init.php';


$db->query("SELECT * FROM alertas WHERE id = '" . $_GET["id"] . "'");

dump($db->results());


if ((count($db->results()) == 0)) {
    echo "ALERTA NÃO LOCALIZADO";
    die();
}

$alerta = $db->results()[0];

if ($user->data()->id != $alerta->usuario) {
    echo "USUÁRIO NÃO AUTORIZADO PARA VER ESTE ALERTA";
    die();
}

$db->deleteById("alertas", $alerta->id);
header('Location: alertas.php');