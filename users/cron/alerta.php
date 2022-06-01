<?php
require_once '../init.php';

function linha_mensagem_evento($evento, $hoje=false){
	if($hoje){
		if($evento->allDay == "1"){
			$mensagem = "Sem horário";
		}else{
			$mensagem = escreve_data($evento->start, "H:i");
		}
	}else{
		if($evento->allDay == "1"){
			$mensagem = escreve_data($evento->start, "d/m/Y");
		}else{
			$mensagem = escreve_data($evento->start, "d/m/Y H:i");
		}
	}
	$mensagem .= " - " . $evento->title;
	return $mensagem;
}

$filename = currentPage();
$db = DB::getInstance();
$ip = ipCheck();
logger("","CronRequest","Cron request from $ip.");
$settings = $db->query("SELECT * FROM settings")->first();
//if($settings->cron_ip != ''){
//if($ip != $settings->cron_ip && $ip != '127.0.0.1'){
//	logger("","CronRequest","Cron request DENIED from $ip.");
//	die;
//	}
//}
$errors = $successes = [];

//your code goes here...
//do whatever you want to do and it will be run automatically when the cron job is triggered.

$alerta_eventos_hoje = [];
$alerta_eventos_prox = [];

$db->query("SELECT * FROM alertas");
$alertas = $db->results();

$db->query("SELECT MAX(dia) as max FROM alertas");

$max_evento = $db->results()[0]->max;
$db->query("SELECT DISTINCT permission_id FROM user_permission_matches ORDER BY permission_id");
$temp = ($db->results());
$grupos = [];
foreach($temp as $i)
{
	$grupos[]=$i->permission_id;
}
unset($temp);

$grupos_usuarios = array();
foreach($grupos as $grupo)
{
	$grupos_usuarios[$grupo] = [];
	$db->query("SELECT user_id FROM user_permission_matches WHERE permission_id = " . $grupo);
	foreach ($db->results() as $row){
		$grupos_usuarios[$grupo][] = $row->user_id;
	}
}

$hoje = date("Y-m-d 00:00:00");
$data_max = strtotime(date("Y-m-d 23:59:59")."+ " . $max_evento . " days");
$data_max = date("Y-m-d H:i:s",$data_max);

$conditions[] = "start >= '" . $hoje . "'";
$conditions[] = "end <= '" . $data_max . "'";
$conditions[] = "grupo IN (" . implode(",", $grupos) . ")";

$conditions = implode(" AND ", $conditions);

$db->query("SELECT * FROM calendario WHERE " . $conditions ." ORDER BY start ");
$eventos = ($db->results());

foreach ($eventos as $evento){
	$diff = diff_dias($evento->start, $hoje);
	foreach ($grupos_usuarios[$evento->grupo] as $item){
		$key = array_search($item, array_column($alertas, 'usuario'));
		if (intval($alertas[$key]->dia) >= $diff){
			if($diff > 0){
				$alerta_eventos_prox[$alertas[$key]->email][] = linha_mensagem_evento($evento);
			} else {
				$alerta_eventos_hoje[$alertas[$key]->email][] = linha_mensagem_evento($evento, true);
			}
		}
	}

}

$lista_mensagens = [];

function gerar_lista($email, $matriz, $titulo, $vazio){
	$mensagem = "";
	if(	array_key_exists($email, $matriz)){
		if(count($matriz[$email])){
			$mensagem = $titulo . "\n";
			$mensagem .= implode("\n", $matriz[$email]);
		}
	}
	if($mensagem == ""){
		$mensagem = $vazio;
	}
	return $mensagem;
}

foreach ($alertas as $alerta){
	$mensagem = [];
//	if(	array_key_exists($alerta->email, $alerta_eventos_hoje)){
//		if(count($alerta_eventos_hoje[$alerta->email])){
//			$mensagem = "Compromissos de hoje:\n";
//			$mensagem .= implode("\n", $alerta_eventos_hoje[$alerta->email]);
//		}
//	}
	$mensagem[] = gerar_lista($alerta->email, $alerta_eventos_hoje, "Compromissos de hoje:", "Sem compromissos para hoje");
	$mensagem[] = gerar_lista($alerta->email, $alerta_eventos_prox, "Compromissos dos próximos dias:", "Sem compromissos para os próximos dias");
//	if(	array_key_exists($alerta->email, $alerta_eventos_prox)){
//		if(count($alerta_eventos_prox[$alerta->email])){
//			$mensagem .= "Compromissos dos próximos dias:\n";
//			$mensagem .= implode("\n", $alerta_eventos_prox[$alerta->email]);
//		}
//	}

	if($mensagem != ""){
		$mensagem = escreve_data($hoje, "m/d/Y") . "\n\n" . implode("\n\n", $mensagem);
		$lista_mensagens[] = [
			"email" =>  $alerta->email,
			"assunto" => "[AGENDA]" . escreve_data($hoje, "m/d/Y"),
			"mensagem" => $mensagem,
		];
	}
}
dump($lista_mensagens);




//your code ends here.

$from = Input::get('from');
if($from != NULL && $currentPage == $filename) {
	$query = $db->query("SELECT id,name FROM crons WHERE file = ?",array($filename));
	$results = $query->first();
		$cronfields = array(
		'cron_id' => $results->id,
		'datetime' => date("Y-m-d H:i:s"),
		'user_id' => $user_id);
		$db->insert('crons_logs',$cronfields);
	Redirect::to('/'. $from);
}
?>
