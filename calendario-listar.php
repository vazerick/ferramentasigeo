<?php
require_once 'users/init.php';

//$lista_grupos = array();
//parse_str($_GET["info"], $lista_grupos);

$lista_id = explode(";", $_GET["grupos"]);

$events = array();

$db->query("SELECT * FROM calendario");
foreach ($db->results() as $row) {

    if (in_array($row->grupo, $lista_id)) {
        $e = array();
        $e['id'] = $row->id;
        $e['title'] = $row->title;
        $e['start'] = $row->start;
        $e['end'] = $row->end;
        $e['allDay'] = boolval($row->allDay);
        $e['extendedProps']['description'] = $row->descr;
        $e['grupo'] = $row->grupo;
        $e['url'] = "calendario-visualizar.php?id=" . $row->id;
        if ($row->tipo == 1) {
            $e['backgroundColor'] = "#493b2c";
        } else {
            $e['backgroundColor'] = "#3788d8";
            $e['backgroundColor'] = cor_equipe($row->grupo);
        }
        if ($e['allDay']) {
            $temp_inicio = escreve_data($e['start'], "Y-m-d");
            $temp_fim = escreve_data($e['end'], "Y-m-d");
            if ($temp_inicio != $temp_fim) {
                $e['end'] = date('Y-m-d', strtotime($temp_fim . ' + 1 days'));
            }
        }
        $events[] = $e;
    }

}

echo json_encode($events);
//dump(hsltorgb(209.8, 67.4, 53.1));
