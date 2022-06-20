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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    submit();
}


function submit()
{

    global $db, $user;
    $imagem = array();
    $ErrorArrays = array();
    if (empty($_POST["nome"])) {
        $ErrorArrays[] = "Preencha o campo Nome.";
    } else {
        $imagem["nome"] = $_POST["nome"];
    }

    $target_dir = "galeria/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $ErrorArrays[] = "Arquivo não é uma imagem";
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $ErrorArrays[] = "Arquivo repetido";
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $ErrorArrays[] = "Arquivo grande demais.";
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $ErrorArrays[] = "Apenas JPG, JPEG, PNG & GIF são permitidos";
    }

    if (count($ErrorArrays) == 0) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $nome = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
            echo "The file " . $nome . " has been uploaded.";
            $fields = array(
                "nome" => $imagem["nome"],
                "endereco" => $nome,
                "grupo" => $_POST["equipe"],
            );
            $db->insert("imagem", $fields);
            logger($user->data()->id, 'Galeria', 'Adicionada imagem ' . $imagem["nome"]);
            if ($db->error()) {
                echo $db->errorString();
            } else {
                header('Location: galeria.php');
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

$Equipes = listar_equipes();

$db->query("SELECT * FROM imagem");
$imagens = array();
foreach ($db->results() as $row) {
    if (hasPerm($row->grupo)) {
        $e['id'] = $row->id;
        $e['nome'] = $row->nome;
        $e['endereco'] = $row->endereco;
        $e['grupo'] = $row->grupo;
        $imagens[] = $e;
    }
}

?>


<!DOCTYPE html>
<html>
<body>

<h3>Enviar imagem</h3>
<form class="form-group" action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome">
    </div>
    <div class="form-group">
        <label for="equipe">Equipe</label>
        <select class="form-control" id="equipe" name="equipe">
            <?php
            foreach ($Equipes as $equipe) {
                echo '<option value="' . $equipe["id"] . '">' . $equipe["nome"] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="fileToUpload" class="col-form-label">Selecione uma imagem</label>
        <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
    </div>
    <input class="btn-primary btn" type="submit" value="Enviar" name="submit">
</form>

<div class="card-header">
    <h3>Galeria</h3>
</div>
<div class="card-body">
    <div class="card-columns">
        <?php
        foreach ($imagens as $imagem){
            echo "<div class='col text-center'>";
            echo "<a href='galeria/" . $imagem["endereco"] . "'>" ;
            echo "<img style='width: 100%; max-width: 150px' src='galeria/" . $imagem["endereco"] . "'>" ;
            echo "<p>" . $imagem["nome"] . "</p>";
            echo "</a>";
            echo "</div>";
        }
        ?>
    </div>
</div>



</body>
</html>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
