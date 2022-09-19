<?php
header("Content-Type: text/html;  charset=ISO-8859-1",true);
//////////////////////////////////////////////////////////////////////////
// Isistem Gerenciador Financeiro para Hosts  		                    //
// DescriÃ§Ã£o: Sistema de Gerenciamento de Clientes		                //
// Site: www.isistem.com.br       										//
//////////////////////////////////////////////////////////////////////////

include('ds8.php');
//require_once('Accounting.php');
require_once('class.xmlapi.php');
require_once('class.whois.php');
require_once('class.mail.php');

// Banco de FunÃ§Ãµes em PHP do sistema
$mysqli = new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);
// Uso global
$sql_dados_empresa = $mysqli->query("SELECT * FROM empresa");
$dados_empresa = $sql_dados_empresa->fetch_array(MYSQLI_ASSOC);

$sql_dados_sistema = $mysqli->query("SELECT * FROM sistema");
$dados_sistema = $sql_dados_sistema->fetch_array(MYSQLI_ASSOC);




// Aumenta dia
function dataAumentaDia($dias){

	if ($dias == 0) {
		return date ( "d/m/Y" );
	}
    $datastamp = '86400' * $dias + mktime(0,0,0, date('m'), date('d'), date('Y'));
    return date ( "d/m/Y" ,$datastamp );
}


// Metodo que gera Key
 function generateKey()
	{

		$a = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$b = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$c = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$d = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$e = strtoupper(substr(md5(rand(11111,99999)), 5, 5));

		return "$a-$b-$c-$d-$e";

	}



// FunÃ§Äƒo para codificar e decodificar strings
function encode_decode($texto, $tipo = "E") {

  if($tipo == "E") {

	  $sesencoded = $texto;
	  $num = mt_rand(0,3);
	  for($i=1;$i<=$num;$i++)
	  {
	     $sesencoded = base64_encode($sesencoded);
	  }
	  $alpha_array =  array('Y','D','U','R','P','S','B','M','A','T','H');
	  $sesencoded =
	  $sesencoded."+".$alpha_array[$num];
	  $sesencoded = base64_encode($sesencoded);
	  return $sesencoded;

  }
  else
  {
		$alpha_array =array('Y','D','U','R','P','S','B','M','A','T','H');

		$decoded = base64_decode($texto);

		//list($decoded,$letter) = split("\+",$decoded);
		list($decoded,$letter) = preg_split("/\+/",$decoded);

		for($i=0;$i<count($alpha_array);$i++)
		{
		   if($alpha_array[$i] == $letter)
		   break;
		}
		for($j=1;$j<=$i;$j++)
		{
		  $decoded = base64_decode($decoded);
		}
		return $decoded;

  }
}


// formas de pagamento
function forma_pagamento($forma_pagto,$fatura) {

	include('ds8.php');
	$mysqli =new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);
	$sql_dados_empresa = $mysqli->query("SELECT * FROM empresa");
	$dados_empresa = $sql_dados_empresa->fetch_array(MYSQLI_ASSOC);

	$sql_dados_forma_pagamento = $mysqli->query("SELECT * FROM formas_pagamento where codigo = '".$forma_pagto."'");
	$dados_forma_pagamento = $sql_dados_forma_pagamento->fetch_array(MYSQLI_ASSOC);

	if($dados_forma_pagamento["tipo_pagamento"] == 'deposito') {
	return "
	Banco: $dados_forma_pagamento[banco]<br>
	Agência: $dados_forma_pagamento[agencia]<br>
	Conta: $dados_forma_pagamento[conta]<br>
	Tipo: $dados_forma_pagamento[tipo_conta]<br>
	Documento: $dados_forma_pagamento[cpf_cnpj]<br>
	Titular: $dados_forma_pagamento[cedente]<br>
	";
	} elseif($dados_forma_pagamento["tipo_pagamento"] == 'pagseguro') {

	// Codifica cÃ³digo da fatura
	$fatura = encode_decode($fatura,"E");

	return "Clique no botão/link abaixo, voçê será direcionado para o site PagSeguro.com.br para completar seu pagamento em ambiente seguro.<br><br><a href=\"http://pag.isistem.com.br/pagseguro.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"https://pagseguro.uol.com.br/Security/Imagens/btnPagarBR.jpg\" border=\"0\" alt=\"Pague com PagSeguro - É rápido, grátis e seguro!\" /></a>";

	} elseif($dados_forma_pagamento["tipo_pagamento"] == 'sendep') {

	// Codifica cÃ³digo da fatura
	$fatura = encode_decode($fatura,"E");

	return "Clique no botão abaixo, voçê será direcionado para o site Sendep.com.br para completar seu pagamento em ambiente seguro.<br><br><a href=\"http://pag.isistem.com.br/sendep.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"http://www.sendep.com.br/buttons/paynow1.gif\" border=\"0\" alt=\"Pagamento Eletrônico Facilitado\" /></a>";

	} elseif($dados_forma_pagamento["tipo_pagamento"] == 'f2b') {

	// Codifica cÃ³digo da fatura
	$fatura = encode_decode($fatura,"E");

	return "Clique no botão abaixo, voçê será direcionado para o site F2B.com.br para completar seu pagamento em ambiente seguro.<br><br><a href=\"http://pag.isistem.com.br/f2b.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"http://pag.isistem.com.br/images/botoes/Botao_F2B.jpg\" border=\"0\" alt=\"Pagamento Eletrônico Facilitado\" /></a>";

	} elseif($dados_forma_pagamento["tipo_pagamento"] == 'paypal') {

	// Codifica cÃ³digo da fatura
	$fatura = encode_decode($fatura,"E");

	return "Clique no botão abaixo, voçê será direcionado para o site PayPal.com para completar seu pagamento em ambiente seguro.<br><br><a href=\"http://pag.isistem.com.br/paypal.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"http://pag.isistem.com.br/images/botoes/Botao_PayPal.jpg\" border=\"0\" alt=\"Pagamento Eletrônico PayPal\" /></a>";

	} elseif($dados_forma_pagamento["tipo_pagamento"] == 'pagamentodigital') {

	// Codifica cÃ³digo da fatura
	$fatura = encode_decode($fatura,"E");

	return "Clique no botão abaixo, voçê será direcionado para o site PagamentoDigital.com para completar seu pagamento em ambiente seguro.<br><br><a href=\"http://pag.isistem.com.br/pagamentodigital.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"http://pag.isistem.com.br/images/botoes/Botao_PagamentoDigital.jpg\" border=\"0\" alt=\"Pagamento Digital\" /></a>";

	} elseif($dados_forma_pagamento["tipo_pagamento"] == 'moip') {

	// Codifica cÃ³digo da fatura
	$fatura = encode_decode($fatura,"E");

	return "Clique no botão abaixo, voçê será direcionado para o site MoIP.com para completar seu pagamento em ambiente seguro.<br><br><a href=\"http://pag.isistem.com.br/moip.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"http://pag.isistem.com.br/images/botoes/Botao_MoIP.png\" border=\"0\" alt=\"MoIP\" /></a>";

	} else {

	// Codifica cÃ³digo da fatura
	$fatura = encode_decode($fatura,"E");

	$botao_boleto = $dados_empresa["url_sistema"]."/img/botoes/".str_replace("php", "jpg", $dados_forma_pagamento["tipo_pagamento"]);

	return "<a href=\"http://pag.isistem.com.br/boleto.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"".$botao_boleto."\" border=\"0\" alt=\"Visualizar Boleto\" /></a>";
	}
}
