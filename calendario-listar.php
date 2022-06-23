<?php
require_once 'users/init.php';

//$lista_grupos = array();
//parse_str($_GET["info"], $lista_grupos);

$lista_id = explode(";", $_GET["grupos"]);

$events = array();

function hsltorgb ($h, $s, $l) {

    $h /= 60;
    if ($h < 0) $h = 6 - fmod(-$h, 6);
    $h = fmod($h, 6);

    $s = max(0, min(1, $s / 100));
    $l = max(0, min(1, $l / 100));

    $c = (1 - abs((2 * $l) - 1)) * $s;
    $x = $c * (1 - abs(fmod($h, 2) - 1));

    if ($h < 1) {
        $r = $c;
        $g = $x;
        $b = 0;
    } elseif ($h < 2) {
        $r = $x;
        $g = $c;
        $b = 0;
    } elseif ($h < 3) {
        $r = 0;
        $g = $c;
        $b = $x;
    } elseif ($h < 4) {
        $r = 0;
        $g = $x;
        $b = $c;
    } elseif ($h < 5) {
        $r = $x;
        $g = 0;
        $b = $c;
    } else {
        $r = $c;
        $g = 0;
        $b = $x;
    }

    $m = $l - $c / 2;
    $r = round(($r + $m) * 255);
    $g = round(($g + $m) * 255);
    $b = round(($b + $m) * 255);
    return sprintf("#%02x%02x%02x", $r, $g, $b);
//    return ['r' => $r, 'g' => $g, 'b' => $b];

}

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
            $j = 0;
            $e['backgroundColor'] = "#3788d8";
            if($row->grupo % 2 == 0){
                $j = -1.3;
            }
            else{
                $j = 1;
            }
            $h = 209.8 + (139.87 * $j * (($row->grupo-3)/2));
            $e['backgroundColor'] = hsltorgb($h, 67.4, 35);
        }
        $events[] = $e;
    }

}

echo json_encode($events);
//dump(hsltorgb(209.8, 67.4, 53.1));
