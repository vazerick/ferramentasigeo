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

function submit()
{
    global $db, $user, $comissoes;
    $projeto = array();
    $ErrorArrays = array();
    if (empty($_POST["nome"])) {
        $ErrorArrays[] = "Preencha o campo Nome.";
    } else {
        $projeto["nome"] = $_POST["nome"];
    }
    if (empty($_POST["equipe"])) {
        $ErrorArrays[] = "Selecione a comissão responsável.";
    } else {
        $projeto["equipe"] = $_POST["equipe"];
    }
    if (empty($_POST["descricao"])) {
        $ErrorArrays[] = "Preencha o campo Descricao.";
    } else {
        $projeto["descricao"] = $_POST["descricao"];
    }
    if (empty($_POST["data"])) {
        $ErrorArrays[] = "Preencha o campo Data Prevista.";
    } else {
        $projeto["data"] = $_POST["data"];
    }

    if (count($ErrorArrays) == 0) {
        $fields = array(
            "nome" => $projeto["nome"],
            "descricao" => $projeto["descricao"],
            "id_responsavel" => $projeto["equipe"],
            "data" => $_POST["data"],
            "status_comissao" => 0,
            "status_gestao" => 0,
        );
        $db->insert("comissao_projetos", $fields);
        $projeto_id = $db->lastId();
        logger($user->data()->id, 'Projetos', 'Adicionado o projeto ' . $projeto["nome"]);
        $assunto = "[NOVO PROJETO PARA ANÁLISE] " . $fields["nome"];
        $mensagem = "<strong> Novo projeto: " . $fields["nome"] . ".</strong>";
        $mensagem .= "<p>Comissão: " . $comissoes[$fields["id_responsavel"]]["nome"] . " - " . $comissoes[$fields["id_responsavel"]]["membros"]  . ".</p>";
        $mensagem .= "<p>Descrição: " . $fields["descricao"] . ".</p>";
        $mensagem .= "<p>Data prevista: " . escreve_data($fields["data"], "d/m/Y") . ".</p>";
        if ($db->error()) {
            echo $db->errorString();
        } else {
            echo "Ok";
            email_comissao($projeto_id, $assunto, $mensagem);
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
        <h1>Adicionar projeto</h1>
        <br/>
        <form method='POST' action="">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome"
                       name="nome" >
            </div>
            <div class="form-group">
                <label for="equipe">Responsável</label>
                <select class="form-control" id="equipe" name="equipe" onchange="atualiza()">
                    <?php
                    foreach ($comissoes as $comissao) {
                        echo '<option value="' . $comissao["id"] . '">' . $comissao["nome"] . " - " . $comissao["membros"]  . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" onchange="atualiza()"></textarea>
            </div>
            <div class="form-group">
                <label for="data">Data prevista</label>
                <input type="date" class="form-control" id="data" onchange="atualiza()"
                       name="data">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
