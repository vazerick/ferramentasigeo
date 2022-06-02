<?php
require_once '../init.php';

$filename = currentPage();
$db = DB::getInstance();
$ip = ipCheck();
logger("", "CronRequest", "Cron request from $ip.");
$settings = $db->query("SELECT * FROM settings")->first();
if($settings->cron_ip != ''){
if($ip != $settings->cron_ip && $ip != '127.0.0.1'){
	logger("","CronRequest","Cron request DENIED from $ip.");
	die;
	}
}
$errors = $successes = [];

//your code goes here...
//do whatever you want to do and it will be run automatically when the cron job is triggered.

//Função que escreve a linha de um bloco de alertas

function linha_mensagem_evento($evento, $hoje = false)
{
	if ($hoje) {
		if ($evento->allDay == "1") {
			$mensagem = "Sem horário";
		} else {
			$mensagem = escreve_data($evento->start, "H:i");
		}
	} else {
		if ($evento->allDay == "1") {
			$mensagem = escreve_data($evento->start, "d/m/Y");
		} else {
			$mensagem = escreve_data($evento->start, "d/m/Y H:i");
		}
	}
	$mensagem .= " - " . $evento->title;
	return $mensagem;
}

//Função para gerar a lista de alertas

function gerar_lista($email, $matriz, $titulo, $vazio)
{
	$mensagem = "";
	if (array_key_exists($email, $matriz)) {
		if (count($matriz[$email])) {
			$mensagem = "<p><strong>" . $titulo . "</p></strong>";
			$mensagem .= "<p>" . implode("<p></p>", $matriz[$email]) . "</p>";
		}
	}
	if ($mensagem == "") {
		$mensagem = "<p><strong>" . $vazio . "</strong><p>";
	}
	return $mensagem;
}

$alerta_eventos_hoje = [];
$alerta_eventos_prox = [];

//Lê a lista de e-mails cadastrados nos alertas

$db->query("SELECT * FROM alertas");
$alertas = $db->results();

$db->query("SELECT MAX(dia) as max FROM alertas");
$max_evento = $db->results()[0]->max;

//Lê os prazos cadastrados

$alerta_prazos = [];
$db->query("SELECT * FROM prazos");
$prazos = $db->results();

$db->query("SELECT MAX(alerta) as max FROM prazos");
$max_prazos = $db->results()[0]->max;

//Lê a lista de grupos com usuários cadastrados

$db->query("SELECT DISTINCT permission_id FROM user_permission_matches ORDER BY permission_id");
$temp = ($db->results());
$grupos = [];
foreach ($temp as $i) {
    $grupos[] = $i->permission_id;
}
unset($temp);

//Matriz com os usuários de cada grupo

$grupos_usuarios = array();
foreach ($grupos as $grupo) {
    $grupos_usuarios[$grupo] = [];
    $db->query("SELECT user_id FROM user_permission_matches WHERE permission_id = " . $grupo);
    foreach ($db->results() as $row) {
        $grupos_usuarios[$grupo][] = $row->user_id;
    }
}

$hoje = date("Y-m-d 00:00:00");

//Lê os eventos do calendário de hoje até a maior data de corte

$data_max = strtotime(date("Y-m-d 23:59:59") . "+ " . $max_evento . " days");
$data_max = date("Y-m-d H:i:s", $data_max);

$conditions = array();

$conditions[] = "start >= '" . $hoje . "'";
$conditions[] = "end <= '" . $data_max . "'";
$conditions[] = "grupo IN (" . implode(",", $grupos) . ")";

$conditions = implode(" AND ", $conditions);

$db->query("SELECT * FROM calendario WHERE " . $conditions . " ORDER BY start ");
$eventos = ($db->results());

//Para cada evento, para cada usuário do grupo do evento, gera uma lista de alerta

foreach ($eventos as $evento) {
    $diff = diff_dias($evento->start, $hoje);
    foreach ($grupos_usuarios[$evento->grupo] as $item) {
        $key = array_search($item, array_column($alertas, 'usuario'));
        if (intval($alertas[$key]->dia) >= $diff) {
            if ($diff > 0) {
                $alerta_eventos_prox[$alertas[$key]->email][] = linha_mensagem_evento($evento);
            } else {
                $alerta_eventos_hoje[$alertas[$key]->email][] = linha_mensagem_evento($evento, true);
            }
        }
    }

}

//Lê os prazos com vencimento até a maior data de corte

$data_max = strtotime(date("Y-m-d 23:59:59") . "+ " . $max_prazos . " days");
$data_max = date("Y-m-d H:i:s", $data_max);

$conditions = array();

$conditions[] = "fim <= '" . $data_max . "'";
$conditions[] = "grupo IN (" . implode(",", $grupos) . ")";

$conditions = implode(" AND ", $conditions);

$db->query("SELECT * FROM prazos WHERE " . $conditions . " ORDER BY fim ");
$prazos_lista = ($db->results());

//Para cada prazo, para cada usuário do grupo do prazo, gera uma lista de alerta

foreach ($prazos_lista as $prazo) {
    $diff = diff_dias($prazo->fim, $hoje);
    $linha = [];
    if ($diff <= intval($prazo->alerta)) {
        $linha = escreve_data($prazo->fim, "d/m/Y") . " - " . $prazo->titulo;
        if ($diff > 1) {
            $diff = "Vence em " . $diff . " dias.";
        } elseif ($diff == 1) {
            $diff = "Vence em " . $diff . " dia.";
        } elseif ($diff == 0) {
            $diff = "PRAZO VENCE HOJE!";
        } else {
            $diff = "VENCIDO há " . -$diff . " dias!";
        }
        $linha .= ": " . $diff;
        foreach ($grupos_usuarios[$prazo->grupo] as $item) {
            $key = array_search($item, array_column($alertas, 'usuario'));
            $alerta_prazos[$alertas[$key]->email][] = $linha;
        }
    }
}

//Gera a matriz com as mensagens a serem enviadas

$lista_mensagens = [];

foreach ($alertas as $alerta) {
    $mensagem = [];

    $mensagem[] = gerar_lista($alerta->email, $alerta_prazos, "Prazos vencendo:", "Sem prazos vencendo.");
    $mensagem[] = gerar_lista($alerta->email, $alerta_eventos_hoje, "Compromissos de hoje:", "Sem compromissos para hoje.");
    $mensagem[] = gerar_lista($alerta->email, $alerta_eventos_prox, "Compromissos dos próximos dias:", "Sem compromissos para os próximos dias.");


    if ($mensagem != "") {
        $mensagem = "<p><strong>" . escreve_data($hoje, "m/d/Y") . "</strong></p><p>" . implode("</p><br/><p>", $mensagem) . "</p>";
        $lista_mensagens[] = [
            "email" => $alerta->email,
            "assunto" => "[AGENDA] " . escreve_data($hoje, "m/d/Y"),
            "mensagem" => $mensagem,
        ];
    }
}

//Envia cada mensagem

foreach ($lista_mensagens as $mensagem) {
    email($mensagem["email"], $mensagem["assunto"], $mensagem["mensagem"]);
}

//your code ends here.

$from = Input::get('from');
if ($from != NULL && $currentPage == $filename) {
    $query = $db->query("SELECT id,name FROM crons WHERE file = ?", array($filename));
    $results = $query->first();
    $cronfields = array(
        'cron_id' => $results->id,
        'datetime' => date("Y-m-d H:i:s"),
        'user_id' => $user_id);
    $db->insert('crons_logs', $cronfields);
    Redirect::to('/' . $from);
}
?>
