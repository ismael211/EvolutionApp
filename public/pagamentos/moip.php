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

$sql_donimo = $conexao->query("SELECT * FROM dominios where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_donimo->fetch_array(MYSQLI_ASSOC);


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

$sql_dominios_registro = $conexao->query("SELECT * FROM dominios_registro where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_dominios_registro->fetch_array(MYSQLI_ASSOC);

$sql_forma_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_forma_pagamento->fetch_array(MYSQLI_ASSOC);

}

// Verifica se a fatura do f2b existe
$total_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
if($total_fatura->num_rows > 0) {

// Verifica se a forma de pagamento da fatura é MoIP
if($dados_formas_pagamento[tipo_pagamento] != 'moip') {
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
$fatura_codigo .= $dados_faturas_unificadas[codigo];

}

$fatura_valor = number_format($faturas_valor_total,2,"","");
$fatura_descricao = "Suas faturas estão unificadas, para maiores detalhes sobre suas faturas, acesse a Central do Cliente";

} else {

$fatura_codigo = $dados_fatura[codigo];
$fatura_valor = number_format($dados_fatura[valor],2,"","");
$fatura_descricao = $dados_fatura[descricao];

}

// Dados Cliente

  // Função para trocar a sigla do Estado pelo nome completo do Estado
  function trocar_estado($estado) {

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
      $estado = str_replace("MT do Sul","MS",$estado);
      $estado = str_replace("Minas Gerais","MG",$estado);
      $estado = str_replace("Pará","PA",$estado);
      $estado = str_replace("Paraná","PR",$estado);
      $estado = str_replace("Paraíba","PB",$estado);
      $estado = str_replace("Pernambuco","PE",$estado);
      $estado = str_replace("Piauí","PI",$estado);
      $estado = str_replace("Rio de Janeiro","RJ",$estado);
      $estado = str_replace("Rio Grande do Norte","RN",$estado);
      $estado = str_replace("Rio Grande do Sul","RS",$estado);
      $estado = str_replace("Rondônia","RO",$estado);
      $estado = str_replace("Roraima","RR",$estado);
      $estado = str_replace("Santa Catarina","SC",$estado);
      $estado = str_replace("Sergipe","SE",$estado);
      $estado = str_replace("São Paulo","SP",$estado);
      $estado = str_replace("Tocantins","TO",$estado);

      return $estado;
  }

$moip_carteira = $dados_formas_pagamento[moip_carteira];

// Dados Cliente
$cliente_nome = $dados_cliente[nome];
$cliente_email_1 = $dados_cliente[email1];
$cliente_telefone = preg_replace('#[^0-9]#','',$dados_cliente[fone]);
$cliente_endereco_logradouro = $dados_cliente[endereco];
$cliente_endereco_numero = $dados_cliente[numero];
$cliente_endereco_bairro = $dados_cliente[bairro];
$cliente_endereco_cidade = $dados_cliente[cidade];
$cliente_endereco_estado = trocar_estado($dados_cliente[estado]);
$cliente_endereco_cep = preg_replace('#[^0-9]#','',$dados_cliente[cep]);


// Numeracao Dif
// 

$data = date("YmdHis");

$fatura_codigo = "$fatura_codigo-$data";

// Juros ativado
// 

function calcularJuros($valor,$taxa,$tipo_taxa,$periodo){

 
  if ($tipo_taxa == "monetario") {
    
    $resultado_taxa = $periodo * $taxa;
    $resultado = $valor + $resultado_taxa;

  }

  return $resultado;
}


function calculaDias($data_inicial,$data_final){
  
    $time_inicial = strtotime($data_inicial);
    $time_final = strtotime($data_final);

    // Calcula a diferença de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial;

    // Calcula a diferença de dias
    $dias = (int)floor( $diferenca / (60 * 60 * 24)); 

    return $dias;
}



if ($dados_formas_pagamento[tipo_pagamento] == "moip" and $dados_formas_pagamento[moip_juros] == "s") {
  


  $moip_juros_taixa_tp = $dados_formas_pagamento[moip_juros_taixa_tp];
  $moip_juros_taixa = number_format($dados_formas_pagamento[moip_juros_taixa],2,"","");


  $datahj = date("Y-m-d");

  $periodo = calculaDias($fatura_vencimento,$datahj);

  $valorcomjuros = calcularJuros($fatura_valor,$moip_juros_taixa,$moip_juros_taixa_tp,$periodo);


  if ($periodo > 0) {
    $fatura_valor = $valorcomjuros;
  }
  
  
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pagamento MoIP</title>
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   window.onload = function() {
    document.moip.submit();
   };
</script>
</head>



<body onload="document.moip.submit();">
<div align="center" class="texto_padrao2" style="margin:30px">Por favor aguarde, voc&ecirc; está sendo redirecionado para o site do MoIP.com.br<br />Se você tem um anti-popup instalado, desabilite ele e atualize a página.<br /><br /><img src="img/ajax-loader.gif" alt="Carregando..." /></div>
<form method='post' action='https://www.moip.com.br/PagamentoMoIP.do' name="moip">

<input type='hidden' name='id_carteira' value='<?php echo $moip_carteira; ?>'/>
<input type='hidden' name='id_transacao' value='<?php echo trim($fatura_codigo); ?>'/> 

<input type='hidden' name='valor' value='<?php echo $fatura_valor; ?>'/>
<input type='hidden' name='nome' value='<?php echo $fatura_descricao; ?>'/>

<input type='hidden' name='pagador_nome' value='<?php echo $cliente_nome; ?>'/>
<input type='hidden' name='pagador_telefone' value='<?php echo $cliente_telefone; ?>'/>
<input type='hidden' name='pagador_email' value='<?php echo $cliente_email_1; ?>'/>
<input type='hidden' name='pagador_logradouro' value='<?php echo $cliente_endereco_logradouro; ?>' />
<input type='hidden' name='pagador_numero' value='<?php echo $cliente_endereco_numero; ?>' />
<input type='hidden' name='pagador_bairro' value='<?php echo $cliente_endereco_bairro; ?>' />
<input type='hidden' name='pagador_cep' value='<?php echo $cliente_endereco_cep; ?>' />
<input type='hidden' name='pagador_cidade' value='<?php echo $cliente_endereco_cidade; ?>' />
<input type='hidden' name='pagador_estado' value='<?php echo $cliente_endereco_estado; ?>' />
<input type='hidden' name='pagador_pais' value='Brasil' />

</form>
</body>
</html>
