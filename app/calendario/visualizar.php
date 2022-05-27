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

function escreve_data($data, $formato){
    $date = strtotime($data);
    return date($formato, $date);
}

$db->query("SELECT * FROM calendario WHERE id = '" . $_GET["id"] . "'");

if((count($db->results()) == 0)){
    echo "EVENTO NÃO LOCALIZADO";
    die();
}


$evento = $db->results()[0];

if(!(hasPerm($evento->group))){
    echo "USUÁRIO NÃO AUTORIZADO PARA VER ESTE EVENTO";
    die();
}

?>

<div class="container">
    <div class="row align-items-start text-center">
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <button style="width: 75%" class="btn btn-primary">Calendário</button>
        </div>
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <button style="width: 75%" class="btn btn-primary">Novo Evento</button>
        </div>
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <button style="width: 75%" class="btn btn-primary">Editar</button>
        </div>
        <div style="margin-top: 1em; margin-bottom: 1em" class="col">
            <button style="width: 75%" class="btn btn-warning">Deletar</button>
        </div>
    </div>
	<div class="card-body">
        <div class="card-header">
        <h2><?php echo $evento->title; ?></h2>
        </div>
        <div class="card-text">
            <div>
        <?php echo "<strong>Tipo: </strong>" . "Interno" ?>
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
