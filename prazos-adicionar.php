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
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

function submit()
{
    global $db;
    $prazo = array();
    $ErrorArrays = array();
    if (empty($_POST["descricao"])) {
        $ErrorArrays[] = "Preencha o campo Descrição.";
    } else {
        $prazo["titulo"] = $_POST["descricao"];
    }
    if (empty($_POST["vencimento"])) {
        $ErrorArrays[] = "Preencha o campo Vencimento.";
    } else {
        $prazo["fim"] = $_POST["vencimento"];
    }
    if (empty($_POST["alerta"])) {
        $ErrorArrays[] = "Preencha o campo Alerta.";
    } else {
        $prazo["alerta"] = $_POST["alerta"];
    }
    if (empty($_POST["documento"])) {
        $prazo["documento"] = "";
    } else {
        $prazo["documento"] = $_POST["documento"];
    }

    if (count($ErrorArrays) == 0) {
        $fields = array(
            "titulo" => $prazo["titulo"],
            "fim" => $prazo["fim"],
            "alerta" => $prazo["alerta"],
            "documento" => $prazo["documento"],
        );
        $db->insert("prazos", $fields);
        if ($db->error()) {
            echo $db->errorString();
        } else {
            header('Location: prazos.php');
            echo "Ok";
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


<div style="margin-top: 1em" class="row">
	<div class="col-sm-12">
        <form action="" method="post">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" id="descricao" name="descricao">
            </div>
            <div class="form-group">
                <label for="vencimento">Vencimento</label>
                <input type="date" class="form-control" id="vencimento" name="vencimento">
            </div>
            <div class="form-group">
                <label for="descricao">Documento</label>
                <input type="text" class="form-control" id="documento" name="documento">
            </div>
            <div class="form-group">
                <label for="alerta">Alerta com quantos dias de antecedência?</label>
                <input type="number" class="form-control" id="alerta" name="alerta">
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
	</div>
</div>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
