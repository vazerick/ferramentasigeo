<?php
require_once 'users/init.php';


$db->query("SELECT * FROM calendario WHERE id = '" . $_GET["id"] . "'");

if ((count($db->results()) == 0)) {
    echo "EVENTO NÃO LOCALIZADO";
    die();
}

$evento = $db->results()[0];

if (!(hasPerm($evento->grupo))) {
    echo "USUÁRIO NÃO AUTORIZADO PARA VER ESTE EVENTO";
    die();
}

dump($evento);

$db->deleteById("calendario", $evento->id);
logger($user->data()->id, 'Calendário', 'Deletado evento ' . $evento->id);
header('Location: calendario-externo-listar.php?setor=' . $_GET["setor"] . '&lastid=0');
