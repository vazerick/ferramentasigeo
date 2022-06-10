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

$pastas_validas = array();

$db->query("SELECT * FROM wiki_artigos ORDER BY titulo");
$artigos = array();
foreach ($db->results() as $row) {
    if (hasPerm($row->grupo)) {
        $e['id'] = $row->id;
        $e['titulo'] = $row->titulo;
        $e['conteudo'] = $row->conteudo;
        $e['grupo'] = $row->grupo;
        $e['pasta'] = $row->pasta;
        $pastas_validas[] = $row->pasta;
        $artigos[$row->id] = $e;
    }
}
$pastas_validas = array_unique($pastas_validas);

$db->query("SELECT * FROM wiki_pastas ORDER BY titulo");
$pastas = array();
foreach ($db->results() as $row) {
    if (hasPerm($row->grupo)) {
        if(in_array($row->id, $pastas_validas)){
            $e['id'] = $row->id;
            $e['titulo'] = $row->titulo;
            $e['grupo'] = $row->grupo;
            $pastas[$row->id] = $e;
        }
    }
}


function lista_artigo($pastaid){
    global $pastas, $artigos;
    foreach ($artigos as $artigo){
        if ($artigo['pasta'] == $pastaid){
            echo '<div class="row">';
            echo '<div class="col">';
            echo '<div onclick="selecionar(' . $artigo["id"] . ')" class="btn btn-link border-bottom text-left" style="width: 100%; white-space: normal !important;" >';
            echo '<span class="bi-file-text"></span>';
            echo $artigo["titulo"];
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
}

function lista_pastas(){
    global $pastas;
    foreach ($pastas as $pasta){
        $etiqueta = "collapse" . ($pasta["id"]);
        echo '<div class="card">';
        echo '<div class="card-header" id="headingOne">';
        echo '<button class="btn btn-link" style="white-space: normal !important;" data-toggle="collapse" data-target="#'. $etiqueta .'" aria-expanded="false" aria-controls="'. $etiqueta .'">';
        echo '<span class="bi-folder-fill"></span>';
        echo $pasta["titulo"];
        echo '</button>';
        echo '</div>';
        echo '<div id="'. $etiqueta .'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
        lista_artigo($pasta["id"]);
        echo '</div>';
        echo '</div>';
    }
    lista_artigo("0");
}

?>

<script>
    function selecionar(elemento) {
        artigo = <?php echo json_encode($artigos); ?>;
        artigo = artigo[elemento]
        document.getElementById("painel").style.display = 'block';
        document.getElementById("titulo").innerHTML = artigo["titulo"];
        document.getElementById("texto").innerHTML = artigo["conteudo"];
        document.getElementById("botao").setAttribute("name", "#" + artigo["id"]);
        document.getElementById("botao").href = "wiki_artigo_editar.php?id=" + artigo["id"];
    }
</script>

<a href="wiki_pastas.php" class="btn btn-primary" style="margin-top: 1em; margin-bottom: 1em">
    Editar pastas
</a>

<div class="row">
    <div class="col-3">
        <div id="accordion">
            <?php lista_pastas(); ?>
        </div>
        <div class="row">
            <a href="wiki_artigo_novo.php" class="btn btn-primary" style="margin-top: 1em; margin-bottom: 1em">
                Novo artigo
            </a>
        </div>
    </div>

    <div id="painel" style="display: none;" class="col">
        <div class="card">
            <h5 class="card-header">
                <div class="float-left" id="titulo"></div>
                <a id="botao" class="float-right btn btn-primary">
                    <div class="bi-pencil-fill"></div>
                </a>
            </h5>
            <div id="texto" class="card-body">
            </div>
        </div>
    </div>
</div>


<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
