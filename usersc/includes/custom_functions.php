<?php
/*
UserSpice 5
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//Put your custom functions in this file and they will be automatically included.

//bold("<br><br>custom helpers included");

function listar_equipes(){
    global $db;
    $db->query("SELECT * FROM permissions");
    $Equipes = array();
    foreach ($db->results() as $row) {
        if (hasPerm($row->id)) {
            $item = array();
            $item['id'] = $row->id;
            $item['nome'] = $row->name;
            $Equipes[] = $item;
        }
    }
    return $Equipes;
}

function escreve_linha($array){
    foreach ($array as $coluna){
        echo "<td>" . $coluna . "</td>";
    }
}

function escreve_data($data, $formato){
    $date = strtotime($data);
    return date($formato, $date);
}

function console_log( $data , $texto=""){
    echo '<script>';
    if($texto != ""){
        echo 'console.log("'. $texto .'");';
    }
    echo 'console.log('. json_encode( $data ) .');';
    echo '</script>';
}