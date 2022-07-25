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

$comissoes = lista_comissoes();

$db->query("SELECT * FROM comissao_projetos");
$projetos = array();
$projetos_arquivo = array();

foreach ($db->results() as $row) {
    $e['id'] = $row->id;
    $e['nome'] = $row->nome;
    $e['descricao'] = $row->descricao;
    $e['data'] = $row->data;
    $e['id_responsavel'] = $row->id_responsavel;
    $e['status_gestao'] = $row->status_gestao;
    $e['status_comissao'] = $row->status_comissao;
    if ($row->status_gestao == "0"){
        $projetos[$row->id] = $e;
    }else{
        $projetos_arquivo[$row->id] = $e;
    }
}

//dump($projetos);
//dump($projetos_arquivo);

?>


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">Gestão de Comissão</h1>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="projetos-tab" data-toggle="tab" href="#projetos" role="tab" aria-controls="projetos" aria-selected="true">Projetos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="arquivo-tab" data-toggle="tab" href="#arquivo" role="tab" aria-controls="arquivo" aria-selected="false">Arquivo</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="projetos" role="tabpanel" aria-labelledby="projetos-tab">

                    <div style="margin-top: 1em" class="container">
                        <div class="row">
                            <div class="col justify">
                                <a class="btn btn-primary" href="comissao-gestao-adicionar.php">
                                    Adicionar novo
                                </a>
                            </div>
                        </div>
                        <div style="margin-top: 1em" class="row">
                            <h3 class="text-center">Projetos</h3>
                        </div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Responsável</th>
                                <th scope="col">Data prevista</th>
                                <th scope="col">Status</th>
                                <th scope="col">Documentos</th>
                                <th scope="col">Arquivar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($projetos as $item) {
                                console_log($item);
                                $url_edit = "comissao-gestao-editar.php?id=" . $item["id"];
                                $url_doc = "comissao-gestao-documentos.php?id=" . $item["id"];
                                $url_arquivo = "comissao-gestao-arquivar.php?id=" . $item["id"];
                                echo "<tr>";
                                escreve_linha([
                                    "<a class='btn' href='" . $url_edit . "''><i class='bi bi-pencil-fill'></i></a> " . $item["nome"],
                                    $comissoes[$item["id_responsavel"]]["nome"] . "<p><sub>" . $comissoes[$item["id_responsavel"]]["membros"] . "</sub></p>",
                                    escreve_data($item["data"], "d/m/Y"),
                                    ($item["status_comissao"] == "0" ? "Pendente" : "Concluído"),
                                    "<a class='btn' href='" . $url_doc . "''><i class='bi bi-file-text-fill'></i> Documentos</a>",
                                    "<a class='btn' href='" . $url_arquivo . "''><i class='bi bi-archive-fill'></i> Arquivar</a>"
                                ]);
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="arquivo" role="tabpanel" aria-labelledby="arquivo-tab">

                    arquivo

                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
