prazo-deletar.php<?php
require_once 'users/init.php';


$db->query("SELECT * FROM comissao_projetos WHERE id = '" . $_GET["id"] . "'");

if ((count($db->results()) == 0)) {
    echo "PROJETO NÃO LOCALIZADO";
    die();
}

$projeto = $db->results()[0];
$db->query("SELECT * FROM permissions WHERE id = '" . $projeto->id_responsavel . "'");

$assunto = "[PROJETO DELETADO] " . $projeto->nome;
$mensagem = "<strong> Projeto " . $projeto->nome . " deletado!</strong>";
$mensagem .= "<p>Comissão: " . $db->results()[0]->name . ".</p>";
$mensagem .= "<p>Nome: " . $projeto->nome . ".</p>";
$mensagem .= "<p>Descrição: " . $projeto->descricao . ".</p>";

email_comissao($projeto->id, $assunto, $mensagem);


$db->deleteById("comissao_projetos", $projeto->id);
logger($user->data()->id, 'Projeto', 'Deletado o projeto ' . $prazo->id);
header('Location: comissao-gestao.php');