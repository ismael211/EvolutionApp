<?php
//////////////////////////////////////////////////////////////////////////
// Soft4you Gerenciador Financeiro para Hosts  		                    //
// Descrição: Sistema de Gerenciamento de Clientes		                //
// Site: www.soft4you.com.br       										//
//////////////////////////////////////////////////////////////////////////

require('../../ds8.php');
require_once('inc/funcoes.php');

$conexao = new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);

// Decodifica código da fatura
$_GET[codigo] = encode_decode($_GET[codigo],"D");

// Uso Geral
// ->fetch_array(MYSQLI_ASSOC);

$sql_empresa = $conexao->query("SELECT * FROM empresa");
$dados_empresa = $sql_empresa->fetch_array(MYSQLI_ASSOC);

$sql_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
$dados_fatura = $sql_fatura->fetch_array(MYSQLI_ASSOC);

$sql_cliente = $conexao->query("SELECT * FROM clientes where codigo = '".$dados_fatura[codigo_cliente]."'");
$dados_cliente = $sql_cliente->fetch_array(MYSQLI_ASSOC);

// Verifica o tipo de fatura para obter a forma de pagamento
// Fatura de Hospedagem(geral) - tipo: h
if($dados_fatura[tipo] == 'h') {

$sql_dominio = $conexao->query("SELECT * FROM dominios where codigo = '".$dados_fatura[codigo_dominio]."'");
$dados_dominio = $sql_domini->fetch_array(MYSQLI_ASSOC);

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

// Verifica se a fatura do boleto existe
$total_fatura = $conexao->query("SELECT * FROM faturas where codigo = '".$_GET[codigo]."'");
if($total_fatura->num_rows > 0) {

// Verifica se a forma de pagamento da fatura é boleto
if(!preg_match("/boleto/i",$dados_formas_pagamento[tipo_pagamento])) {
	echo "<script>
	       alert(\"Atenção!\\n \\nBoleto bancário não encontrado!\\n \\nEntre em contato com nosso atendimento.\\n \\nVocê será redirecionado para a Central do Cliente.\");
	 window.location = '".$dados_empresa[url_sistema]."/';
	 </script>";
}

} else {
	echo "<script>
	       alert(\"Atenção!\\n \\nBoleto bancário não encontrado!\\n \\nEntre em contato com nosso atendimento.\\n \\nVocê será redirecionado para a Central do Cliente.\");
	 window.location = '".$dados_empresa[url_sistema]."/';
	 </script>";
}

// Boletos

if($dados_formas_pagamento[tipo_pagamento] == 'boleto.bb.php') {
// BB
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "N";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";

// DADOS DA SUA CONTA - BANCO DO BRASIL

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_conta = explode("-",$dados_formas_pagamento[boleto_conta]);

$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] = $converte_conta[0]; 	// Num da conta, sem digito

// DADOS PERSONALIZADOS - BANCO DO BRASIL
$dadosboleto["convenio"] = $dados_formas_pagamento[boleto_convenio];  // Num do convênio - REGRA: 6 ou 7 dígitos
$dadosboleto["contrato"] = $dados_formas_pagamento[boleto_contrato]; // Num do seu contrato
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira 18 - 17 ou 11
$dadosboleto["variacao_carteira"] = "";  // Variação da Carteira, com traço (opcional)

// TIPO DO BOLETO
$qtd_digitos = strlen($dados_formas_pagamento[boleto_convenio]);
if($qtd_digitos == '7') {
$qtd_digitos_convenio = "7";
} else {
$qtd_digitos_convenio = "6";
}
$dadosboleto["formatacao_convenio"] = $qtd_digitos_convenio; // REGRA: Informe 7 se for Convênio com 7 dígitos ou 6 se for Convênio com 6 dígitos
$dadosboleto["formatacao_nosso_numero"] = "1"; // REGRA: Se for Convênio com 6 dígitos, informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.bradesco.php') {

// Bradesco
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "001";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DS";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_conta = explode("-",$dados_formas_pagamento[boleto_conta]);

// DADOS DA SUA CONTA - Bradesco
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["agencia_dv"] = $converte_agencia[1]; // Digito do Num da agencia
$dadosboleto["conta"] = $converte_conta[0]; 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $converte_conta[1]; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - Bradesco
$dadosboleto["conta_cedente"] = $converte_conta[0]; // ContaCedente do Cliente, sem digito (Somente Números)
$dadosboleto["conta_cedente_dv"] = $converte_conta[1]; // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.cef.php') {
// CEF
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_codigo_cedente = explode("-",$dados_formas_pagamento[boleto_codigo_cedente]);

// DADOS DA SUA CONTA - CEF
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] = $converte_codigo_cedente[0]; 	// Num da conta, sem digito
$dadosboleto["dac_conta"] = $converte_codigo_cedente[1];	// Digito do Num da conta
$dadosboleto["inicio_nosso_numero"] = $dados_formas_pagamento[boleto_inicio_nosso_numero];

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.cef.sinco.php') {
	
	
	
// CEF
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_codigo_cedente = explode("-",$dados_formas_pagamento[boleto_codigo_cedente]);

// DADOS DA SUA CONTA - CEF
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] = $converte_codigo_cedente[0]; 	// Num da conta, sem digito
$dadosboleto["dac_conta"] = $converte_codigo_cedente[1];	// Digito do Num da conta
$dadosboleto["inicio_nosso_numero"] = "9";
$dadosboleto["carteira"] = "SR";





} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.cef.sigcb.php') {
// CEF  SIGCB


 // Composição Nosso Numero - CEF SIGCB
$dadosboleto["nosso_numero1"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
$dadosboleto["nosso_numero2"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
$dadosboleto["nosso_numero3"] = $dados_boleto[codigo]; // tamanho 9

$dadosboleto["numero_documento"] = $dados_boleto[codigo];	// Num do pedido ou do documento



// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

// DADOS DA SUA CONTA - CEF

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_codigo_cedente = explode("-",$dados_formas_pagamento[boleto_codigo_cedente]);

$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] =  $dados_formas_pagamento[boleto_conta];	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $dados_formas_pagamento[boleto_conta_dv]; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - CEF
$dadosboleto["conta_cedente"] = $converte_codigo_cedente[0]; // Código Cedente do Cliente, com 6 digitos (Somente Números)
$dadosboleto["carteira"] = "SR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)









} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.hsbc.php') {
// HSBC
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

// DADOS PERSONALIZADOS - HSBC

$dadosboleto["codigo_cedente"] = $dados_formas_pagamento[boleto_codigo_cedente]; // Código do Cedente (Somente 7 digitos)
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.itau.php') {
// Itaú

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_conta = explode("-",$dados_formas_pagamento[boleto_conta]);

// DADOS DA SUA CONTA - ITAÚ
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] = $converte_conta[0];	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $converte_conta[1];	// Digito do Num da conta

// DADOS PERSONALIZADOS - ITAÚ
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.unibanco.php') {
// Unibanco
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_conta = explode("-",$dados_formas_pagamento[boleto_conta]);

// DADOS DA SUA CONTA - UNIBANCO
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] = $converte_conta[0];	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $converte_conta[1];	// Digito do Num da conta

// DADOS PERSONALIZADOS - UNIBANCO
$dadosboleto["codigo_cliente"] = $dados_formas_pagamento[boleto_codigo_cedente]; // Codigo do Cliente
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.nossacaixa.php') {
// Nossa Caixa

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "N";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_conta1 = explode("-",$dados_formas_pagamento[boleto_conta]);

// DADOS DA SUA CONTA - NOSSA CAIXA
$dadosboleto["inicio_nosso_numero"] = "99"; // 99 - Cobrança Direta(Carteira 5) ou 0 - Cobrança Simples(Carteira 1)
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta_cedente"] = $converte_conta1[1]; // ContaCedente do Cliente, sem digito (Somente Números)
$dadosboleto["conta_cedente_dv"] = $converte_conta1[2]; // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira -> 5-Cobrança Direta ou 1-Cobrança Simples
$dadosboleto["modalidade_conta"] = $converte_conta1[0];  // modalidade da conta 02 posições - 04 000 629-0

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.real.php') {
// Banco Real

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "N";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$converte_conta = explode("-",$dados_formas_pagamento[boleto_conta]);

// DADOS DA SUA CONTA - REAL
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] = $converte_conta[0]; 	// Num da conta, sem digito
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.besc.php') {
// Banco BESC

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "N";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

// DADOS PERSONALIZADOS - BESC
$dadosboleto["codigo_cedente"] = $dados_formas_pagamento[boleto_codigo_cedente]; // Código do Cedente (Somente 7 digitos)
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.sicredi.php') {
// Banco Sicredi

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
if(strlen($dados_fatura[codigo]) < '4') {

if(strlen($dados_fatura[codigo]) == '1') {
$dadosboleto["numero_documento"] = "2000".$dados_fatura[codigo];
} elseif(strlen($dados_fatura[codigo]) == '2') {
$dadosboleto["numero_documento"] = "200".$dados_fatura[codigo];
} elseif(strlen($dados_fatura[codigo]) == '3') {
$dadosboleto["numero_documento"] = "20".$dados_fatura[codigo];
}

} else {
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
}

$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "NÃO";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DI";

$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);

// DADOS PERSONALIZADOS - SICREDI
$dadosboleto["agencia"] = $converte_agencia[0]; // Num da agencia, sem digito
$dadosboleto["conta"] = $dados_formas_pagamento[boleto_conta]; 	// Num da conta, com digito
$dadosboleto["codigo_cedente"] = $dados_formas_pagamento[boleto_codigo_cedente]; // Código do Cedente
$dadosboleto["posto_cedente"] = $dados_formas_pagamento[boleto_posto_cedente]; // Código do Posto Cedente
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Código da Carteira

} elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.santander.php') {
// Banco Santander

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["nosso_numero"] = $dados_fatura[codigo];
$dadosboleto["numero_documento"] = $dados_fatura[codigo];
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

// DADOS PERSONALIZADOS - SANTANDER BANESPA
$dadosboleto["codigo_cliente"] = $dados_formas_pagamento[boleto_codigo_cedente]; // Código do Cliente (PSK) (Somente 7 digitos)
$dadosboleto["ponto_venda"] = $dados_formas_pagamento[boleto_agencia]; // Ponto de Venda = Agencia
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];  // Cobrança Simples - SEM Registro
$dadosboleto["carteira_descricao"] = "COBRANÇA SIMPLES - CSR";  // Descrição da Carteira

}

elseif($dados_formas_pagamento[tipo_pagamento] == 'boleto.bancoob.php') {
// Banco BANCOOB

$ano = substr(date("Y"),2,2);
$ano = "$ano$dados_fatura[codigo]";

$dadosboleto["nosso_numero"] = $ano;  // Até 8 digitos, sendo os 2 primeiros o ano atual (Ex.: 08 se for 2008)
$dadosboleto["numero_documento"] = $dados_fatura[codigo];	// Num do pedido ou do documento


// DADOS DO SEU CLIENTE
/*
$dadosboleto["sacado"] = "Nome do seu Cliente";
$dadosboleto["endereco1"] = "Endereço do seu Cliente";
$dadosboleto["endereco2"] = "Cidade - Estado -  CEP: 00000-000";
*/


// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = $dados_formas_pagamento[boleto_quantidade];
$dadosboleto["valor_unitario"] = $dados_formas_pagamento[boleto_valor_unit];
$dadosboleto["aceite"] = "N";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
// DADOS ESPECIFICOS DO SICOOB
$dadosboleto["modalidade_cobranca"] = $dados_formas_pagamento[boleto_modalidade];
$dadosboleto["numero_parcela"] = $dados_formas_pagamento[boleto_parcela];


// DADOS DA SUA CONTA - BANCO SICOOB
$converte_agencia = explode("-",$dados_formas_pagamento[boleto_agencia]);
$dadosboleto["agencia"] = $converte_agencia[0];  // Num da agencia, sem digito
$dadosboleto["conta"] =  $dados_formas_pagamento[boleto_conta]; 	// Num da conta, sem digito

// DADOS PERSONALIZADOS - SICOOB
$dadosboleto["convenio"] = $dados_formas_pagamento[boleto_convenio];  // Num do convênio - REGRA: No máximo 7 dígitos
$dadosboleto["carteira"] = $dados_formas_pagamento[boleto_carteira];


}

/*                
------------------------------------------------------------------------------------
|//////////////////////////////////////////////////////////////////////////////////|
|//////////////////////////////////////////////////////////////////////////////////|
|//////////////////////////////////////////////////////////////////////////////////|
|////////////////////// Fim dos Bancos - Inicio do Dados Gerais ///////////////////|
|//////////////////////////////////////////////////////////////////////////////////|
|//////////////////////////////////////////////////////////////////////////////////|
------------------------------------------------------------------------------------
*/

// Valores e datas
// Dados da fatura

// Verifica se existem faturas do cliente vencendo este mes e gera o valor total das faturas
list($ano_atual,$mes_atual,$dia) = explode("-",$dados_fatura[data_vencimento]);

$total_faturas_cliente = $conexao->query("SELECT * FROM faturas where codigo_cliente = '".$dados_cliente[codigo]."' AND MONTH(data_vencimento) = '$mes_atual' AND YEAR(data_vencimento) = '$ano_atual'");

if($dados_cliente[unificar_faturas] == 'sim' && $total_faturas_cliente->num_rows > 1) {

$sql_faturas = $conexao->query("SELECT * FROM faturas where status = 'off' AND codigo_cliente = '".$dados_cliente[codigo]."' AND MONTH(data_vencimento) = '$mes_atual' AND YEAR(data_vencimento) = '$ano_atual'");
while ($dados_faturas_unificadas = $sql_faturas->fetch_array(MYSQLI_ASSOC)) {

$faturas_valor_total += $dados_faturas_unificadas[valor];
$faturas_descricao .= $dados_faturas_unificadas[descricao]."<br>";

}

$valor_cobrado = $faturas_valor_total+$dados_formas_pagamento[boleto_taxa];
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto = number_format($valor_cobrado, 2, ',', '');

$dadosboleto["descricao"] = $faturas_descricao;

} else {

$valor_cobrado = $dados_fatura[valor]+$dados_formas_pagamento[boleto_taxa];
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto = number_format($valor_cobrado, 2, ',', '');

$dadosboleto["descricao"] = $dados_fatura[descricao];

}

if($dados_formas_pagamento[boleto_taxa] > 0) {
$dadosboleto["taxa_pagamento"] = "Taxa bancária: R$ ".number_format($dados_formas_pagamento[boleto_taxa], 2, ',', '');
} else {
$dadosboleto["taxa_pagamento"] = "";
}


// Verifica se esta habilitado a 2ª via de fatura
$data_hoje = date("Y-m-d");

if($dados_empresa['2via_fatura'] == 'sim' && $dados_fatura[data_vencimento] < $data_hoje) {
$data_vencimento = date("d/m/Y",mktime (0, 0, 0, date("m"), date("d")+$dados_empresa['dias_2via_fatura'], date("Y")));
} else {
list($ano,$mes,$dia) = explode("-",$dados_fatura[data_vencimento]);
$data_vencimento = $dia."/".$mes."/".$ano;
}

$dadosboleto["data_vencimento"] = $data_vencimento; // Data de Vencimento do Boleto
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// Dados do Cliente
if($dados_cliente[tipo_pessoa] == 'fisica') {

if($dados_cliente[cpf] == '' && $dados_cliente[rg] != '') {
$dadosboleto["sacado"] = $dados_cliente[nome]."<br>".$dados_cliente[rg];
} elseif($dados_cliente[cpf] != '' && $dados_cliente[rg] == '') {
$dadosboleto["sacado"] = $dados_cliente[nome]."<br>".$dados_cliente[cpf];
} elseif($dados_cliente[cpf] == '' && $dados_cliente[rg] == '') {
$dadosboleto["sacado"] = $dados_cliente[nome];
} else {
$dadosboleto["sacado"] = $dados_cliente[nome]."<br>".$dados_cliente[cpf]."<br>".$dados_cliente[rg];
}

} else {
$dadosboleto["sacado"] = $dados_cliente[razao_social]."<br>".$dados_cliente[cnpj];
}

// Informações para o cliente e caixa
$dadosboleto["demonstrativo1"] = $dados_formas_pagamento[boleto_demonstrativo1];
$dadosboleto["demonstrativo2"] = $dados_formas_pagamento[boleto_demonstrativo2];
$dadosboleto["instrucoes1"] = $dados_formas_pagamento[boleto_instrucoes1];
$dadosboleto["instrucoes2"] = $dados_formas_pagamento[boleto_instrucoes2];
$dadosboleto["instrucoes3"] = $dados_formas_pagamento[boleto_instrucoes3];

// Dados da empresa
$dadosboleto["cedente"] = $dados_formas_pagamento[boleto_cedente];
$dadosboleto["cpf_cnpj"] = $dados_formas_pagamento[boleto_cpf_cnpj];
$dadosboleto["empresa_logo"] = $dados_empresa[logo];
$dadosboleto["identificacao"] = $dados_empresa[nome];

// Layouts e funções
include("boletos/".$dados_formas_pagamento[tipo_pagamento]."");
?>