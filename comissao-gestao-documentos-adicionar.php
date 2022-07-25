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


function submit()
{
    global $db, $_GET, $user;
    $documento = array();
    $ErrorArrays = array();
    if (empty($_POST["nome"])) {
        $ErrorArrays[] = "Preencha o campo Nome.";
    } else {
        $documento["nome"] = $_POST["nome"];
    }

    $target_dir = "biblioteca/comissoes/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        $ErrorArrays[] = "Arquivo repetido";
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $ErrorArrays[] = "Arquivo grande demais.";
    }

    if (count($ErrorArrays) == 0) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $nome = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
            echo "The file " . $nome . " has been uploaded.";
            $fields = array(
                "nome" => $documento["nome"],
                "link" => $nome,
                "id_projeto" => $_GET["id"],
            );
            $db->insert("comissao_documentos", $fields);
            logger($user->data()->id, 'Documentos', 'Adicionada documento de projeto de comissão ' . $documento["nome"]);
            if ($db->error()) {
                echo $db->errorString();
            } else {
                header('Location: comissao-gestao-documentos.php?id=' . $_GET["id"]);
                echo "Ok";
            }
        } else {
            echo "ERRO AO FAZER UPLOAD";
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
        <form class="form-group" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome"
                       name="nome" >
            </div>
            <div class="form-group">
                <label for="fileToUpload" class="col-form-label">Selecione um documento</label>
                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
