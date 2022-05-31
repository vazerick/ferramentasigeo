<?php
require_once '../../users/init.php';

$db->query("SELECT * FROM calendario WHERE grupo = '" . $_GET["setor"] . "'");

$events = array();

foreach ($db->results() as $row) {

    if (hasPerm($row->grupo)){
        if($row->tipo == 1) {
            $e = array();
            $e['id'] = $row->id;
            $e['title'] = $row->title;
            $e['start'] = $row->start;
            $e['end'] = $row->end;
            $e['allDay'] = boolval($row->allDay);
            $e['extendedProps']['description'] = $row->descr;
            $e['grupo'] = $row->grupo;
            // Merge the event array into the return array
            array_push($events, $e);
        }
    }

}
$myfile = fopen("list"  . $_GET["setor"] .  ".json", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($events));
echo json_encode($events);
