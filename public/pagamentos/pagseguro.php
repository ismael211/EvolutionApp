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

// Verifica se a fatura do pagseguro existe
$total_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
if($total_fatura->num_rows > 0) {

// Verifica se a forma de pagamento da fatura é pagseguro
if($dados_formas_pagamento[tipo_pagamento] != 'pagseguro') {
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

// Dados da conta no Pagseguro
$pagseguro_email = $dados_formas_pagamento[pagseguro_email];
$pagseguro_taxa = $dados_formas_pagamento[pagseguro_taxa];

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
$fatura_valor = number_format($faturas_valor_total+$pagseguro_taxa,2,",",".");
$fatura_valor = str_replace(",", "", $fatura_valor);

} else {

$fatura_codigo = $dados_fatura[codigo];
$fatura_descricao = $descricao;
$fatura_valor = number_format($dados_fatura[valor]+$pagseguro_taxa,2,",",".");
$fatura_valor = str_replace(",", "", $fatura_valor);

}

// Dados Cliente
$telefone_partes = explode(') ', $dados_cliente[fone]);
$telefone_partes[0] = str_replace("(", "", $telefone_partes[0]);

$cliente_nome = $dados_cliente[nome];
$cliente_cep = preg_replace('#[^0-9]#','',$dados_cliente[cep]);
$cliente_endereco = $dados_cliente[endereco];
$cliente_numero = $dados_cliente[numero];
$cliente_complemento = $dados_cliente[complemento];
$cliente_bairro = $dados_cliente[bairro];
$cliente_cidade = $dados_cliente[cidade];
$cliente_estado = sigla_estado($dados_cliente[estado]);
$cliente_ddd_telefone = $telefone_partes[0];
$cliente_telefone = $telefone_partes[1];
$cliente_email = $dados_cliente[email1];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pagamento PagSeguro</title>
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   window.onload = function() {
    document.pagseguro.submit();
   };
</script>
</head>

<body onload="document.pagseguro.submit();">
<div align="center" class="texto_padrao2" style="margin:30px">Por favor aguarde, voc&ecirc; está sendo redirecionado para o site do PagSeguro.com.br<br />Se você tem um anti-popup instalado, desabilite ele e atualize a página.<br /><br /><img src="img/ajax-loader.gif" alt="Carregando..." /></div>
<form action="https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx" method="post" name="pagseguro">
<input type="hidden" name="email_cobranca" value="<?php echo $pagseguro_email; ?>" />
<input type="hidden" name="tipo" value="CP" />
<input type="hidden" name="moeda" value="BRL" />
<input type="hidden" name="item_id_1" value="<?php echo $fatura_codigo; ?>" />
<input type="hidden" name="item_descr_1" value="<?php echo $fatura_descricao; ?>" />
<input type="hidden" name="item_quant_1" value="1" />
<input type="hidden" name="item_valor_1" value="<?php echo $fatura_valor; ?>" />
<input type="hidden" name="cliente_nome" value="<?php echo $cliente_nome; ?>" />
<input type="hidden" name="cliente_cep" value="<?php echo $cliente_cep; ?>" />
<input type="hidden" name="cliente_end" value="<?php echo $cliente_endereco; ?>" />
<input type="hidden" name="cliente_num" value="<?php echo $cliente_numero; ?>" />
<input type="hidden" name="cliente_compl" value="<?php echo $cliente_complemento; ?>" />
<input type="hidden" name="cliente_bairro" value="<?php echo $cliente_bairro; ?>" />
<input type="hidden" name="cliente_cidade" value="<?php echo $cliente_cidade; ?>" />
<input type="hidden" name="cliente_uf" value="<?php echo $cliente_estado; ?>" />
<input type="hidden" name="cliente_pais" value="BRA" />
<input type="hidden" name="cliente_ddd" value="<?php echo $cliente_ddd_telefone; ?>" />
<input type="hidden" name="cliente_tel" value="<?php echo $cliente_telefone; ?>" />
<input type="hidden" name="cliente_email" value="<?php echo $cliente_email; ?>" />
</form>
</body>
</html>
