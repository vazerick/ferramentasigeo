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

$usuario = $user->data()->id;

$db->query("SELECT * FROM alertas WHERE usuario = '" . $usuario . "'");

if ((count($db->results()) == 0)) {
    $primeiro = true;
} else {
    $primeiro = false;
    $alerta = $db->results()[0];
}

function submit()
{
    global $db, $alerta, $primeiro, $usuario, $user;
    $alerta_edit = array();
    $ErrorArrays = array();
    if (empty($_POST["email"])) {
        $ErrorArrays[] = "Preencha o campo E-mail.";
    } else {
        $alerta_edit["email"] = $_POST["email"];
    }
    if (empty($_POST["alerta"])) {
        $ErrorArrays[] = "Preencha o campo Alerta.";
    } else {
        $alerta_edit["dia"] = $_POST["alerta"];
    }
    if (count($ErrorArrays) == 0) {
        $fields = array(
            "email" => $alerta_edit["email"],
            "dia" => $alerta_edit["dia"],
            "usuario" => $usuario,
        );
        if ($primeiro) {
            $db->insert("alertas", $fields);
            logger($user->data()->id, 'Alerta', 'Adicionado alerta novo.');
            if ($db->error()) {
                echo $db->errorString();
            } else {
                header('Location: alertas.php');
            }
        } else {

            $db->update("alertas", $alerta->id, $fields);
            logger($user->data()->id, 'Alerta', 'Atualizado alerta ' . $alerta->id);
            if ($db->error()) {
                echo $db->errorString();
            } else {
                header('Location: alertas.php');
            }
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


<form action="" method="post">
    <div style="margin-top: 1em;" class="row">
        <div class="col">
            <label for="email">E-mail para alerta</label>
            <?php
            if ($primeiro) {
                echo "<input class='form-control' type='email' id='email' name='email'>";
            } else {
                echo "<input class='form-control' type='email' id='email' value='" . $alerta->email . "' name='email'>";
            }
            ?>
        </div>
        <div class="col">
            <label for="alerta">Alerta do calendário com quantos dias de antecedência?</label>

            <?php
            if ($primeiro) {
                echo '<input class="form-control" type="number" id="alerta" name="alerta">';
            } else {
                echo "<input class='form-control' type='number' id='alerta' value='" . $alerta->dia . "' name='alerta'>";
            }
            ?>
        </div>
    </div>
    <div style="margin-top: 1em;" class="row align-items-end justify-content-end">
        <div class="col align-self-end">
            <input class="btn btn-primary" type="submit" value="Salvar">
        </div>
    </div>
</form>
<?php
if (!$primeiro) {
    echo '<div style="margin-top: 1em;" class="row align-items-end justify-content-end">';
    echo '<div class="col align-self-end">';
    echo '<a class="btn btn-danger" href="alertas-deletar.php?id= ' . $alerta->id . '">Deletar</a>';
    echo '</div>';
    echo '</div>';
}
?>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
