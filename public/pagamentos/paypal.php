<?php
require('../../ds8.php');
require_once('inc/funcoes.php');

$conexao = new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);

// Decodifica código da fatura
$_GET[codigo] = encode_decode($_GET[codigo],"D");

$sql_empresa = $conexao->query("SELECT * FROM empresa");
$dados_empresa = $sql_empresa->fetch_array(MYSQLI_ASSOC);

$sql_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
$dados_fatura = $sql_fatura->fetch_array(MYSQLI_ASSOC);

$sql_dados_cliente = $conexao->query("SELECT * FROM clientes where codigo = '".$dados_fatura[codigo_cliente]."'");
$dados_cliente = $sql_dados_cliente->fetch_array(MYSQLI_ASSOC);

// Verifica o tipo de fatura para obter a forma de pagamento
// Fatura de Hospedagem(geral) - tipo: h
if($dados_fatura[tipo] == 'h') {

$sql_dominio = $conexao->query("SELECT * FROM dominios where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_dominio->fetch_array(MYSQLI_ASSOC);

$sql_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
} elseif($dados_fatura[tipo] == 's') {

$sql_servico = $conexao->query("SELECT * FROM servicos_adicionais where codigo = '".$dados_fatura[codigo_servico]."'");
$dados_servico = $sql_servico->fetch_array(MYSQLI_ASSOC);

$sql_servico_modelo = $conexao->query("SELECT * FROM servicos_modelos where codigo = '".$dados_servico[codigo_servico]."'");
$dados_servico_modelo = $sql_servico_modelo->fetch_array(MYSQLI_ASSOC);

$sql_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
} elseif($dados_fatura[tipo] == 'd') {

$sql_dominio = $conexao->query("SELECT * FROM dominios_registro where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_dominio->fetch_array(MYSQLI_ASSOC);

$sql_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamento->fetch_array(MYSQLI_ASSOC);

}

// Verifica se a fatura do paypal existe
$total_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
if($total_fatura->num_rows > 0) {

// Verifica se a forma de pagamento da fatura é paypal
if($dados_formas_pagamento[tipo_pagamento] != 'paypal') {
echo "<script>
       alert(\"Atenção!\\n \\nFatura não encontrada!\\n \\nEntre em contato com nosso atendimento.\\n \\nVocê será redirecionado para a Central do Cliente.\");
 window.location = '".$dados_empresa[url_sistema]."/';
 </script>";
}

} else {
echo "<script>
       alert(\"Atenção!\\n \\nFatura não encontrada!\\n \\nEntre em contato com nosso atendimento.\\n \\nVocê será redirecionado para a Central do Cliente.\");
 window.location = '".$dados_empresa[url_sistema]."/';
 </script>";
}

// Dados PayPal
$paypal_email = $dados_formas_pagamento[paypal_email];
$paypal_cotacao = $dados_formas_pagamento[paypal_cotacao];
$paypal_taxa = number_format($dados_formas_pagamento[paypal_taxa],2,".","");

// Dados Fatura
// Verifica se existem faturas do cliente vencendo este mes e gera o valor total das faturas
list($ano_atual,$mes_atual,$dia) = explode("-",$dados_fatura[data_vencimento]);

$total_faturas_cliente = $conexao->query("SELECT * FROM faturas where codigo_cliente = '".$dados_cliente[codigo]."' AND MONTH(data_vencimento) = '$mes_atual' AND YEAR(data_vencimento) = '$ano_atual'");

if($dados_cliente[unificar_faturas] == 'sim' && $total_faturas_cliente->num_rows > 1) {

$sql_faturas = $conexao->query("SELECT * FROM faturas where status = 'off' AND codigo_cliente = '".$dados_cliente[codigo]."' AND MONTH(data_vencimento) = '$mes_atual' AND YEAR(data_vencimento) = '$ano_atual'");
while ($dados_faturas_unificadas = $sql_faturas->fetch_array(MYSQLI_ASSOC)) {

$faturas_valor_total += $dados_faturas_unificadas[valor];

}

$fatura_codigo = $dados_fatura[codigo];
$fatura_descricao = "Suas faturas estão unificadas, para maiores detalhes sobre suas faturas, acesse a Central do Cliente";
$fatura_valor = number_format($faturas_valor_total/$paypal_cotacao,2,".","");

} else {

if(strlen($dados_fatura[descricao]) > 124) {
$fatura_descricao = substr($dados_fatura[descricao], 0, 124) . "...";
} else {
$fatura_descricao = $dados_fatura[descricao];
}

$fatura_codigo = $dados_fatura[codigo];
$fatura_valor = number_format($dados_fatura[valor]/$paypal_cotacao,2,".","");

}

// Dados Cliente
$cliente_nome = explode(' ', $dados_cliente[nome]);

$cliente_nome1 = $cliente_nome[0];
$cliente_nome2 = $cliente_nome[1].$cliente_nome[2].$cliente_nome[3].$cliente_nome[4].$cliente_nome[5];
$cliente_email = $dados_cliente[email1];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pagamento PayPal</title>
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   window.onload = function() {
    document.paypal.submit();
   };
</script>
</head>

<body onload="document.paypal.submit();">
<div align="center" class="texto_padrao2" style="margin:30px">Por favor aguarde, voc&ecirc; está sendo redirecionado para o site do PayPal.com<br />Se você tem um anti-popup instalado, desabilite ele e atualize a página.<br /><br /><img src="img/ajax-loader.gif" alt="Carregando..." /></div>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
<input type="hidden" name="item_name" value="<?php echo $fatura_descricao; ?>">
<input type="hidden" name="amount" value="<?php echo $fatura_valor; ?>">
<input type="hidden" name="tax" value="<?php echo $paypal_taxa; ?>">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="custom" value="<?php echo $fatura_codigo; ?>">
<input type="hidden" name="return" value="<?php echo $paypal_url_retorno; ?>">
<input type="hidden" name="cancel_return" value="<?php echo $dados_empresa[url_sistema]."/clientes/index.php?pagina=Financeiro.Fatura&codigo=".$fatura_codigo; ?>">
<input type="hidden" name="first_name" value="<?php echo $cliente_nome1; ?>">
<input type="hidden" name="last_name" value="<?php echo $cliente_nome2; ?>">
<input type="hidden" name="email" value="<?php echo $cliente_email; ?>">
</form>
</body>
</html>
