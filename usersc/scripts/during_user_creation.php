<?php
// This script is really useful for doing additional things when a user is created.

// You have access to two things that will really be helpful.
//
// You have the new user id for your new user. Comment out below to see it.

//You also have access to everything that was submitted in the form.

 $adm = array();

 $db->query("SELECT user_id FROM user_permission_matches WHERE permission_id = 2");
 foreach ($db->results() as $row) {
     $db->query("SELECT email FROM users WHERE id = " . $row->user_id);
     foreach ($db->results() as $row2){
         $adm[] = $row2->email;
     }
 }

$mensagem = array();
$mensagem[] = "<p><strong>NOVA SOLICITAÇÃO DE CADASTRO</strong></p>";
$mensagem[] = "<p><strong>Nome:</strong> " . $_POST["fname"] . " " . $_POST["lname"] . "</p>";
$mensagem[] = "<p><strong>Setor: " .$_POST["setor"] . "</p>";
$mensagem[] = "<p><strong>Usuário:</strong> " . $_POST["username"] . "</p>";
$mensagem[] = "<p><strong>E-mail:</strong> " . $_POST["email"] . "</p>";

$mensagem = implode("", $mensagem);

email("erick.vaz@ufrgs.br", "[FERRAMENTAS IGEO - CADASTRO] #" . $theNewId . " - " . $_POST["fname"], $mensagem);

echo "<h3> Cadastro realizado </h3>";
echo "<p><strong> Aguarde o revisar sua solicitação e adicioná-lo a uma equipe de trabalho.</strong></p>";

//If you added additional fields to the join form, you can process them here.
//For example, in additional_join_form_fields.php we have a sample form field called account_id.
// You may wish to do additional validation, but we'll keep it simple. Uncomment out the code below to test it.

// The format of the array is ['column_name'=>Data_for_column]

// $db->update('users',$theNewId,['account_id'=>Input::get('account_id')]);

// You'll notice that the account id is now in the database!

// Even if you do not want to add additional fields to the the join form, this is a great opportunity to add this user to another database table.
// Get creative!

// The script below will automatically login a user who just registered if email activation is not turned on
//PLEASE NOTE: This will also run during the user creation process that happens when the admin creates a user which is good, except you could
//find yourself logged in as the user you just created.  :)
// $e = $db->query("SELECT email_act FROM email")->first();
// if($e->email_act != 1 && !$user->isLoggedIn()){
//   $user = new User();
// $login = $user->loginEmail(Input::get('email'), trim(Input::get('password')), 'off');
// if(!$login){Redirect::to('login.php?err=There+was+a+problem+logging+you+in+automatically.');}
//where the user goes just after login is in usersc/scripts/custom_login_script.php
// }

