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

function listar_equipes()
{
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

function escreve_linha($array)
{
    foreach ($array as $coluna) {
        echo "<td>" . $coluna . "</td>";
    }
}

function escreve_data($data, $formato)
{
    $date = strtotime($data);
    return date($formato, $date);
}

function console_log($data, $texto = "")
{
    echo '<script>';
    if ($texto != "") {
        echo 'console.log("' . $texto . '");';
    }
    echo 'console.log(' . json_encode($data) . ');';
    echo '</script>';
}

function diff_dias($data1, $data2)
{
    $diff = strtotime(substr($data1, 0, 10)) - strtotime(substr($data2, 0, 10));
    $diff = (round($diff / 86400));
    return $diff;
}

function tinymce($titulo, $conteudo, $pastas, $setores){
    $id = "texto";
    echo "<!DOCTYPE html>";
    echo '<script src="tinymce/tinymce.min.js" referrerpolicy="origin"></script>';
    echo '<script>';
    echo 'tinymce.init({';
    echo "selector: '#" . $id . "',";
    echo "menubar: 'edit view insert format',";
    echo "toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist | indent outdent',";
    echo "language: 'pt_BR',";
    echo "plugins: 'autosave searchreplace lists',";
    echo "autosave_interval: '30s'";
    echo "});";
    echo '</script>';
    echo '';
    echo '<h1>' . $titulo . '</h1>';
    echo '<form method="post">';
    echo '<div class="row">';
    echo '<label class="col-1" for="titulo">Título: </label>' ;
    echo '<input type="text" name="titulo" value="' . $titulo . '" class="form-control col" style="margin-bottom:1em;">';
    echo '<label class="col-1" for="setor">Setor: </label>' ;
    echo '<select name="setor" id="setor" class="form-control col" style="margin-bottom:1em;">';
    foreach ($setores as $setor) {
        echo '<option value="' . $setor["id"] . '">' . $setor["nome"] . '</option>';
    }
    echo '</select>';
    echo '<label class="col-1" for="pasta">Pasta: </label>' ;
    echo '<select name="pasta" id="pasta" class="form-control col" style="margin-bottom:1em;">';
    echo '<option value="0"> SEM PASTA </option>';
    foreach ($pastas as $pasta) {
        echo '<option value="' . $pasta["id"] . '">' . $pasta["titulo"] . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '<div class="row">';
    echo '<div class="col">';
    echo '<textarea id='. $id . ' name=' . $id . '>' .$conteudo . '</textarea>';
    echo '</div>';
    echo '</div>';
    echo '<input type="submit" class="rapid_contact btn btn-primary button" value="Salvar" style="margin-top: 1em; margin-bottom: 1em">';
    echo '</form>';
}