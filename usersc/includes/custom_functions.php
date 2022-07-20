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
    $db->query("SELECT * FROM permissions ORDER BY `name`");
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

function escreve_data($data, $formato, $escreve_semana = false)
{
    $texto = "";
    $date = strtotime($data);
    if ($escreve_semana){
        $diasemana = array('dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sáb');
        $diasemana_numero = date('w', strtotime($data));
        $texto = date($formato, $date) . " (" . $diasemana[$diasemana_numero] . ")";
    }else{
        $texto = date($formato, $date);
    }
    return $texto;
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
    echo "toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist | indent outdent | image',";
    echo "language: 'pt_BR',";
    echo "plugins: 'autosave searchreplace lists table wordcount image',";
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

function cor_equipe($id)
{
    $j = 0;

    if($id % 2 == 0){
        $j = -1.3;
    }
    else{
        $j = 1;
    }

    $k = floor($id/10);
    if ($k == 0) {
        $k = 139.87;
        $i = 209.8;

    } else {
        $i = 0;
        $k = $k * 13;
    }

    $h = 209.8 + ($k * $j * (($id-3)/2));

    $j = 1;

    $conts = floor($id/8);

    if($conts % 2 == 0){
        $j = -1;
    }
    else{
        $j = 1;
    }

    $s = 67.4 + (15 * $j * (($conts)/2));

    while ($s > 100){
        $s = $s - 50;
    }
    while ($s < 5){
        $s = 50 + $s;
    }

    $j = 1;

    $contl = floor($id/3);

    if($contl % 2 == 0){
        $j = -1.3;
    }
    else{
        $j = 1;
    }

    $l = 35 + (5 * $j * (($contl)/2));

    while ($l > 75){
        $l = $l - 50;
    }
    while ($l < 10){
        $l = 50 + $l;
    }
    return hsltorgb($h, $s, $l);
}

function legenda($extra = null)
{
    if(!is_null($extra)){
        echo '<div class="col-2" style="color:#fff; background-color:' . $extra["cor"] . '">';
        echo $extra["texto"];
        echo '</div>';
    }
    $equipes = listar_equipes();
    if (count($equipes) > 1 or !is_null($extra)){
        foreach ($equipes as $item) {
            if (!($item["id"] == 1 or $item["id"] == 2)) {
                echo '<div class="col-2" style="color:#fff; background-color:' . cor_equipe($item["id"]) . '">';
                echo $item["nome"];
                echo '</div>';
            }
        }
    }
}

function lista_comissoes()
{
    global $db;
    $db->query("SELECT id FROM pages WHERE page='comissao.php'");
    $idpag = $db->results()[0]->id;
    $db->query("SELECT permission_id FROM permission_page_matches WHERE page_id='" . $idpag . "' AND NOT permission_id='2' ");
    $idperm = array();
    foreach ($db->results() as $item){
        $idperm[] = $item->permission_id;
    }
    $comissoes = array();
    $db->query("SELECT * FROM permissions WHERE id IN (" . implode(", ", $idperm) . ")");
    foreach ($db->results() as $item){
        $e["id"] = $item->id;
        $e["nome"] = $item->name;

        $db->query("SELECT user_id FROM user_permission_matches WHERE permission_id='" . $item->id . "'");
        if (count($db->results()) > 0){
            $lista = array();
            foreach ($db->results() as $pessoa){
                $db->query("SELECT * FROM users WHERE id='" . $pessoa->user_id . "'");
                $lista[] = $db->results()[0]->fname . " " . $db->results()[0]->lname;
            }
            sort($lista);
            $lista = implode(", ", $lista);
        } else {
            $lista = "";
        }
        $e["membros"] = $lista;

        $comissoes[$item->id] = $e;
    }
    return $comissoes;
}

function email_comissao($id, $assunto, $mensagem)
{
    global $db;

    $db->query("SELECT * FROM comissao_projetos WHERE id = '" . $id . "'");
    $projeto = $db->results()[0];

    $db->query("SELECT * FROM permissions WHERE id = '" . $projeto->id_responsavel . "'");
    $comissao = $db->results()[0];

    $db->query("SELECT * FROM user_permission_matches WHERE permission_id = '" . $comissao->id . "'");
    $lista = $db->results();
    $usuarios = array();

    foreach ($lista as $row){
        $usuarios[$row->user_id] = $row;

        $db->query("SELECT * FROM users WHERE id = '" . $row->user_id . "'");
        $email = $db->results()[0]->email;

        $db->query("SELECT * FROM alertas WHERE usuario = '" . $db->results()[0]->id . "'");
        $alerta = $db->results();
        if(count($alerta)){
            $email = $alerta[0]->email;
        }

        $usuarios[$row->user_id]->email = $email;
    }

    foreach ($usuarios as $item){
        $resultado = email($item->email, $assunto, $mensagem);
        if ($resultado) {
            $resultado = "E-mail enviado";
        } else {
            $resultado = "Erro no envio de e-mail";
        }
        logger("", $resultado, "E-mail de atualização de projeto para " . $item->email . ".");
    }

    return $resultado;

}