<?php
//Anything you put in this file will override the default UserSpice language that's located in
// users/lang/en-US.php
//NOTE: You can also add as many other language keys as you wish and use them wherever you want in
//your project.

//You can test this out by uncommenting the section below and you will note that the menu on the home page changes from the default Home to Homepage


//Emails
$lang = array_merge($lang, array(
    "EML_CONF" => "Confirme seu e-mail",
    "EML_VER" => "Verifique seu e-mail",
    "EML_CHK" => "Por favor, verifique seu e-mail para realizar a verificação. Certifique-se de verificar sua pasta de Spam e Lixo Eletrônico, pois o link de verificação expira em ",
    "EML_MAT" => "Seu e-mail não corresponde.",
    "EML_HELLO" => "Olá de ",
    "EML_HI" => "Olá ",
    "EML_AD_HAS" => "Um administrador redefiniu sua senha.",
    "EML_AC_HAS" => "Um administrador criou sua conta.",
    "EML_REQ" => "Você será solicitado a definir sua senha usando o link acima.",
    "EML_EXP" => "Observe que os links de senha expiram em ",
    "EML_VER_EXP" => "Observe que os links de verificação expiram em ",
    "EML_CLICK" => "Clique aqui para fazer login.",
    "EML_REC" => "Recomenda-se alterar sua senha ao fazer login.",
    "EML_MSG" => "Você tem uma nova mensagem de",
    "EML_REPLY" => "Clique aqui para responder ou ver o tópico",
    "EML_WHY" => "Você está recebendo este e-mail porque foi feita uma solicitação para redefinir sua senha. Se não foi você, desconsidere este e-mail.",
    "EML_HOW" => "Se foi você, clique no link abaixo para continuar com o processo de redefinição de senha.",
    "EML_EML" => "Uma solicitação para alterar seu e-mail foi feita na sua conta de usuário.",
    "EML_VER_EML" => "Obrigado por se inscrever. Depois de verificar seu endereço de e-mail, você estará pronto para fazer login! Clique no link abaixo para verificar seu endereço de e-mail.",

));

//Verification
$lang = array_merge($lang, array(
    "VER_SUC" => "Seu e-mail foi verificado!",
    "VER_FAIL" => "Não foi possível verificar sua conta. Tente novamente.",
    "VER_RESEND" => "Reenviar e-mail de verificação",
    "VER_AGAIN" => "Digite seu endereço de e-mail e tente novamente",
    "VER_PAGE" => "<li>Verifique seu e-mail e clique no link enviado a você</li><li>Concluído</li>",
    "VER_RES_SUC" => "<p>Seu link de verificação foi enviado para seu endereço de e-mail.</p><p>Clique no link do e-mail para concluir a verificação. Verifique sua pasta de spam se o e-mail não estiver na sua caixa de entrada.</p><p>Os links de verificação são válidos apenas para ",
    "VER_OOPS" => "Oops...algo deu errado, talvez um link de redefinição antigo no qual você clicou. Clique abaixo para tentar novamente",
    "VER_RESET" => "Sua senha foi redefinida!",
    "VER_INS" => "<li>Digite seu endereço de e-mail e clique em Redefinir</li> <li>Verifique seu e-mail e clique no link enviado a você.</li>
												<li>Siga as instruções na tela</li>",
    "VER_SENT" => "<p>O link de redefinição de senha foi enviado para seu endereço de e-mail.</p>
			    							<p>Clique no link do e-mail para redefinir sua senha. Certifique-se de verificar sua pasta de spam se o e-mail não estiver em sua caixa de entrada.</p><p>Os links de redefinição são válidos apenas para ",
    "VER_PLEASE" => "Por favor redefina sua senha",
));

$lang = array_merge($lang, array(
    "GEN_SUBMIT" => "Enviar",
    "GEN_EMAIL" => "Email",
    "GEN_FNAME" => "Primeiro Nome",
    "GEN_LNAME" => "Sobrenome",
    "GEN_UNAME" => "Login",
    "GEN_PASS" => "Senha",
));

$lang = array_merge($lang, array(
    "PW_NEW" => "Nova senha",
    "PW_OLD" => "Senha antiga",
    "PW_CONF" => "Confirme a senha",
    "PW_RESET" => "Resetar senha",
    "PW_UPD" => "Senha atualizada",
    "PW_SHOULD" => "A senha deve...",
    "PW_SHOW" => "Exibir senha",
    "PW_SHOWS" => "Exibir senhas",
    "JOIN_TWICE" => "Ser confirmada",
    "GEN_MIN" => "No mínimo",
    "GEN_MAX" => " no máximo ",
    "GEN_CHAR" => "caracteres", //as in characters
    "GEN_AND" => "e",
));

?>
