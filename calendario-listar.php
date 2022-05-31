<?php
require_once 'users/init.php';

$lista_grupos = array();
parse_str($_GET["info"], $lista_grupos);
$lista_id = array();
foreach ($lista_grupos as $item){
    $lista_id[] = $item["id"];
//    echo "a";
}

$events = array();

$db->query("SELECT * FROM calendario");
foreach ($db->results() as $row) {

    if (in_array($row->grupo, $lista_id)){
        $e = array();
        $e['id'] = $row->id;
        $e['title'] = $row->title;
        $e['start'] = $row->start;
        $e['end'] = $row->end;
        $e['allDay'] = boolval($row->allDay);
        $e['extendedProps']['description'] = $row->descr;
        $e['grupo'] = $row->grupo;
        $e['url'] = "calendario-visualizar.php?id=" . $row->id;
        if($row->tipo == 1){
            $e['backgroundColor'] = "#804c19";
        }
        // Merge the event array into the return array
        $events[] = $e;
    }

}

echo json_encode($events);
