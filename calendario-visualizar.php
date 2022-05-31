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

$db->query("SELECT * FROM calendario WHERE id = '" . $_GET["id"] . "'");

if((count($db->results()) == 0)){
    echo "EVENTO NÃO LOCALIZADO";
    die();
}


$evento = $db->results()[0];

if(!(hasPerm($evento->grupo))){
    echo "USUÁRIO NÃO AUTORIZADO PARA VER ESTE EVENTO";
    die();
}

?>

<script >

    function validateForm() {
        if(confirm("Você deseja deletar este evento? Essa operação não poderá ser desfeita."))
        {
            $("myform").submit();

        }
        else{
            return false;
        }
    }

</script>

<div class="container">
    <form id="myform" name="myForm" action=<?php echo "'calendario-deletar.php?id=" . $evento->id ."'"?> onsubmit="return validateForm()" method="post">
        <div class="row align-items-start text-center">
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <a style="width: 100%; color: white" class="btn btn-primary" href="calendario.php">Calendário</a>
        </div>
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <a style="width: 100%; color: white" class="btn btn-primary" href="calendario-adicionar.php">Novo Evento</a>
        </div>
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <a style="width: 100%; color: white" class="btn btn-primary" href=<?php echo "'calendario-editar.php?id=" . $_GET["id"] . "'" ?>>Editar</a>
        </div>
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <button  type="submit" style="width: 100%; color: white" class="btn btn-danger">Deletar</button>
        </div>
    </div>

	<div class="card-body">
        <div class="card-header">
        <h2><?php echo $evento->title; ?></h2>
        </div>
        <div class="card-text">
            <div>
        <?php
        if($evento->tipo == 0){
            echo "<strong>Tipo: </strong> Interno";
        } else {
            echo "<strong>Tipo: </strong> Externo";
        }
        ?>
            </div>
        </div>
        <div class="card-text">
        <?php
            $formato = "";
            if($evento->allDay){
                $formato = "d/m/Y";
            } else {
                $formato = "H:i - d/m/Y";
            }
            echo "<div ><strong>Início: </strong>";
            echo escreve_data($evento->start, $formato) . "</div>";
            echo "<div ><strong>Fim: </strong>";
            echo escreve_data($evento->end, $formato) . "</div>";
        ?>
        </div>
        <div class="card-body">
            <div class='card-text'><strong>Descrição: </strong>
                <div style="padding: 1em" class="card">
                    <?php echo $evento->descr; ?>
                </div>
        </div>
	</div>
</div>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
