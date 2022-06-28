<?php
if (file_exists("install/index.php")) {
    //perform redirect if installer files exist
    //this if{} block may be deleted once installed
    header("Location: install/index.php");
}

require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (isset($user) && $user->isLoggedIn()) {
}

$Equipes = [];
foreach (listar_equipes() as $equipe) {
    $Equipes[] = $equipe['id'];
}

$Paginas = $db->results();

$count = 0;

function botao($pagina, $rotulo, $icone, $info){
    global $Equipes, $db, $count;
    $db->query("SELECT id FROM pages WHERE `page` = '" . $pagina . "'");
    $idpagina = $db->results()[0]->id;
    $db->query("SELECT permission_id FROM permission_page_matches WHERE `page_id` = '" . $idpagina . "'");
    $flag = false;
    foreach ($db->results() as $item) {
        if (in_array($item->permission_id, $Equipes)) {
            $flag = true;
        }
    }
    if ($flag){
        $count += 1;
        echo '<div class="col-sm">';
        echo '<a class="btn btn-primary protip" style="width: 100%" href="' . $pagina . '" role="button" data-pt-title="' . $info . '" data-pt-position="top" data-pt-scheme="blue">';
        echo '<i class="bi ' . $icone . '"></i>';
        echo '<p>' . $rotulo . '</p>';
        echo '</a>';
        echo '</div>';
        echo '<div id="' . $count . '"></div>';
    }
}

?>

<head>

    <link rel="stylesheet" href="protip.min.css">
    <script>
        $(document).ready(function () {
            $.protip();
        });
<!--        function (info) {-->
<!--            var texto = "";-->
<!--            if (info.event.allDay == false) {-->
<!--                var date = new Date(info.event.start);-->
<!--                texto += date.getHours() + ':' + date.getMinutes() + " - ";-->
<!--            }-->
<!--            texto += info.event.title;-->
<!--            if (info.event.extendedProps.description) {-->
<!--                texto += " | " + info.event.extendedProps.description;-->
<!--            }-->
<!--            info.el.classList.add('protip');-->
<!--            info.el.setAttribute('data-pt-title', texto)-->
<!--            info.el.setAttribute('data-pt-position', 'top')-->
<!--            info.el.setAttribute('data-pt-scheme', 'blue')-->
<!--        }-->
    </script>

</head>

<div class="card-header">
    <h1 align="center"><?php echo $settings->site_name; ?></h1>
    <p align="center">
        <?php
        if ($user->isLoggedIn()) {
            ?>
            <a class="btn btn-primary" href="users/account.php" role="button"> Perfil &raquo;</a>
            <a class="btn btn-warning" href="users/logout.php" role="button"> Sair &raquo;</a>
        <?php } else {
            ?>
            <a class="btn btn-warning" href="users/login.php" role="button"><?= lang("SIGNIN_TEXT"); ?> &raquo;</a>
            <a class="btn btn-info" href="users/join.php" role="button">Solicitar acesso &raquo;</a>
        <?php } ?>
    </p>
    <br>
</div>
<?php
if ($user->isLoggedIn()) {
    ?>
    <div class="container" style="margin-top: 2em;">
        <div class="row justify-content-center">

            <?php
                botao("alertas.php", "Alerta", "bi-alarm", "E-mail de alarme diário");
                botao("calendario.php", "Calendário", "bi-calendar-event", "Calendário com eventos e compromissos");
                botao("prazos.php", "Controle de prazos","bi-exclamation-diamond", "Controle de encerramento de prazos e mandados");
                botao("wiki.php","Encliclopédia", "bi-book", "Textos compartilhados");
                botao("galeria.php", "Galeria","bi-images", "Galeria de imagens compartilhadas");
                botao("ata.php", "Gerador de ata","bi-journal-text", "Assistente para redigir atas");
                botao("comissao_gestao.php", "Gestão de Comissão","bi-gear-fill", "Gestão das comissões do conselho");
                botao("comissao.php", "Comissão","bi-inboxes-fill", "Projetos das comissões do conselho");
            ?>

        </div>
    </div>
    <?php
    $max = 6;
    if($count > $max){
        $linhas = ceil(($count/$max))-1;
        for ($i = $linhas; $i > 0; $i-- ){
            $corte = round($count/($linhas+1));
            echo "<script> document.getElementById('". $corte * $i ."').classList.add('w-100'); </script>";
        }
    }
    ?>

<?php } ?>
<?php languageSwitcher(); ?>

<script src="protip.min.js"></script>

<!-- Place any per-page javascript here -->

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
