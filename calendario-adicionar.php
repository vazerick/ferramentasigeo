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
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

$Equipes = listar_equipes();

function submit()
{
    global $db, $user;
    $evento = array();
    $ErrorArrays = array();
    $evento["equipe"] = $_POST["equipe"];
    $evento["tipo"] = $_POST["tipo"];
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
    if (empty($_POST["allday"])) {
        $evento["allday"] = "0";
        $evento["hora_inicio"] = $_POST["hora_inicio"];
        $evento["hora_fim"] = $_POST["hora_fim"];
    } else {
        $evento["allday"] = "1";
        $evento["hora_inicio"] = "00:00";
        $evento["hora_fim"] = "23:59";
    }
    if (empty($_POST["data_inicio"])) {
        $ErrorArrays[] = "Preencha o campo Data Início.";
    } else {
        $evento["data_inicio"] = $_POST["data_inicio"];
    }

    if (empty($_POST["data_fim"])) {
        $ErrorArrays[] = "Preencha o campo Data Fim.";
    } else {
        $evento["data_fim"] = $_POST["data_fim"];
    }


    $evento["inicio"] = $evento["data_inicio"] . " " . $evento["hora_inicio"] . ":00";
    $evento["fim"] = $evento["data_fim"] . " " . $evento["hora_fim"] . ":00";

    if (count($ErrorArrays) == 0) {
        $fields = array(
            "title" => $evento["titulo"],
            "start" => $evento["inicio"],
            "end" => $evento["fim"],
            "allDay" => $evento["allday"],
            "descr" => $evento["descricao"],
            "grupo" => $evento["equipe"],
            "tipo" => $evento["tipo"],
        );
        $db->insert("calendario", $fields);
        $lastid = $db->lastId();
        logger($user->data()->id, 'Calendário', 'Adicionado evento ' . $evento["titulo"]);
        if ($db->error()) {
            echo $db->errorString();
        } else {
//            header('Location: calendario-visualizar.php?id=' . $db->lastId());
            header('Location: calendario-externo-listar.php?setor=' . $evento["equipe"] . '&lastid=' . $lastid);
        }

    } else {
        foreach ($ErrorArrays as $Errors) {
            echo "<p style='color:red'><b>" . $Errors . "</p></b>";
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
    <script>
        function troca_data(data) {
            let fim = document.getElementById("data_fim");
            fim.value = data.value;
            fim.setAttribute("min", data.value);
        }

        function troca_allday(check) {
            let inicio = document.getElementById("hora_inicio");
            let fim = document.getElementById("hora_fim");
            if (check.checked) {
                inicio.setAttribute('disabled', '');
                inicio.value = "00:00"
                fim.setAttribute('disabled', '');
                fim.value = "23:59"
            } else {
                inicio.removeAttribute('disabled');
                fim.removeAttribute('disabled');
            }
        }
    </script>
    <meta charset='utf-8'/>
</head>
<body>

<div>
    <br/><br/>
    <h1>Adicionar novo evento</h1>
    <br/>
    <form action="" method="post">
        <div class="row">
            <div class="col">
                <label for="titulo_evento">Título</label>
                <input class="form-control" type="text" id="titulo_evento" name="titulo_evento" style="width: 100%">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="equipe">Equipe</label>
                <select class="form-control" id="equipe" name="equipe">
                    <?php
                    foreach ($Equipes as $equipe) {
                        echo '<option value="' . $equipe["id"] . '">' . $equipe["nome"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <label for="tipo">Tipo</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="0">Interno</option>
                    <option value="1">Externo</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" style="width: 100%"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col"
            <label for="allday">Dia inteiro</label>
            <input type="checkbox" onchange="troca_allday(this)" id="allday" name="allday">
        </div>
</div>
<div class="container form-group ">
    <div class="row align-items-center">
        <div class="col col-form-label">
            <label for="data_inicio">Data de início:</label>
        </div>
        <div class="col">

            <input class="form-control" type="date" onchange="troca_data(this)" id="data_inicio" name="data_inicio">
        </div>
        <div class="col col-form-label">

            <label for="hora_inicio">Horário de início:</label>
        </div>
        <div class="col">

            <input class="form-control" type="time" id="hora_inicio" name="hora_inicio" value="00:01">
        </div>
        <div class="w-100"></div>
        <div class="col col-form-label">
            <label for="data_fim">Data de fim:</label>
        </div>
        <div class="col">
            <input class="form-control" type="date" id="data_fim" name="data_fim">
        </div>
        <div class="col col-form-label">
            <label for="hora_fim">Horário de fim:</label>
        </div>
        <div class="col">
            <input class="form-control" type="time" id="hora_fim" name="hora_fim" value="23:59">
        </div>
    </div>
</div>
<div class="row align-items-end justify-content-end">
    <div class="col align-self-end">
        <input class="btn btn-primary" type="submit" value="Adicionar">
    </div>
</div>


</body>
</html>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>

