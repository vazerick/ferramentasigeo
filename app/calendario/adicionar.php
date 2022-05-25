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
require_once '../../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}


function submit(){
    var_dump($_POST);
    $evento =  array();
    $ErrorArrays = array();
    if (empty($_POST["titulo_evento"])) {
        $ErrorArrays[] = "Preencha o campo Título.";
    } else {
        $evento["titulo"] = $_POST["titulo_evento"];
    }
    if (empty($_POST["descricao"])) {
        $evento["descricao"] = null;
    } else {
        $evento["descricao"] = $_POST["descricao"];
    }
    if (empty($_POST["data_inicio"])) {
        $ErrorArrays[] = "Preencha o campo Data Início.";
    } else {
        $evento["data_inicio"] = $_POST["data_inicio"];
    }
    var_dump($evento);

    if (count($ErrorArrays) == 0) {
        echo "OK";
    } else {
        foreach ($ErrorArrays as $Errors) {
            echo "<p style='color:red'><b>" . $Errors . "</p></b><br>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    submit();
}



?>

<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <style>
        /*input, textarea{*/
        /*    width: 100%;*/
        /*}*/
    </style>
    <meta charset='utf-8' />
</head>
<body>

<div>
    </br></br>
    <h1>Adicionar novo evento</h1>
    </br>
    <form action="" method="post">
        <div>
            <label for="equipe">Equipe:</label>
            <select id="equipe" name="equipe">
                <option>
                    Acadêmico
                </option>
            </select>
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo">
                <option>
                    Interno
                </option>
            </select>
        </div>
        <div>
            <label for="titulo_evento">Título</label>
            <input type="text" id="titulo_evento" name="titulo_evento" style="width: 100%">
        </div>
        <div>

        </div>
        <div>
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" style="width: 100%"></textarea>
        </div>
        <div>
            <label for="allday">Dia inteiro</label>
            <input type="checkbox" id="allday" name="allday">
        </div>
        <div>
            <table style="width: auto">
                <tr>
                    <td>
                        <label for="data_inicio">Data de início:</label>
                    </td>
                    <td>
                        <input type="date" id="data_inicio" name="data_inicio">
                    </td>
                    <td style="width: 5%"></td>
                    <td>
                        <label for="hora_inicio">Horário de início:</label>
                    </td>
                    <td>
                        <input type="time" id="hora_inicio" name="hora_inicio" value="00:01">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="data_fim">Data de fim:</label>
                    </td>
                    <td>
                        <input type="date" id="data_fim" name="data_fim">
                    </td>
                    <td></td>
                    <td>
                        <label for="hora_fim">Horário de fim:</label>
                    </td>
                    <td>
                        <input type="time" id="hora_fim" name="hora_fim" value="23:59">
                    </td>
                </tr>
            </table>
        </div>
        <div style="float: right;">
            <input type="submit" value="Enviar">
        </div>
    </form>
</div>


</body>
</html>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>

