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

function submit(){
    global $db, $equipes, $pastas, $user;
    $artigo = array();
    $ErrorArrays = array();
    if (empty($_POST["titulo"])) {
        $ErrorArrays[] = "Digite um título.";
    } else {
        $artigo["titulo"] = $_POST["titulo"];
    }
    if (empty($_POST["texto"])) {
        $ErrorArrays[] = "O artigo não pode ficar em branco.";
    } else {
        $artigo["conteudo"] = $_POST["texto"];
    }

    $setor = $_POST["setor"];
    $pasta = $_POST["pasta"];

    if($pasta != 0){
        if($pastas[$pasta]["grupo"] != $setor){
            $ErrorArrays[] = "O setor selecionado não tem acesso a pasta selecionada";
        }
    }

    if (count($ErrorArrays) == 0) {
        $fields = array(
            "titulo" => $artigo["titulo"],
            "conteudo" => $artigo["conteudo"],
            "grupo" => $setor,
            "pasta" => $pasta,
        );
        $db->insert("wiki_artigos", $fields);
        if ($db->error()) {
            echo $db->errorString();
        } else {
            $idartigo = $db->lastId();
            $fields = array(
                "idartigo" => $idartigo,
                "conteudo" => $artigo["titulo"] . " " . $artigo["conteudo"],
                "iduser" => $user->data()->id,
            );
            $db->insert("wiki_historico", $fields);
            header('Location: wiki_artigo_editar.php?id=' . $idartigo);
        }

    } else {
        foreach ($ErrorArrays as $Errors) {
            echo "<p style='color:red'><b>" . $Errors . "</p></b>";
        }
    }

}

$db->query("SELECT * FROM wiki_pastas ORDER BY titulo");
$pastas = array();
foreach ($db->results() as $row) {
    if (hasPerm($row->grupo)) {
        $e['id'] = $row->id;
        $e['titulo'] = $row->titulo;
        $e['grupo'] = $row->grupo;
        $pastas[$row->id] = $e;
    }
}

$equipes = listar_equipes();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    submit();
}


?>

<div class="row" style="margin-top: 1em; margin-bottom: 1em">
    <div class="col">
        <a class="btn btn-primary" href="wiki.php">Voltar</a>
    </div>
</div>

<?php tinymce("Título", "", $pastas, $equipes); ?>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
