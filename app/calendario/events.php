<?php
require_once '../../users/init.php';

$db->query("SELECT * FROM calendario");

$events = array();

foreach ($db->results() as $row) {

    if (hasPerm($row->group)){
        $e = array();
        $e['id'] = $row->id;
        $e['title'] = $row->title;
        $e['start'] = $row->start;
        $e['end'] = $row->end;
        $e['allDay'] = boolval($row->allDay);
        $e['extendedProps']['description'] = $row->descr;
        $e['group'] = $row->group;

        // Merge the event array into the return array
        array_push($events, $e);
    }

}

echo json_encode($events);