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

$db->query("SELECT * FROM prazos");
$prazos = array();
foreach ($db->results() as $row) {
    if (hasPerm($row->grupo)) {
        $e['id'] = $row->id;
        $e['titulo'] = $row->titulo;
        $e['fim'] = $row->fim;
        $e['documento'] = $row->documento;
        $e['grupo'] = $row->grupo;
        $e['alerta'] = $row->alerta;
        // Merge the event array into the return array
        $prazos[] = $e;
    }
}
?>


<div style="margin-top: 1em" class="container">
    <div class="row">
        <div class="col justify">
            <a class="btn btn-primary" href="prazos-adicionar.php">
                Adicionar novo
            </a>
        </div>
    </div>
    <div style="margin-top: 1em" class="row">
        <div class="col">
            <div class="card-header">
                <h1 class="text-center">Controle de prazos</h1>
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Descrição</th>
            <th scope="col">Vencimento</th>
            <th scope="col">Documento</th>
            <th scope="col">Alerta</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($prazos as $item) {
            console_log($item);
            $url = "prazos-editar.php?id=" . $item["id"];
            echo "<tr>";
            escreve_linha([
                "<a class='btn' href='" . $url . "''><i class='bi bi-pencil-fill' style='color:" . cor_equipe($item['grupo']) . "' ></i></a> " . $item["titulo"],
                escreve_data($item["fim"], "d/m/Y"),
                $item["documento"],
                $item["alerta"] . " dias",
            ]);
//            echo "<td class='btn btn-secondary'><a style='width: 100%'><i class='bi bi-pencil-fill'></i></a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php legenda(); ?>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
