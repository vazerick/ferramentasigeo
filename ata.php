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

$persistencia = [];


function manter_dados()
{
    foreach ($_POST as $key => $value) {
        global $persistencia;
        $persistencia[$key] = $value;
    }
}

function valor($key)
{
    global $persistencia;
    if (isset($persistencia[$key])) {
        return $persistencia[$key];
    } else {
        return "";
    }
}

function selecionado($key, $option)
{
    global $persistencia;
    if (isset($persistencia[$key])) {
        if ($persistencia[$key] == $option) {
            return "selected";
        }
    }
    return "";
}

function escreve_semana($num)
{
    $num = (int)$num;
    $semana = array(
        "",
        "segunda-feira",
        "terça-feira",
        "quarta-feira",
        "quinta-feira",
        "sexta-feira",
        "sábado",
        "domingo"
    );
    return $semana[$num];
}

function mes_extenso($num)
{
    $num = (int)$num;
    $meses = array(
        "",
        "janeiro",
        "fevereiro",
        "março",
        "abril",
        "maio",
        "junho",
        "julho",
        "agosto",
        "setembro",
        "outubro",
        "novembro",
        "dezembro",
    );
    return $meses[$num];
}

function data_extenso($num)
{
    $num = (int)$num;
    $data = array(
        '1' => 'primeiro', '2' => 'segundo', '3' => 'terceiro', '4' => 'quarto', '5' => 'quinto', '6' => 'sexto', '7' => 'sétimo', '8' => 'oitavo', '9' => 'nono', '10' => 'décimo',
        '11' => 'décimo primeiro', '12' => 'décimo segundo', '13' => 'décimo terceiro', '14' => 'décimo quarto', '15' => 'décimo quinto', '16' => 'décimo sexto', '17' => 'décimo sétimo', '18' => 'décimo oitavo', '19' => 'décimo nono', '20' => 'vigésimo',
        '21' => 'vigésimo primeiro', '22' => 'vigésimo segundo', '23' => 'vigésimo terceiro', '24' => 'vigésimo quarto', '25' => 'vigésimo quinto', '26' => 'vigésimo sexto', '27' => 'vigésimo sétimo', '28' => 'vigésimo oitavo', '29' => 'vigésimo nono', '30' => 'trigésimo', '31' => 'trigésimo primeiro'
    );
    return $data[$num];
}

function num_extenso($num)
{
    $num = (int)$num;
    $numeros1 = array(
        '1' => 'um', '2' => 'dois', '3' => 'três', '4' => 'quatro', '5' => 'cinco', '6' => 'seis', '7' => 'sete', '8' => 'oito', '9' => 'nove', '10' => 'dez',
        '11' => 'onze', '12' => 'doze', '13' => 'treze', '14' => 'catorze', '15' => 'quinze', '16' => 'dezesseis', '17' => 'dezessete', '18' => 'dezoito', '19' => 'dezenove'
    );
    $numeros2 = array(
        '2' => 'vinte', '3' => 'trinta', '4' => 'quarenta', '5' => 'cinquenta', '6' => 'sessenta', '7' => 'setenta', '8' => 'oitenta', '9' => 'noventa'
    );
    if ($num == 0) {
        return false;
    }
    if ($num < 20) {
        return $numeros1[$num];
    }
    $palavra = "";
    $num = strval($num);
    if (strlen($num) > 3) {
        $palavra .= $numeros1[$num[0]] . " mil e ";
        $num = substr($num, -2);
    }
    $palavra .= $numeros2[$num[0]];
    if ($num[1] != "0") {
        $palavra .= " e " . $numeros1[$num[1]];
    }
    return $palavra;
}

function hora_extenso($str)
{
    $texto = "";
    $horario = explode(":", $str);
    $hora = $horario[0];
    $minuto = $horario[1];
    switch ($hora) {
        case "12":
            $texto = "ao meio dia";
            break;
        case "01":
            $texto = "à uma hora";
            break;
        default:
            $texto = "às " . num_extenso($hora) . " horas";
    }
    if ($minuto != "00") {
        if ($minuto == "01") {
            $texto .= " e " . num_extenso($minuto) . " minuto";
        } else {
            $texto .= " e " . num_extenso($minuto) . " minutos";
        }
    }
    return $texto;
}

function titleCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("de", "da", "dos", "das", "do", "I", "II", "III", "IV", "V", "VI"))
{
    /*
     * Exceptions in lower case are words you don't want converted
     * Exceptions all in upper case are any words you don't want converted to title case
     *   but should be converted to upper case, e.g.:
     *   king henry viii or king henry Viii should be King Henry VIII
     */
    $string = trim($string);
    $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    foreach ($delimiters as $dlnr => $delimiter) {
        $words = explode($delimiter, $string);
        $newwords = array();
        foreach ($words as $wordnr => $word) {
            if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtoupper($word, "UTF-8");
            } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtolower($word, "UTF-8");
            } elseif (!in_array($word, $exceptions)) {
                // convert to uppercase (non-utf8 only)
                $word = ucfirst($word);
            }
            array_push($newwords, $word);
        }
        $string = join($delimiter, $newwords);
    }//foreach
    return $string;
}

function arrumar_nomes($lista)
{
    $lista = str_replace(array(";", ",", "\n"), '*', $lista);
    $lista = explode("*", $lista);
    $nomes = array();
    foreach ($lista as $nome) {
        if (strlen($nome) > 1) {
            $nomes[] .= titleCase($nome);
        }
    }
    sort($nomes);
    $nomes = implode(", ", $nomes);
    return $nomes;
}

function escreve_ata()
{
    $arquivo = fopen("ata.txt", "r");
    if ($arquivo == false) {
        echo("ERRO LENDO ARQUIVO DE ATA");
        exit();
    }
    $filesize = filesize("ata.txt");
    $ata = fread($arquivo, $filesize);
    fclose($arquivo);
    $presidencia = "";
    switch ($_POST["genero"]) {
        case "F":
            $presidencia = "a professora";
            break;
        case "M":
            $presidencia = "o professor";
            break;
        case "N":
            $presidencia = "ê professore";
            break;
    }
    $presidencia = $presidencia . " " . titleCase($_POST['presidente']);

    switch ($_POST["genero_setor"]) {
        case "F":
            $setor = "a";
            break;
        case "M":
            $setor = "o";
            break;
    }
    $setor = $setor . " " . $_POST['setor'];

    $count = 0;
    $presentes = "";
    $docentes = arrumar_nomes($_POST["docentes"]);
    if (strlen($docentes) > 0) {
        $docentes = "os docentes: " . $docentes . "; ";
        $presentes .= $docentes;
    }
    $discentes = arrumar_nomes($_POST["discentes"]);
    if (strlen($discentes) > 0) {
        $discentes = "os discentes: " . $discentes . "; ";
        $presentes .= $discentes;
    }
    $taes = arrumar_nomes($_POST["taes"]);
    if (strlen($taes) > 0) {
        $taes = "os técnicos-administrativos: " . $taes . ". ";
        $presentes .= $taes;
    }

    $ausentes = arrumar_nomes($_POST["ausentes"]);
    if (strlen($ausentes) > 0) {
        $ausentes = "Ausências justificadas: " . $ausentes;
        $presentes .= $ausentes;
    }

    if (isset($_POST["data"]) and $_POST["data"] != "") {
        $data = explode("-", $_POST["data"]);
        $dia = data_extenso($data[2]);
        $mes = mes_extenso($data[1]);
        $ano = num_extenso($data[0]);
    } else {
        $data = $dia = $mes = $ano = "";
    }

    if (isset($_POST["inicio"]) and $_POST["inicio"] != "") {
        $inicio = hora_extenso($_POST["inicio"]);
    } else {
        $inicio = "";
    }

    if (isset($_POST["fim"]) and $_POST["fim"] != "") {
        $fim = hora_extenso($_POST["fim"]);
    } else {
        $fim = "";
    }

    $dados = array(
        '{{{SETOR}}}' => $setor,
        '{{{redator}}}' => titleCase($_POST['redator']),
        '{{{linha}}}' => str_repeat("_", strlen($_POST['redator']) * 1.5),
        '{{{cargo}}}' => $_POST['cargo'],
        '{{{presidencia}}}' => $presidencia,
        '{{{presidenciacaps}}}' => ucfirst($presidencia),
        '{{{titulo}}}' => $_POST['titulo'],
        '{{{local}}}' => $_POST['local'],
        '{{{dia}}}' => $dia,
        '{{{mes}}}' => $mes,
        '{{{ano}}}' => $ano,
        '{{{inicio}}}' => $inicio,
        '{{{fim}}}' => $fim,
        '{{presentes}}' => $presentes,
    );
    $resultado = strtr($ata, $dados);
    while (strpos($resultado, "  ")) {
        $resultado = str_replace("  ", " ", $resultado);
    }
    $resultado = str_replace('{{{n}}}', "\n\n\n", $resultado);
    while (strpos($resultado, "\n ")) {
        $resultado = str_replace("\n ", "\n", $resultado);
    }
    return $resultado;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    manter_dados();
    echo '<div style="margin-top: 1em;" class="container">';
    echo '<form class="form-group">';
    echo '<textarea class="form-control" id="ata" name="ata" placeholder="" style="height:200px">';
    echo escreve_ata();
    echo '</textarea>';
    echo '<br/>';
    echo '<button type="button" onclick="copiar()" class="rapid_contact btn btn-primary button">Copiar</button>';
    echo '</form>';
    echo '</div>';
    echo '<br/>';
}

manter_dados();

?>

<script>
    function copiar() {
        var copyText = document.getElementById("ata");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        alert("Ata copiada");
    }
</script>

<div class="container" style="margin-top: 2em;">

    <form method="post" action="">

        <div class="form-group">
            <div class="row">
                <div class="col">

                    <div class="row">
                        <label for="setor">Reunião de:</label>
                    </div>

                    <div class="row">
                        <input type="text" id="setor" name="setor"
                               value="<?php echo valor('setor'); ?>" class="form-control">
                    </div>

                </div>

                <div class="col-1"></div>

                <div class="col">
                    <div class="row">
                        <label for="genero_setor">Gênero do setor:</label>
                    </div>
                    <div class="row">
                        <select id="genero" name="genero_setor" class="form-control">
                            <option value="F" <?php echo selecionado('genero_setor', 'F'); ?> >Feminino</option>
                            <option value="M" <?php echo selecionado('genero_setor', 'M'); ?>>Masculino</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <label for="redator">
                            Quem redigiu a ata:</label>
                    </div>
                    <div class="row">
                        <input type="text" id="redator" name="redator"
                               value="<?php echo valor('redator'); ?>" class="form-control">
                    </div>
                </div>
                <div class="col-1">
                </div>
                <div class="col">
                    <div class="row">
                        <label for="cargo">Cargo:</label>
                    </div>
                    <div class="row">
                        <input type="text" id="cargo" name="cargo" value="<?php echo valor('cargo'); ?>"
                               class="form-control">
                    </div>

                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <label for="presidente">
                            Presidência da reunião:</label>
                    </div>
                    <div class="row">
                        <input type="text" id="presidente" name="presidente"
                               value="<?php echo valor('presidente'); ?>" class="form-control"></div>
                </div>
                <div class="col-1">
                </div>
                <div class="col">
                    <div class="row">
                        <label for="titulo">Título da presidência:</label>
                    </div>
                    <div class="row">
                        <input type="text" id="titulo" name="titulo"

                               value="<?php echo valor('titulo'); ?>" class="form-control">
                    </div>

                </div>
                <div class="col-1">
                </div>
                <div class="col">
                    <div class="row">
                        <label for="genero">Gênero da presidência:</label>
                    </div>
                    <div class="row">
                        <select id="genero" name="genero" class="form-control">
                            <option value="F" <?php echo selecionado('genero', 'F'); ?> >Feminino</option>
                            <option value="M" <?php echo selecionado('genero', 'M'); ?>>Masculino</option>
                            <option value="N" <?php echo selecionado('genero', 'N'); ?>>Neutro</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="local">
                    Local da reunião:
                </label>
            </div>
            <div class="row">
                <input type="text" id="local" name="local"
                       value="<?php echo valor('local'); ?>" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <label for="data">
                            Data:</label>
                    </div>
                    <div class="row">
                        <input type="date" id="data" name="data" value="<?php echo valor('data'); ?>"
                               class="form-control">
                    </div>
                </div>
                <div class="col-1">
                </div>
                <div class="col">
                    <div class="row">
                        <label for="inicio">
                            Começo:</label>
                    </div>
                    <div class="row">
                        <input type="time" id="inicio" name="inicio" value="<?php echo valor('inicio'); ?>"
                               class="form-control">
                    </div>

                </div>
                <div class="col-1">
                </div>
                <div class="col">
                    <div class="row">
                        <label for="fim">Fim:</label>
                    </div>
                    <div class="row">
                        <input type="time" id="fim" name="fim" value="<?php echo valor('fim'); ?>" class="form-control">
                    </div>

                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="docentes">Docentes presentes:<br/> (separados por vírgula ou por linha)
                </label>
            </div>
            <div class="row">
                    <textarea id="docentes" name="docentes"
                              style="height:200px" class="form-control"><?php echo valor('docentes'); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="discentes">Discentes presentes:
                    <br/> (separados por vírgula ou por linha)
                </label>
            </div>
            <div class="row">
                    <textarea id="discentes" name="discentes"
                              style="height:200px" class="form-control"><?php echo valor('discentes'); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="taes">Técnicos presentes:
                    <br/> (separados por vírgula ou por linha)
                </label>
            </div>
            <div class="row">
                    <textarea id="taes" name="taes"
                              style="height:200px" class="form-control"><?php echo valor('taes'); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="ausentes">Ausências justificadas:
                    <br/> (separados por vírgula ou por linha)
                </label>
            </div>
            <div class="row">
                    <textarea id="ausentes" name="ausentes"
                              style="height:200px" class="form-control"><?php echo valor('ausentes'); ?></textarea>
            </div>
        </div>
        <input type="submit" class="rapid_contact btn btn-primary button" value="Enviar">
    </form>


</div>


<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
