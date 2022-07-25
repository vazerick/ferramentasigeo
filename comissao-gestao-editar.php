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

$db->query("SELECT * FROM comissao_projetos WHERE id = '" . $_GET["id"] . "'");

if ((count($db->results()) == 0)) {
    echo "PROJETO NÃO LOCALIZADO";
    die();
}

$projeto = $db->results()[0];

$comissoes = lista_comissoes();

function submit()
{   
    global $db, $projeto, $user;
    $projeto_edit = array();
    $ErrorArrays = array();
    if (empty($_POST["nome"])) {
        $ErrorArrays[] = "Preencha o campo Nome.";
    } else {
        $projeto_edit["nome"] = $_POST["nome"];
    }
    if (empty($_POST["equipe"])) {
        $ErrorArrays[] = "Selecione a comissão responsável.";
    } else {
        $projeto_edit["equipe"] = $_POST["equipe"];
    }
    if (empty($_POST["descricao"])) {
        $ErrorArrays[] = "Preencha o campo Descricao.";
    } else {
        $projeto_edit["descricao"] = $_POST["descricao"];
    }
    if (empty($_POST["data"])) {
        $ErrorArrays[] = "Preencha o campo Data Prevista.";
    } else {
        $projeto_edit["data"] = $_POST["data"];
    }
//    $email = $_POST["data"];

    if (count($ErrorArrays) == 0) {
        $fields = array(
            "nome" => $projeto_edit["nome"],
            "descricao" => $projeto_edit["descricao"],
            "id_responsavel" => $projeto_edit["equipe"],
            "data" => $_POST["data"],
        );
//        $db->insert("prazos", $fields);
        $db->update("comissao_projetos", $projeto->id, $fields);
        logger($user->data()->id, 'Projetos', 'Atualizado o projeto ' . $projeto->id);
        $assunto = "[PROJETO ATUALIZADO] " . $projeto->nome;
        if ($db->error()) {
            echo $db->errorString();
        } else {
            header('Location: comissao-gestao.php');
            echo "Ok";
            email_comissao($projeto->id, $assunto, $_POST["mensagem"]);
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
        if (confirm("Você deseja deletar este projeto? Essa operação não poderá ser desfeita.")) {
            $("myform").submit();

        } else {
            return false;
        }
    }

    function atualiza() {
        let projeto = <?php echo json_encode($projeto); ?>;
        let comissao = <?php echo json_encode($comissoes[$projeto->id_responsavel]["nome"]); ?>;
        console.log(comissao);
        let prop = ["nome", "descricao", "data"]
        let mensagem = "";
        prop.forEach(function(i){
            let old = projeto[i];
            let edit = document.getElementById(i).value;
            console.log(old);
            console.log(edit);
            if (old !== edit){
                mensagem = mensagem + "<p><strong>" + i + ": " + "</strong>" + old + " -> " + edit + "</p>";
            }
        })
        console.log(projeto["id_responsavel"])
        console.log(document.getElementById("equipe").value)
        if (mensagem !== ""){
            mensagem = "<p><strong> Modificação no projeto " + projeto["nome"] + "<p>" + comissao + "</p>" + "</strong></p>" + mensagem;
        }
        document.getElementById("texto").innerHTML = mensagem;
        document.getElementById("mensagem").innerHTML = mensagem;
    }

</script>


<div style="margin-top: 1em" class="container">
    <div class="row">
        <div class="col">
            <a class="btn btn-secondary" href="comissao-gestao.php">Projetos</a>
        </div>
        <div class="col-sm-offset-6">
            <form id="myform" name="myForm"
                  action=<?php echo "'comissao-gestao-deletar.php?id=" . $projeto->id . "'" ?> onsubmit="return validateForm()
            " method="post">
            <button type="submit" class="btn btn-danger">Deletar</button>
            </form>
        </div>
    </div>

</div>

<div style="margin-top: 1em" class="row">
    <div class="col-sm-12">
        <h1>Editar projeto</h1>
        <br/>
        <form method='POST' action="">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" onchange="atualiza()"
                       name="nome" <?php echo "value='" . $projeto->nome . "'" ?> >
            </div>
            <div class="form-group">
                <label for="equipe">Responsável</label>
                <select class="form-control" id="equipe" name="equipe" onchange="atualiza()">
                    <?php
                    foreach ($comissoes as $comissao) {
                        $opt = "";
                        if ($comissao["id"] == $projeto->id_responsabel) {
                            $opt = "selected='selected'";
                        }
                        echo '<option value="' . $comissao["id"] . '"' . $opt . '>' . $comissao["nome"] . " - " . $comissao["membros"]  . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" onchange="atualiza()"><?php echo $projeto->descricao ?></textarea>
            </div>
            <div class="form-group">
                <label for="data">Data prevista</label>
                <input type="date" class="form-control" id="data" onchange="atualiza()"
                       name="data" <?php echo "value='" . $projeto->data . "'" ?>>
            </div>
            <div>
                <input type="checkbox" id="email" name="email">
                <label for="email" >Enviar e-mail informando atualização</label>
            </div>
            <div class="card" id="texto">
            </div>
            <textarea id="mensagem" name="mensagem" hidden>
            </textarea>
            <div style="margin-bottom: 1em"></div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<script>
    atualiza();
</script>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
