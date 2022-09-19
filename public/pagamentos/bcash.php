<?php
require('../../ds8.php');
require_once('inc/funcoes.php');

$conexao = new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);

// Decodifica c�digo da fatura
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

// Fatura de Servi�o Adicional   - tipo: s
} elseif($dados_fatura[tipo] == 's') {

$sql_servico = $conexao->query("SELECT * FROM servicos_adicionais where codigo = '".$dados_fatura[codigo_servico]."'");
$dados_servico = $sql_servico->fetch_array(MYSQLI_ASSOC);

$sql_servico_modelo = $conexao->query("SELECT * FROM servicos_modelos where codigo = '".$dados_servico[codigo_servico]."'");
$dados_servico_modelo = $sql_servico_modelo->fetch_array(MYSQLI_ASSOC);

$sql_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transfer�ncia de Dom�nio   - tipo: d
} elseif($dados_fatura[tipo] == 'd') {

$sql_dominio = $conexao->query("SELECT * FROM dominios_registro where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_dominio->fetch_array(MYSQLI_ASSOC);

$sql_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento where codigo = '".$dados_dominio[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_formas_pagamento->fetch_array(MYSQLI_ASSOC);

}

// Verifica se a fatura do bcash existe
$total_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
if($total_fatura->num_rows > 0) {

  // Verifica se a forma de pagamento da fatura � Bcash
  if($dados_formas_pagamento[tipo_pagamento] != 'bcash') {
  echo "<script>
         alert(\"Aten��o!\\n \\nFatura n�o encontrada!\\n \\nEntre em contato com nosso atendimento.\\n \\nVoc� ser� redirecionado para a Central do Cliente.\");
   window.location = '".$dados_empresa[url_sistema]."/';
   </script>";
  }

} else {
echo "<script>
       alert(\"Aten��o!\\n \\nFatura n�o encontrada!\\n \\nEntre em contato com nosso atendimento.\\n \\nVoc� ser� redirecionado para a Central do Cliente.\");
 window.location = '".$dados_empresa[url_sistema]."/';
 </script>";
}

// Verifica se esta habilitado a 2� via de fatura
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
$fatura_descricao = "Suas faturas est�o unificadas, para maiores detalhes sobre suas faturas, acesse a Central do Cliente";

} else {

$fatura_codigo = $dados_fatura[codigo];
$fatura_valor = number_format($dados_fatura[valor],2,"","");
$fatura_descricao = $dados_fatura[descricao];

}

// Dados Cliente

  // Fun��o para trocar a sigla do Estado pelo nome completo do Estado
  function trocar_estado($estado) {

      $estado = str_replace("Acre","AC",$estado);
      $estado = str_replace("Alagoas","AL",$estado);
      $estado = str_replace("Amap�","AP",$estado);
      $estado = str_replace("Amazonas","AM",$estado);
      $estado = str_replace("Bahia","BA",$estado);
      $estado = str_replace("Cear�","CE",$estado);
      $estado = str_replace("Distrito Federal","DF",$estado);
      $estado = str_replace("Esp�rito Santo","ES",$estado);
      $estado = str_replace("Goi�s","GO",$estado);
      $estado = str_replace("Maranh�o","MA",$estado);
      $estado = str_replace("Mato Grosso","MT",$estado);
      $estado = str_replace("Mato Grosso do Sul","MS",$estado);
      $estado = str_replace("MT do Sul","MS",$estado);
      $estado = str_replace("Minas Gerais","MG",$estado);
      $estado = str_replace("Par�","PA",$estado);
      $estado = str_replace("Paran�","PR",$estado);
      $estado = str_replace("Para�ba","PB",$estado);
      $estado = str_replace("Pernambuco","PE",$estado);
      $estado = str_replace("Piau�","PI",$estado);
      $estado = str_replace("Rio de Janeiro","RJ",$estado);
      $estado = str_replace("Rio Grande do Norte","RN",$estado);
      $estado = str_replace("Rio Grande do Sul","RS",$estado);
      $estado = str_replace("Rond�nia","RO",$estado);
      $estado = str_replace("Roraima","RR",$estado);
      $estado = str_replace("Santa Catarina","SC",$estado);
      $estado = str_replace("Sergipe","SE",$estado);
      $estado = str_replace("S�o Paulo","SP",$estado);
      $estado = str_replace("Tocantins","TO",$estado);

      return $estado;
  }

$bcash_email = $dados_formas_pagamento[bcash_email];

// Dados Cliente
$cliente_nome = $dados_cliente[nome];
$cliente_email_1 = $dados_cliente[email1];
$cliente_telefone = preg_replace('#[^0-9]#','',$dados_cliente[fone]);
$cliente_celuar = preg_replace('#[^0-9]#','',$dados_cliente[celular]);
$cliente_cpf = preg_replace('#[^0-9]#','',$dados_cliente[cpf]);
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

    // Calcula a diferen�a de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial;

    // Calcula a diferen�a de dias
    $dias = (int)floor( $diferenca / (60 * 60 * 24)); 

    return $dias;
}



if ($dados_formas_pagamento[tipo_pagamento] == "bcash" and $dados_formas_pagamento[bcash_juros] == "s") {
  


  $bcash_juros_taixa_tp = $dados_formas_pagamento[bcash_juros_taixa_tp];
  $bcash_juros_taixa = number_format($dados_formas_pagamento[bcash_juros_taixa],2,"","");


  $datahj = date("Y-m-d");

  $periodo = calculaDias($fatura_vencimento,$datahj);

  $valorcomjuros = calcularJuros($fatura_valor,$bcash_juros_taixa,$bcash_juros_taixa_tp,$periodo);


  if ($periodo > 0) {
    $fatura_valor = $valorcomjuros;
  }
  
  
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pagamento Bcash</title>
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   window.onload = function() {
    document.bcash.submit();
   };
</script>
</head>



<body onload="document.bcash.submit();">

<div align="center" class="texto_padrao2" style="margin:30px">
Por favor aguarde, voc&ecirc; est� sendo
 redirecionado para o site do bcash.com.br<br />Se voc� tem um anti-popup instalado, desabilite ele e atualize 
 a p�gina.<br /><br />
 <img src="img/ajax-loader.gif" alt="Carregando..." />
 </div>


 <form action="https://www.bcash.com.br/checkout/pay/" method="post" name="bcash" >
  
 <!-- Identifica��o do vendedor -->
  
 <input name="email_loja" type="hidden" value="<?php echo $bcash_email; ?>">
  
 <!-- Dados do Pedido / Produtos -->
  
 <input name="produto_codigo_1" type="hidden" value="<?php echo $fatura_codigo; ?>">
  
 <input name="produto_descricao_1" type="hidden" value="<?php echo $fatura_descricao; ?>" >
  
 <input name="produto_qtde_1" type="hidden" value="1">
  
 <input name="produto_valor_1" type="hidden" value="<?php echo $fatura_valor; ?>" >
  
 <!-- Dados do Comprador -->
  
 <input name="email" type="hidden" value="<?php echo $cliente_email_1; ?>">
  
 <input name="nome" type="hidden" value="<?php echo $cliente_nome; ?>">
  
 <input name="cpf" type="hidden" value="<?php echo $cliente_cpf; ?>">
  
 <input name="telefone" type="hidden" value="<?php echo $cliente_telefone; ?>">
  
 <!-- Dados de Entrega -->
  
 <input name="cep" type="hidden" value="<?php echo $cliente_endereco_cep; ?>">
  
 <input name="endereco" type="hidden" value="<?php echo  $cliente_endereco_logradouro;  ?>">
  
 <input name="cidade" type="hidden" value="<?php echo $cliente_endereco_cidade; ?>">
  
 <input name="estado" type="hidden" value="<?php echo $cliente_endereco_estado; ?>">
  
<!--<input type="image" src="https://www.bcash.com.br/webroot/img/bt_comprar.gif" value="Comprar" alt="Comprar" border="0" align="absbottom" >-->
  
 </form>




</body>
</html>
