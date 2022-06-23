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

function botao($pagina, $rotulo, $icone){
    global $Equipes, $db;
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
        echo '<div class="col-sm">';
        echo '<a class="btn btn-primary" style="width: 100%" href="' . $pagina . '" role="button">';
        echo '<i class="bi ' . $icone . '"></i>';
        echo '<p>' . $rotulo . '</p>';
        echo '</a>';
        echo '</div>';
    }
}

?>

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
                echo botao("alertas.php", "Alerta", "bi-alarm");
                echo botao("calendario.php", "Calendário", "bi-calendar-event");
                echo botao("prazos.php", "Controle de prazos","bi-exclamation-diamond");
                echo botao("wiki.php","Encliclopédia", "bi-book");
                echo botao("galeria.php", "Galeria","bi-images");
                echo botao("ata.php", "Gerador de ata","bi-journal-text");
            ?>

        </div>
    </div>
<?php } ?>
<?php languageSwitcher(); ?>


<!-- Place any per-page javascript here -->
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
