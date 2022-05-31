<?php
if(file_exists("install/index.php")){
	//perform redirect if installer files exist
	//this if{} block may be deleted once installed
	header("Location: install/index.php");
}

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if(isset($user) && $user->isLoggedIn()){
}
?>
<!--<style>-->
<!--    a.btn-primary {-->
<!--        color: #fff;-->
<!--    }-->
<!--</style>-->
		<div class="card-header">
			<h1 align="center"><?php echo $settings->site_name;?></h1>
			<p align="center">
				<?php
				if($user->isLoggedIn()){?>
					<a class="btn btn-primary" href="users/account.php" role="button"> Perfil &raquo;</a>
                    <a class="btn btn-warning" href="users/logout.php" role="button"> Sair &raquo;</a>
				<?php }else{?>
					<a class="btn btn-warning" href="users/login.php" role="button"><?=lang("SIGNIN_TEXT");?> &raquo;</a>
					<a class="btn btn-info" href="users/join.php" role="button"><?=lang("SIGNUP_TEXT");?> &raquo;</a>
<!--                    <a class="btn btn-warning" href="users/login.php" role="button">--><?//=lang("SIGNIN_TEXT");?><!-- &raquo;</a>-->
				<?php }?>
			</p>
			<br>
		</div>
<?php
if($user->isLoggedIn()){?>
    <div class="container" style="margin-top: 2em;">
        <div class="row">
            <div class="col">
                <a class="btn btn-primary" href="calendario.php" role="button">
                    <i class="bi bi-calendar-event"></i>
                    <p>Calendário</p>
                </a>
            </div>
        </div>
    </div>
<?php }?>
<?php  languageSwitcher();?>


<!-- Place any per-page javascript here -->
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
