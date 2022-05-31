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

$db->query("SELECT * FROM calendario WHERE id = '" . $_GET["id"] . "'");

if ((count($db->results()) == 0)) {
    echo "EVENTO NÃO LOCALIZADO";
    die();
}


$evento = $db->results()[0];
$data_inicio = $hora_inicio = $data_fim = $hora_fim = "";
$data_inicio = escreve_data($evento->start, "Y-m-d");
$data_fim = escreve_data($evento->end, "Y-m-d");
$hora_inicio = escreve_data($evento->start, "H:i");
$hora_fim = escreve_data($evento->end, "H:i");

if (!(hasPerm($evento->grupo))) {
    echo "USUÁRIO NÃO AUTORIZADO PARA VER ESTE EVENTO";
    die();
}

$Equipes = listar_equipes();

function escreve_data($data, $formato)
{
    $date = strtotime($data);
    return date($formato, $date);
}

function submit()
{
    global $db, $evento;
    $evento_edit = array();
    $ErrorArrays = array();
    $evento_edit["equipe"] = $_POST["equipe"];
    $evento_edit["tipo"] = $_POST["tipo"];
    if (empty($_POST["titulo_evento"])) {
        $ErrorArrays[] = "Preencha o campo Título.";
    } else {
        $evento_edit["titulo"] = $_POST["titulo_evento"];
    }
    if (empty($_POST["descricao"])) {
        $evento_edit["descricao"] = null;
    } else {
        $evento_edit["descricao"] = $_POST["descricao"];
    }
    if (empty($_POST["allday"])) {
        $evento_edit["allday"] = "0";
        $evento_edit["hora_inicio"] = $_POST["hora_inicio"];
        $evento_edit["hora_fim"] = $_POST["hora_fim"];
    } else {
        $evento_edit["allday"] = "1";
        $evento_edit["hora_inicio"] = "00:00";
        $evento_edit["hora_fim"] = "23:59";
    }
    if (empty($_POST["data_inicio"])) {
        $ErrorArrays[] = "Preencha o campo Data Início.";
    } else {
        $evento_edit["data_inicio"] = $_POST["data_inicio"];
    }

    if (empty($_POST["data_fim"])) {
        $ErrorArrays[] = "Preencha o campo Data Fim.";
    } else {
        $evento_edit["data_fim"] = $_POST["data_fim"];
    }


    $evento_edit["inicio"] = $evento_edit["data_inicio"] . " " . $evento_edit["hora_inicio"] . ":00";
    $evento_edit["fim"] = $evento_edit["data_fim"] . " " . $evento_edit["hora_fim"] . ":00";

    if (count($ErrorArrays) == 0) {
        $fields = array(
            "title" => $evento_edit["titulo"],
            "start" => $evento_edit["inicio"],
            "end" => $evento_edit["fim"],
            "allDay" => $evento_edit["allday"],
            "descr" => $evento_edit["descricao"],
            "grupo" => $evento_edit["equipe"],
            "tipo" => $evento_edit["tipo"],
        );
//        $db->insert("calendario", $fields);
        $db->update("calendario", $evento->id, $fields);
        if ($db->error()) {
            echo $db->errorString();
        } else {
            header('Location: calendario-visualizar.php?id=' . $evento->id);
//            echo "Ok";
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
<div class="container">
    <div class="row align-items-start text-left">
        <div class="col">
            <a class="btn btn-secondary" href="calendario.php">Calendário</a>
        </div>
    </div>
</div>
<div>
    <br/><br/>
    <h1>Editar evento</h1>
    <br/>
    <form action="" method="post">
        <div class="row">
            <div class="col">
                <label for="titulo_evento">Título</label>
                <input class="form-control" type="text" id="titulo_evento" name="titulo_evento"
                       style="width: 100%" <?php echo "value='" . $evento->title . "'" ?>>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="equipe">Equipe</label>
                <select class="form-control" id="equipe" name="equipe">
                    <?php
                    foreach ($Equipes as $equipe) {
                        $opt = "";
                        if ($equipe["id"] == $evento->grupo) {
                            $opt = "selected='selected'";
                        }
                        echo '<option value="' . $equipe["id"] . '"' . $opt . '>' . $equipe["nome"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <label for="tipo">Tipo</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="0" <?php if($evento->tipo == 0){echo "selected='selected'";} ?>>Interno</option>
                    <option value="1" <?php if($evento->tipo == 1){echo "selected='selected'";} ?>>Externo</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao"
                          style="width: 100%"><?php echo $evento->descr ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col"
            <label for="allday">Dia inteiro</label>
            <input type="checkbox" onchange="troca_allday(this)" id="allday" name="allday" <?php if ($evento->allDay) {echo "checked";} ?>>
        </div>
</div>
<div class="container form-group ">
    <div class="row align-items-center">
        <div class="col col-form-label">
            <label for="data_inicio">Data de início:</label>
        </div>
        <div class="col">

            <input class="form-control" type="date" onchange="troca_data(this)" id="data_inicio"
                   name="data_inicio" <?php echo "value='" . $data_inicio . "'" ?>>
        </div>
        <div class="col col-form-label">

            <label for="hora_inicio">Horário de início:</label>
        </div>
        <div class="col">
            <input class="form-control" type="time" id="hora_inicio"
                   name="hora_inicio" <?php echo "value='" . $hora_inicio . "'" ?>>
        </div>
        <div class="w-100"></div>
        <div class="col col-form-label">
            <label for="data_fim">Data de fim:</label>
        </div>
        <div class="col">
            <input class="form-control" type="date" id="data_fim"
                   name="data_fim" <?php echo "value='" . $data_fim . "'" ?>>
        </div>
        <div class="col col-form-label">
            <label for="hora_fim">Horário de fim:</label>
        </div>
        <div class="col">
            <input class="form-control" type="time" id="hora_fim"
                   name="hora_fim" <?php echo "value='" . $hora_fim . "'" ?>>
        </div>
    </div>
</div>
<div class="row align-items-end justify-content-end">
    <div class="col align-self-end">
        <input class="btn btn-primary" type="submit" value="Salvar">
    </div>
</div>


</body>
</html>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>

