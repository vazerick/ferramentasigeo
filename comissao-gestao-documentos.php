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

$db->query("SELECT * FROM comissao_documentos WHERE id_projeto=" . $_GET["id"]);
$documentos = $db->results();

dump($documentos);

?>


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">Documentos - ID</h1>
            </div>

            <div style="margin-top: 1em" class="container">
                <div class="row">
                    <div class="col justify">
                        <?php echo '<a class="btn btn-primary" href="comissao-gestao-documentos-adicionar.php?id=' . $_GET["id"] . '">'; ?>
                            Adicionar
                        </a>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Descrição</th>
                        <th scope="col">Link</th>
                        <th scope="col">Excluir</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($documentos as $item) {
                        console_log($item);
                        $url_edit = "comissao-gestao-documentos-editar.php?id=" . $item->id;
                        $url_doc = "biblioteca/comissoes/" . $item->link;
                        $url_excluir = "comissao-gestao-documentos-excluir.php?id=" . $item->id;
                        echo "<tr>";
                        escreve_linha([
                            "<a class='btn' href='" . $url_edit . "''><i class='bi bi-pencil-fill'></i></a> " . $item->nome,
                            "<a href='" . $url_doc . "''>" . $item->link . "</a>",
                            "<a class='btn' href='" . $url_excluir . "''><i class='bi bi-trash-fill'></i> Excluir</a>"
                        ]);
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>


<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
