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

$sql_forma_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[forma_pagto]."'");
$dados_formas_pagamento = $sql_forma_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
} elseif($dados_fatura[tipo] == 's') {

$sql_servico = $conexao->query("SELECT * FROM servicos_adicionais where codigo = '".$dados_fatura[codigo_servico]."'");
$dados_servico = $sql_servico->fetch_array(MYSQLI_ASSOC);

$sql_servico_modelo = $conexao->query("SELECT * FROM servicos_modelos where codigo = '".$dados_servico[codigo_servico]."'");
$dados_servico_modelo = $sql_servico_modelo->fetch_array(MYSQLI_ASSOC);

$sql_forma_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_forma_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
} elseif($dados_fatura[tipo] == 'd') {

$sql_dominio = $conexao->query("SELECT * FROM dominios_registro where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_dominio->fetch_array(MYSQLI_ASSOC);

$sql_forma_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_forma_pagamento->fetch_array(MYSQLI_ASSOC);

}

// Verifica se a fatura do pagseguro existe
$total_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
if($total_fatura->num_rows > 0) {

// Verifica se a forma de pagamento da fatura é pagseguro
if($dados_formas_pagamento[tipo_pagamento] != 'pagamentodigital') {
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

// Dados da conta no Pagamento Digital
$pagamentodigital_conta = $dados_formas_pagamento[pagamentodigital_conta];
$pagamentodigital_taxa = $dados_formas_pagamento[pagamentodigital_taxa];

// Dados Fatura
if(strlen($dados_fatura[descricao]) > 97) {
$descricao = substr($dados_fatura[descricao], 0, 97) . "...";
} else {
$descricao = $dados_fatura[descricao];
}

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
$fatura_valor = number_format($faturas_valor_total+$pagamentodigital_taxa,2,".","");
$fatura_valor = str_replace(",", "", $fatura_valor);

} else {

$fatura_codigo = $dados_fatura[codigo];
$fatura_descricao = $descricao;
$fatura_valor = number_format($dados_fatura[valor]+$pagamentodigital_taxa,2,".","");
$fatura_valor = str_replace(",", "", $fatura_valor);

}

// Dados Cliente

$cliente_nome = $dados_cliente[nome];
$cliente_telefone = preg_replace('#[^0-9]#','',$dados_cliente[fone]);
$cliente_cep = preg_replace('#[^0-9]#','',$dados_cliente[cep]);
$cliente_endereco = $dados_cliente[endereco].",".$dados_cliente[numero];
$cliente_cidade = $dados_cliente[cidade];
$cliente_estado = sigla_estado($dados_cliente[estado]);
$cliente_email = $dados_cliente[email1];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pagamento Digital</title>
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   window.onload = function() {
    document.pagamentodigital.submit();
   };
</script>
</head>

<body onload="document.pagamentodigital.submit();">
<div align="center" class="texto_padrao2" style="margin:30px">Por favor aguarde, voc&ecirc; está sendo redirecionado para o site do PagamentoDigital.com.br<br />Se você tem um anti-popup instalado, desabilite ele e atualize a página.<br /><br /><img src="img/ajax-loader.gif" alt="Carregando..." /></div>
<form action="https://www.pagamentodigital.com.br/pagamento/pd.php" method="post" name="pagamentodigital">
<input name="cod_loja" type="hidden" value="<?php echo $pagamentodigital_conta; ?>">
<input name="id_pedido" type="hidden" value="<?php echo $fatura_codigo; ?>">
<input name="produto" type="hidden" value="<?php echo $fatura_descricao; ?>">
<input name="valor" type="hidden" value="<?php echo $fatura_valor; ?>">
<input name="nome" type="hidden" value="<?php echo $cliente_nome; ?>">
<input name="email" type="hidden" value="<?php echo $cliente_email; ?>">
<input name="telefone" type="hidden" value="<?php echo $cliente_telefone; ?>">
<input name="endereco" type="hidden" value="<?php echo $cliente_endereco; ?>">
<input name="cidade" type="hidden" value="<?php echo $cliente_cidade; ?>">
<input name="estado" type="hidden" value="<?php echo $cliente_estado; ?>">
<input name="cep" type="hidden" value="<?php echo $cliente_cep; ?>">
<input name="free" type="hidden" value="<?php echo $fatura_codigo; ?>" >
</form>
</body>
</html>
