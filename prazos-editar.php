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

$db->query("SELECT * FROM prazos WHERE id = '" . $_GET["id"] . "'");

if ((count($db->results()) == 0)) {
    echo "PRAZO NÃO LOCALIZADO";
    die();
}


$prazo = $db->results()[0];

if (!(hasPerm($prazo->grupo))) {
    echo "USUÁRIO NÃO AUTORIZADO PARA VER ESTE PRAZO";
    die();
}


function submit()
{
    global $db, $prazo;
    $prazo_edit = array();
    $ErrorArrays = array();
    if (empty($_POST["descricao"])) {
        $ErrorArrays[] = "Preencha o campo Descrição.";
    } else {
        $prazo_edit["titulo"] = $_POST["descricao"];
    }
    if (empty($_POST["vencimento"])) {
        $ErrorArrays[] = "Preencha o campo Vencimento.";
    } else {
        $prazo_edit["fim"] = $_POST["vencimento"];
    }
    if (empty($_POST["alerta"])) {
        $ErrorArrays[] = "Preencha o campo Alerta.";
    } else {
        $prazo_edit["alerta"] = $_POST["alerta"];
    }
    if (empty($_POST["documento"])) {
        $prazo_edit["documento"] = "";
    } else {
        $prazo_edit["documento"] = $_POST["documento"];
    }

    if (count($ErrorArrays) == 0) {
        $fields = array(
            "titulo" => $prazo_edit["titulo"],
            "fim" => $prazo_edit["fim"],
            "alerta" => $prazo_edit["alerta"],
            "documento" => $prazo_edit["documento"],
        );
//        $db->insert("prazos", $fields);
        $db->update("prazos", $prazo->id, $fields);
        if ($db->error()) {
            echo $db->errorString();
        } else {
            header('Location: prazos.php');
            echo "Ok";
//            echo $db->errorString();
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


<script>

    function validateForm() {
        if (confirm("Você deseja deletar este prazo? Essa operação não poderá ser desfeita.")) {
            $("myform").submit();

        } else {
            return false;
        }
    }

</script>


<div style="margin-top: 1em" class="container">
    <div class="row">
        <div class="col">
            <a class="btn btn-secondary" href="prazos.php">Prazos</a>
        </div>
        <div class="col-sm-offset-6">
            <form id="myform" name="myForm"
                  action=<?php echo "'prazo-deletar.php?id=" . $prazo->id . "'" ?> onsubmit="return validateForm()
            " method="post">
            <button type="submit" class="btn btn-danger">Deletar</button>
            </form>
        </div>
    </div>

</div>

<div style="margin-top: 1em" class="row">
    <div class="col-sm-12">
        <h1>Editar prazo</h1>
        <br/>
        <form action="" method="post">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" id="descricao"
                       name="descricao" <?php echo "value='" . $prazo->titulo . "'" ?> >
            </div>
            <div class="form-group">
                <label for="vencimento">Vencimento</label>
                <input type="date" class="form-control" id="vencimento"
                       name="vencimento" <?php echo "value='" . $prazo->fim . "'" ?>>
            </div>
            <div class="form-group">
                <label for="descricao">Documento</label>
                <input type="text" class="form-control" id="documento"
                       name="documento" <?php echo "value='" . $prazo->documento . "'" ?>>
            </div>
            <div class="form-group">
                <label for="alerta">Alerta com quantos dias de antecedência?</label>
                <input type="number" class="form-control" id="alerta"
                       name="alerta" <?php echo "value='" . $prazo->alerta . "'" ?>>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
