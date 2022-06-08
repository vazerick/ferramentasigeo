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

$Equipes = listar_equipes();

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

function lista_pastas(){
    global $pastas;
    foreach ($pastas as $pasta){
        echo '<option value="' . $pasta["id"] . '">' . $pasta["titulo"] . '</option>';
    }
}


function submit()
{
    global $db;
    $pasta = array();
    $ErrorArrays = array();

    $pasta["id"] = $_POST["pasta"];
    if (empty($_POST["titulo"])) {
        $ErrorArrays[] = "Digite um título para a pasta.";
    } else {
        $pasta["titulo"] = $_POST["titulo"];
    }
    $fields = array(
        "titulo" => $_POST["titulo"],
        "grupo" => $_POST["equipe"]
    );

    if($pasta["id"] == "0"){
        $db->insert("wiki_pastas", $fields);
    }else{
        $db->update("wiki_pastas", $pasta["id"], $fields);
    }
    if ($db->error()) {
        echo $db->errorString();
    } else {
//            header('Location: calendario-visualizar.php?id=' . $evento->id);
        header('Location: wiki.php');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    submit();
}

?>



<script>
    function selecionar(elemento) {
        titulo = document.getElementById("titulo")
        equipe = document.getElementById("equipe")
        if(elemento.value == "0"){
            titulo.value = "";
        }else{
            pasta = <?php echo json_encode($pastas); ?>;
            pasta = pasta[elemento.value]
            titulo.value = pasta["titulo"];
            equipe.value = pasta["grupo"];
            console.log(pasta)
        }
    }
</script>

<div class="row" style="margin-top: 1em; margin-bottom: 1em"><div class="col">
    <a class="btn btn-primary" href="wiki.php">Voltar</a>
</div></div>

<div class="row">
    <div class="col-sm-12">
        <form action="" method="post">
            <div class="form-group">
                <label for="pasta">Selecione a pasta</label>
                <select onchange="selecionar(this)" class="form-control" id="pasta" name="pasta">
                    <option selected value="0">Adicionar nova pasta</option>
                    <?php lista_pastas() ?>
                </select>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" id="titulo" name="titulo" placeholder="Título da pasta">
            </div>
            <label for="equipe">Equipe</label>
            <select class="form-control" id="equipe" name="equipe">
                <?php
                foreach ($Equipes as $equipe) {
                    echo '<option value="' . $equipe["id"] . '">' . $equipe["nome"] . '</option>';
                }
                ?>
            </select>
            <input type="submit" class="btn btn-primary" style="margin-top: 1em" value="Salvar">
        </form>
    </div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
