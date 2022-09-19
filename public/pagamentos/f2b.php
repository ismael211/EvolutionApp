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

$sql_formas_pagamentos = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamentos->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
} elseif($dados_fatura[tipo] == 's') {

$sql_servico = $conexao->query("SELECT * FROM servicos_adicionais where codigo = '".$dados_fatura[codigo_servico]."'");
$dados_servico = $sql_servico->fetch_array(MYSQLI_ASSOC);

$sql_servico_modelo = $conexao->query("SELECT * FROM servicos_modelos where codigo = '".$dados_servico[codigo_servico]."'");
$dados_servico_modelo = $sql_servico_modelo->fetch_array(MYSQLI_ASSOC);

$sql_formas_pagamentos = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamentos->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
} elseif($dados_fatura[tipo] == 'd') {

$sql_dominio = $conexao->query("SELECT * FROM dominios_registro where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_dominio->fetch_array(MYSQLI_ASSOC);

$sql_formas_pagamentos = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamentos->fetch_array(MYSQLI_ASSOC);

}

// Verifica se a fatura do f2b existe
$total_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
if($total_fatura->num_rows > 0) {

// Verifica se a forma de pagamento da fatura é f2b
if($dados_formas_pagamento[tipo_pagamento] != 'f2b') {
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

// Verifica se esta habilitado a 2ª via de fatura
$data_hoje = date("Y-m-d");

if($dados_empresa['2via_fatura'] == 'sim' && $dados_fatura[data_vencimento] < $data_hoje) {
$fatura_vencimento = date("Y-m-d",mktime (0, 0, 0, date("m"), date("d")+$dados_empresa['dias_2via_fatura'], date("Y")));
} else {
list($ano,$mes,$dia) = explode("-",$dados_fatura[data_vencimento]);
$fatura_vencimento = $ano."-".$mes."-".$dia;
}

// Verifica se existem faturas do cliente vencendo este mes e gera o valor total das faturas
list($ano_atual,$mes_atual,$dia) = explode("-",$dados_fatura[data_vencimento]);

$total_faturas_cliente = $conexao->query("SELECT * FROM faturas where codigo_cliente = '".$dados_cliente[codigo]."' AND MONTH(data_vencimento) = '$mes_atual' AND YEAR(data_vencimento) = '$ano_atual'");

if($dados_cliente[unificar_faturas] == 'sim' && $total_faturas_cliente->num_rows > 1) {

$sql_faturas = $conexao->query("SELECT * FROM faturas where status = 'off' AND codigo_cliente = '".$dados_cliente[codigo]."' AND MONTH(data_vencimento) = '$mes_atual' AND YEAR(data_vencimento) = '$ano_atual'");
while ($dados_faturas_unificadas = $sql_faturas->fetch_array(MYSQLI_ASSOC)) {

$faturas_valor_total += $dados_faturas_unificadas[valor];

}

$fatura_valor = number_format($faturas_valor_total,2,",",".");
$fatura_descricao = "Suas faturas estão unificadas, para maiores detalhes sobre suas faturas, acesse a Central do Cliente";

} else {

$fatura_valor = number_format($dados_fatura[valor],2,",",".");
$fatura_descricao = $dados_fatura[descricao];

}

// Dados Cliente

// Função para trocar a sigla do Estado pelo nome completo do Estado
function trocar_estato($estado) {

$estado = str_replace("Acre","AC",$estado);
$estado = str_replace("Alagoas","AL",$estado);
$estado = str_replace("Amapá","AP",$estado);
$estado = str_replace("Amazonas","AM",$estado);
$estado = str_replace("Bahia","BA",$estado);
$estado = str_replace("Ceará","CE",$estado);
$estado = str_replace("Distrito Federal","DF",$estado);
$estado = str_replace("Espírito Santo","ES",$estado);
$estado = str_replace("Goiás","GO",$estado);
$estado = str_replace("Maranhão","MA",$estado);
$estado = str_replace("Mato Grosso","MT",$estado);
$estado = str_replace("Mato Grosso do Sul","MS",$estado);
$estado = str_replace("Minas Gerais","MG",$estado);
$estado = str_replace("Pará","PA",$estado);
$estado = str_replace("Paraná","PR",$estado);
$estado = str_replace("Paraíba","PB",$estado);
$estado = str_replace("Pernambuco","PE",$estado);
$estado = str_replace("Piauí","PI",$estado);
$estado = str_replace("Rio de Janeiro","RJ",$estado);
$estado = str_replace("Rio Grande do Nort","RN",$estado);
$estado = str_replace("Rio Grande do Sul","RS",$estado);
$estado = str_replace("Rondônia","RO",$estado);
$estado = str_replace("Roraima","RR",$estado);
$estado = str_replace("Santa Catarina","SC",$estado);
$estado = str_replace("Sergipe","SE",$estado);
$estado = str_replace("São Paulo","SP",$estado);
$estado = str_replace("Tocantins","TO",$estado);

return $estado;
}

$f2b_conta = $dados_formas_pagamento[f2b_conta];
$f2b_senha = $dados_formas_pagamento[f2b_senha];
$f2b_taxa = number_format($dados_formas_pagamento[f2b_taxa],2,",",".");
$f2b_envio = $dados_formas_pagamento[f2b_envio];
$f2b_sem_vencimento = $dados_formas_pagamento[f2b_sem_vencimento];
$f2b_tipo_cobranca = $dados_formas_pagamento[f2b_tipo_cobranca];

$fatura_codigo = $dados_fatura[codigo];

// Dados Cliente
$telefone_partes = explode(') ', $dados_cliente[fone]);
$telefone_partes[0] = str_replace("(", "", $telefone_partes[0]);

$cliente_nome = $dados_cliente[nome];
$cliente_email_1 = $dados_cliente[email1];
$cliente_tipo_pessoa = $dados_cliente[tipo_pessoa];
$cliente_cpf = preg_replace('#[^0-9]#','',$dados_cliente[cpf]);
$cliente_cnpj = preg_replace('#[^0-9]#','',$dados_cliente[cnpj]);
$cliente_ddd_telefone = $telefone_partes[0];
$cliente_telefone = str_replace("-", "", $telefone_partes[1]);;
$cliente_endereco_logradouro = $dados_cliente[endereco];
$cliente_endereco_numero = $dados_cliente[numero];
$cliente_endereco_bairro = $dados_cliente[bairro];
$cliente_endereco_cidade = $dados_cliente[cidade];
$cliente_endereco_estado = trocar_estato($dados_cliente[estado]);
$cliente_endereco_cep = str_replace("-", "", $dados_cliente[cep]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pagamento F2B</title>
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   window.onload = function() {
    document.f2b.submit();
   };
</script>
</head>

<body onload="document.f2b.submit();">
<div align="center" class="texto_padrao2" style="margin:30px">Por favor aguarde, voc&ecirc; está sendo redirecionado para o site do F2b.com.br<br />Se você tem um anti-popup instalado, desabilite ele e atualize a página.<br /><br /><img src="img/ajax-loader.gif" alt="Carregando..." /></div>
<form action="https://www.f2b.com.br/BillingWeb" method="post" name="f2b">
<input type="hidden" name="conta" value="<?php echo $f2b_conta; ?>" />
<input type="hidden" name="senha" value="<?php echo $f2b_senha; ?>" />
<input type="hidden" name="taxa" value="<?php echo $f2b_taxa; ?>" />
<input type="hidden" name="tipo_cobranca" value="<?php echo $f2b_tipo_cobranca; ?>" />
<input type="hidden" name="tipo_taxa" value="0" />
<input type="hidden" name="valor" value="<?php echo $fatura_valor; ?>" />
<input type="hidden" name="demonstrativo_1" value="<?php echo $fatura_descricao; ?>" />
<input type="hidden" name="vencimento" value="<?php echo $fatura_vencimento; ?>" />
<input type="hidden" name="nome" value="<?php echo $cliente_nome; ?>" />
<input type="hidden" name="email_1" value="<?php echo $cliente_email_1; ?>" />
<input type="hidden" name="telefone_ddd" value="<?php echo $cliente_ddd_telefone; ?>" />
<input type="hidden" name="telefone_numero" value="<?php echo $cliente_telefone; ?>" />
<input type="hidden" name="endereco_logradouro" value="<?php echo $cliente_endereco_logradouro; ?>" />
<input type="hidden" name="endereco_numero" value="<?php echo $cliente_endereco_numero; ?>" />
<input type="hidden" name="endereco_bairro" value="<?php echo $cliente_endereco_bairro; ?>" />
<input type="hidden" name="endereco_cidade" value="<?php echo $cliente_endereco_cidade; ?>" />
<input type="hidden" name="endereco_estado" value="<?php echo $cliente_endereco_estado; ?>" />
<input type="hidden" name="endereco_cep" value="<?php echo $cliente_endereco_cep; ?>" />
<?php if($cliente_tipo_pessoa == 'fisica') { ?>
<input type="hidden" name="cpf" value="<?php echo $cliente_cpf; ?>" />
<?php } else { ?>
<input type="hidden" name="cnpj" value="<?php echo $cliente_cnpj; ?>" />
<?php } ?>
<input type="hidden" name="codigo" value="<?php echo $fatura_codigo; ?>" />
<input type="hidden" name="envio" value="<?php echo $f2b_envio; ?>" />
<input type="hidden" name="sem_vencimento" value="<?php echo $f2b_sem_vencimento; ?>" />
<input type="hidden" name="numero_documento" value="<?php echo $fatura_codigo ; ?>"  />
</form>
</body>
</html>
