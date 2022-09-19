<?php
//////////////////////////////////////////////////////////////////////////
// Isistem Gerenciador Financeiro para Hosts  		                    //
// Descrição: Sistema de Gerenciamento de Clientes		                //
// Site: www.isistem.com.br       										//
//////////////////////////////////////////////////////////////////////////

require('ds8.php');
require_once('robot/funcoes.php');
require_once('robot/class.mail.php');


$conexao = new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);

echo "[ROBOT FATURAS] <br>" . PHP_EOL;

// Uso Geral
$sql_dados_empresa = $conexao->query("SELECT * FROM empresa");
$dados_empresa = $sql_dados_empresa->fetch_array(MYSQLI_ASSOC);

$sql_dados_sistema = $conexao->query("SELECT * FROM sistema");
$dados_sistema = $sql_dados_sistema->fetch_array(MYSQLI_ASSOC);


// Verifica se o robot ja foi executado no dia
if($_GET[acao] == '' || $_GET[acao] != 'force') {
    $ano_atual = date("Y");
    $mes_atual = date("m");
    $dia_atual = date("d");

    $sql_robot_executado = $conexao->query("SELECT * FROM logs WHERE robot = 'Faturas' AND YEAR(data) = '$ano_atual' AND MONTH(data) = '$mes_atual' AND DAY(data) = '$dia_atual'");
    $robot_executado = $sql_robot_executado->fetch_array(MYSQLI_ASSOC);

    if($sql_robot_executado->num_rows > 0) {
        die("Atenção! Processo finalizado. Robot de Faturas ja executado em ".date("d/m/Y").".");
        exit;
    }
}

$sql_clientes = $conexao->query("SELECT * FROM clientes");
while ($dados_clientes = $sql_clientes->fetch_array(MYSQLI_ASSOC) ) {



/////////////////////////////////////
//     Início Primeiro Envio       //
/////////////////////////////////////

// Verifica se o cliente tem faturas para o primeiro envio
$data_primeiro_envio = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")+$dados_empresa[envio_fatura1], date("Y")));

$sql_total_faturas_cliente_primeiro_envio = $conexao->query("SELECT * FROM faturas WHERE status = 'off' AND data_vencimento = '".$data_primeiro_envio."' AND codigo_cliente = '".$dados_clientes[codigo]."'");


if($sql_total_faturas_cliente_primeiro_envio->num_rows > 0) {

// Destroi variaveis de uso geral
$valor_total_faturas = 0;
$descricao_faturas = "";

// Unifica as faturas caso esteja habilitado para o cliente
if($dados_clientes[unificar_faturas] == "sim") {

$sql_dados_texto_faturas_unificadas = $conexao->query("SELECT * FROM textos WHERE codigo = '".$dados_empresa[codigo_texto_faturas_unificadas]."'");
$dados_texto_faturas_unificadas = $sql_dados_texto_faturas_unificadas->fetch_array(MYSQLI_ASSOC);

$mes_atual = date("m");
$ano_atual = date("Y");

$sql_faturas_unificadas = $conexao->query("SELECT * FROM faturas WHERE status = 'off' AND MONTH(data_vencimento) <= '".$mes_atual."' AND YEAR(data_vencimento) = '".$ano_atual."' AND codigo_cliente = ".$dados_clientes[codigo]."");

while ($dados_faturas_unificadas = $sql_faturas_unificadas->fetch_array(MYSQLI_ASSOC)) {

// Fatura de Hospedagem(geral) - tipo: h
if($dados_faturas_unificadas[tipo] == 'h') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios
    WHERE codigo = '".$dados_faturas_unificadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);


$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
}
elseif($dados_faturas_unificadas[tipo] == 's') {

$sql_dados_servico = $conexao->query("SELECT * FROM servicos_adicionais
    WHERE codigo = '".$dados_faturas_unificadas[codigo_servico]."'");
$dados_servico = $sql_dados_servico->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento =  $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
}
elseif($dados_faturas_unificadas[tipo] == 'd') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios_registro
    WHERE codigo = '".$dados_faturas_unificadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

}

$conexao->query("UPDATE faturas SET data_envio = NOW() WHERE codigo = '".$dados_faturas_unificadas[codigo]."'");

$valor_total_faturas += $dados_faturas_unificadas[valor];
$descricao_faturas .= $dados_faturas_unificadas[descricao]."<br>";

list($ano,$mes,$dia) = explode("-",$dados_faturas_unificadas[data_vencimento]);
$data_vencimento = $dia."/".$mes."/".$ano;

$codigo_fatura = $dados_faturas_unificadas[codigo];

}

$valor = number_format($valor_total_faturas,2,",",".");

// Forma de pagamento
$dados_pagamento = forma_pagamento($dados_formas_pagamento[codigo],$codigo_fatura);

$mensagem_editada = str_replace ( '[cliente_nome]' , $dados_clientes[nome] , $dados_texto_faturas_unificadas[texto] );
$mensagem_editada = str_replace ( '[cliente_email_principal]' , $dados_clientes[email1] , $mensagem_editada );
$mensagem_editada = str_replace ( '[cliente_senha]' , $dados_clientes[senha] , $mensagem_editada );
$mensagem_editada = str_replace ( '[vencimento]' , $data_vencimento , $mensagem_editada );
$mensagem_editada = str_replace ( '[valor]' , "R$ ".$valor , $mensagem_editada );
$mensagem_editada = str_replace ( '[descricao]' , $descricao_faturas , $mensagem_editada );
$mensagem_editada = str_replace ( '[dados_pagamento]' , $dados_pagamento , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_nome]' , $dados_empresa[nome] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_site]' , $dados_empresa[url_site] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_sistema]' , $dados_empresa[url_sistema] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_logo]' , $dados_empresa[logo] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_email]' , $dados_empresa[email] , $mensagem_editada );

// Envia o e-mail
$envia_Email_resultado = envia_Email($dados_clientes[nome],$dados_clientes[email1],$dados_clientes[email2],$dados_texto_faturas_unificadas[titulo],$mensagem_editada);

if($envia_Email_resultado == "ok") {
$log_fatura_primeiro_envio = "(1º envio)Fatura(s) do cliente <strong>".$dados_clientes[nome]."</strong> enviada(s) com sucesso.";
} else {

// Envia o log para o banco de dados
$inserir_log = "INSERT INTO logs_sistema (descricao,data_hora,tipo,log) VALUES ('Enviar E-mail',NOW(),'erro','Mensagem de erro:<br><br><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #990000;\">".$envia_Email_resultado."</span>')";
$conexao->query($inserir_log);
$codigo_log = $conexao->insert_id();

$log_fatura_primeiro_envio = "(1º envio)Não foi possível enviar a(s) fatura(s) do cliente <strong>".$dados_clientes[nome]."</strong>";
}

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_primeiro_envio')");

// Exibi o log
echo $log_fatura_primeiro_envio."<br>\n";

} else { // unificar == nao

$sql_faturas_primeiro_envio = $conexao->query("SELECT * FROM faturas WHERE status = 'off' AND data_vencimento = '".$data_primeiro_envio."' AND codigo_cliente = '".$dados_clientes[codigo]."'");
while ($dados_faturas_primeiro_envio = $sql_faturas_primeiro_envio->fetch_array(MYSQLI_ASSOC)) {

// Fatura de Hospedagem(geral) - tipo: h
if($dados_faturas_primeiro_envio[tipo] == 'h') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios
    WHERE codigo = '".$dados_faturas_primeiro_envio[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
}
elseif($dados_faturas_primeiro_envio[tipo] == 's') {

$sql_dados_servico = $conexao->query("SELECT * FROM servicos_adicionais
    WHERE codigo = '".$dados_faturas_primeiro_envio[codigo_servico]."'");
$dados_servico = $sql_dados_servico->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
}
elseif($dados_faturas_primeiro_envio[tipo] == 'd') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios_registro
    WHERE codigo = '".$dados_faturas_primeiro_envio[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);
}

$sql_dados_texto_faturas = $conexao->fetch_array("SELECT * FROM textos WHERE codigo = '".$dados_formas_pagamento[texto_fatura]."'");
$dados_texto_faturas = $sql_dados_texto_faturas->fetch_array(MYSQLI_ASSOC);

$conexao->query("UPDATE faturas set data_envio = NOW() WHERE codigo = '".$dados_faturas_primeiro_envio[codigo]."'");

list($ano,$mes,$dia) = explode("-",$dados_faturas_primeiro_envio[data_vencimento]);
$data_vencimento = $dia."/".$mes."/".$ano;

$valor = number_format($dados_faturas_primeiro_envio[valor],2,",",".");

// Forma de pagamento
$dados_pagamento = forma_pagamento($dados_formas_pagamento[codigo],$dados_faturas_primeiro_envio[codigo]);

$mensagem_editada = str_replace ( '[cliente_nome]' , $dados_clientes[nome] , $dados_texto_faturas[texto] );
$mensagem_editada = str_replace ( '[cliente_email_principal]' , $dados_clientes[email1] , $mensagem_editada );
$mensagem_editada = str_replace ( '[cliente_senha]' , $dados_clientes[senha] , $mensagem_editada );
$mensagem_editada = str_replace ( '[vencimento]' , $data_vencimento , $mensagem_editada );
$mensagem_editada = str_replace ( '[valor]' , "R$ ".$valor , $mensagem_editada );
$mensagem_editada = str_replace ( '[descricao]' , $dados_faturas_primeiro_envio[descricao] , $mensagem_editada );
$mensagem_editada = str_replace ( '[dados_pagamento]' , $dados_pagamento , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_nome]' , $dados_empresa[nome] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_site]' , $dados_empresa[url_site] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_sistema]' , $dados_empresa[url_sistema] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_logo]' , $dados_empresa[logo] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_email]' , $dados_empresa[email] , $mensagem_editada );

// Envia o e-mail
$envia_Email_resultado = envia_Email($dados_clientes[nome],$dados_clientes[email1],$dados_clientes[email2],$dados_texto_faturas[titulo],$mensagem_editada);

if($envia_Email_resultado == "ok") {
$log_fatura_primeiro_envio = "(1º envio)Fatura do cliente <strong>".$dados_clientes[nome]."</strong> com data de vencimento em <strong>".$data_vencimento."</strong> enviada com sucesso.";
} else {

// Envia o log para o banco de dados
$inserir_log = "INSERT INTO logs_sistema (descricao,data_hora,tipo,log) VALUES ('Enviar E-mail',NOW(),'erro','Mensagem de erro:<br><br><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #990000;\">".$envia_Email_resultado."</span>')";
$conexao->query($inserir_log);
$codigo_log = $conexao->insert_id();

$log_fatura_primeiro_envio = "(1º envio)Não foi possível enviar a fatura do cliente <strong>".$dados_clientes[nome]."</strong> com data de vencimento em <strong>".$data_vencimento."</strong>";
}

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_primeiro_envio')");

// Exibi o log
echo $log_fatura_primeiro_envio."<br>\n";


}
}

} else { // total faturas primeiro envio < 0
$log_fatura_primeiro_envio = "(1º envio)Nenhuma fatura do cliente <strong>".$dados_clientes[nome]."</strong> encontrada para este envio.";

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_primeiro_envio')");

// Exibi o log
echo $log_fatura_primeiro_envio."<br>\n";

} // fim total faturas primeiro envio
/////////////////////////////////////
//       Fim Primeiro Envio        //
/////////////////////////////////////

/////////////////////////////////////
//      Início Segundo Envio       //
/////////////////////////////////////

// Verifica se o cliente tem faturas para o primeiro envio
$data_segundo_envio = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")+$dados_empresa[envio_fatura2], date("Y")));

$sql_total_faturas_cliente_segundo_envio = $conexao->query("SELECT * FROM faturas
    WHERE status = 'off' AND data_vencimento = '".$data_segundo_envio."'
    AND codigo_cliente = '".$dados_clientes[codigo]."'");
$total_faturas_cliente_segundo_envio = $sql_total_faturas_cliente_segundo_envio->fetch_array(MYSQLI_ASSOC);

if($sql_total_faturas_cliente_segundo_envio->num_rows > 0) {

// Destroi variaveis de uso geral
$valor_total_faturas = 0;
$descricao_faturas = "";

// Unifica as faturas caso esteja habilitado para o cliente
if($dados_clientes[unificar_faturas] == "sim") {

$sql_dados_texto_faturas_unificadas = $conexao->query("SELECT * FROM textos WHERE codigo = '".$dados_empresa[codigo_texto_faturas_unificadas]."'");
$dados_texto_faturas_unificadas = $sql_dados_texto_faturas_unificadas->fetch_array(MYSQLI_ASSOC);

$mes_atual = date("m");
$ano_atual = date("Y");

$sql_faturas_unificadas = $conexao->query("SELECT * FROM faturas WHERE status = 'off' AND MONTH(data_vencimento) <= '".$mes_atual."' AND YEAR(data_vencimento) = '".$ano_atual."' AND codigo_cliente = ".$dados_clientes[codigo]."");

while ($dados_faturas_unificadas = $sql_faturas_unificadas->fetch_array(MYSQLI_ASSOC)) {

// Fatura de Hospedagem(geral) - tipo: h
if($dados_faturas_unificadas[tipo] == 'h') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios
    WHERE codigo = '".$dados_faturas_unificadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
} elseif($dados_faturas_unificadas[tipo] == 's') {

$sql_dados_servico = $conexao->query("SELECT * FROM servicos_adicionais WHERE codigo = '".$dados_faturas_unificadas[codigo_servico]."'");
$dados_servico = $sql_dados_servico->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento WHERE codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
} elseif($dados_faturas_unificadas[tipo] == 'd') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios_registro
    WHERE codigo = '".$dados_faturas_unificadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);


$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento WHERE codigo = '".$dados_dominios[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

}

$conexao->query("UPDATE faturas set data_envio = NOW() WHERE codigo = '".$dados_faturas_unificadas[codigo]."'");

$valor_total_faturas += $dados_faturas_unificadas[valor];
$descricao_faturas .= $dados_faturas_unificadas[descricao]."<br>";

list($ano,$mes,$dia) = explode("-",$dados_faturas_unificadas[data_vencimento]);
$data_vencimento = $dia."/".$mes."/".$ano;

$codigo_fatura = $dados_faturas_unificadas[codigo];

}

$valor = number_format($valor_total_faturas,2,",",".");

// Forma de pagamento
$dados_pagamento = forma_pagamento($dados_formas_pagamento[codigo],$codigo_fatura);

$mensagem_editada = str_replace ( '[cliente_nome]' , $dados_clientes[nome] , $dados_texto_faturas_unificadas[texto] );
$mensagem_editada = str_replace ( '[cliente_email_principal]' , $dados_clientes[email1] , $mensagem_editada );
$mensagem_editada = str_replace ( '[cliente_senha]' , $dados_clientes[senha] , $mensagem_editada );
$mensagem_editada = str_replace ( '[vencimento]' , $data_vencimento , $mensagem_editada );
$mensagem_editada = str_replace ( '[valor]' , "R$ ".$valor , $mensagem_editada );
$mensagem_editada = str_replace ( '[descricao]' , $descricao_faturas , $mensagem_editada );
$mensagem_editada = str_replace ( '[dados_pagamento]' , $dados_pagamento , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_nome]' , $dados_empresa[nome] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_site]' , $dados_empresa[url_site] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_sistema]' , $dados_empresa[url_sistema] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_logo]' , $dados_empresa[logo] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_email]' , $dados_empresa[email] , $mensagem_editada );

// Envia o e-mail
$envia_Email_resultado = envia_Email($dados_clientes[nome],$dados_clientes[email1],$dados_clientes[email2],$dados_texto_faturas_unificadas[titulo],$mensagem_editada);

if($envia_Email_resultado == "ok") {
$log_fatura_segundo_envio = "(2º envio)Fatura(s) do cliente <strong>".$dados_clientes[nome]."</strong> enviada(s) com sucesso.";
} else {

// Envia o log para o banco de dados
$inserir_log = "INSERT INTO logs_sistema (descricao,data_hora,tipo,log) VALUES ('Enviar E-mail',NOW(),'erro','Mensagem de erro:<br><br><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #990000;\">".$envia_Email_resultado."</span>')";
$conexao->query($inserir_log);
$codigo_log = $conexao->insert_id();

$log_fatura_segundo_envio = "(2º envio)Não foi possível enviar a(s) fatura(s) do cliente <strong>".$dados_clientes[nome]."</strong>";
}

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_segundo_envio')");

// Exibi o log
echo $log_fatura_segundo_envio."<br>\n";

} else { // unificar == nao

$sql_faturas_segundo_envio = $conexao->query("SELECT * FROM faturas WHERE status = 'off' AND data_vencimento = '".$data_segundo_envio."' AND codigo_cliente = '".$dados_clientes[codigo]."'");
while ($dados_faturas_segundo_envio = $sql_faturas_segundo_envio->fetch_array(MYSQLI_ASSOC)) {

// Fatura de Hospedagem(geral) - tipo: h
if($dados_faturas_segundo_envio[tipo] == 'h') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios
    WHERE codigo = '".$dados_faturas_segundo_envio[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
} elseif($dados_faturas_segundo_envio[tipo] == 's') {

$sql_dados_servico = $conexao->query("SELECT * FROM servicos_adicionais
    WHERE codigo = '".$dados_faturas_segundo_envio[codigo_servico]."'");
$dados_servico = $sql_dados_servico->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
} elseif($dados_faturas_segundo_envio[tipo] == 'd') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios_registro
    WHERE codigo = '".$dados_faturas_segundo_envio[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento WHERE codigo = '".$dados_dominios[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);
}

$sql_dados_texto_faturas = $conexao->query("SELECT * FROM textos
    WHERE codigo = '".$dados_formas_pagamento[texto_fatura]."'");
$dados_texto_faturas = $sql_dados_texto_faturas->fetch_array(MYSQLI_ASSOC);

$conexao->query("UPDATE faturas set data_envio = NOW() WHERE codigo = '".$dados_faturas_segundo_envio[codigo]."'");

list($ano,$mes,$dia) = explode("-",$dados_faturas_segundo_envio[data_vencimento]);
$data_vencimento = $dia."/".$mes."/".$ano;

$valor = number_format($dados_faturas_segundo_envio[valor],2,",",".");

// Forma de pagamento
$dados_pagamento = forma_pagamento($dados_formas_pagamento[codigo],$dados_faturas_segundo_envio[codigo]);

$mensagem_editada = str_replace ( '[cliente_nome]' , $dados_clientes[nome] , $dados_texto_faturas[texto] );
$mensagem_editada = str_replace ( '[cliente_email_principal]' , $dados_clientes[email1] , $mensagem_editada );
$mensagem_editada = str_replace ( '[cliente_senha]' , $dados_clientes[senha] , $mensagem_editada );
$mensagem_editada = str_replace ( '[vencimento]' , $data_vencimento , $mensagem_editada );
$mensagem_editada = str_replace ( '[valor]' , "R$ ".$valor , $mensagem_editada );
$mensagem_editada = str_replace ( '[descricao]' , $dados_faturas_segundo_envio[descricao] , $mensagem_editada );
$mensagem_editada = str_replace ( '[dados_pagamento]' , $dados_pagamento , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_nome]' , $dados_empresa[nome] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_site]' , $dados_empresa[url_site] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_sistema]' , $dados_empresa[url_sistema] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_logo]' , $dados_empresa[logo] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_email]' , $dados_empresa[email] , $mensagem_editada );

// Envia o e-mail
$envia_Email_resultado = envia_Email($dados_clientes[nome],$dados_clientes[email1],$dados_clientes[email2],$dados_texto_faturas[titulo],$mensagem_editada);

if($envia_Email_resultado == "ok") {
$log_fatura_segundo_envio = "(2º envio)Fatura do cliente <strong>".$dados_clientes[nome]."</strong> com data de vencimento em <strong>".$data_vencimento."</strong> enviada com sucesso.";
} else {

// Envia o log para o banco de dados
$inserir_log = "INSERT INTO logs_sistema (descricao,data_hora,tipo,log) VALUES ('Enviar E-mail',NOW(),'erro','Mensagem de erro:<br><br><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #990000;\">".$envia_Email_resultado."</span>')";
$conexao->query($inserir_log);
$codigo_log = $conexao->insert_id();

$log_fatura_segundo_envio = "(2º envio)Não foi possível enviar a fatura do cliente <strong>".$dados_clientes[nome]."</strong> com data de vencimento em <strong>".$data_vencimento."</strong>";
}

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_segundo_envio')");

// Exibi o log
echo $log_fatura_segundo_envio."<br>\n";


}
}

} else { // total faturas segundo envio < 0
$log_fatura_segundo_envio = "(2º envio)Nenhuma fatura do cliente <strong>".$dados_clientes[nome]."</strong> encontrada para este envio.";

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_segundo_envio')");

// Exibi o log
echo $log_fatura_segundo_envio."<br>\n";

} // fim total faturas segundo envio

/////////////////////////////////////
//        Fim Segundo Envio        //
/////////////////////////////////////

/////////////////////////////////////
//  Início Envio Fatura Atrasada   //
/////////////////////////////////////

// Verifica se o cliente tem faturas para o primeiro envio
$data_atrasada = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")-$dados_empresa[envio_atrasada], date("Y")));

$sql_total_faturas_cliente_atrasadas = $conexao->query("SELECT * FROM faturas
    WHERE status = 'off' AND data_vencimento <= '".$data_atrasada."'
    AND codigo_cliente = '".$dados_clientes[codigo]."'");
$total_faturas_cliente_atrasadas = $sql_total_faturas_cliente_atrasadas->fetch_array(MYSQLI_ASSOC);

if($sql_total_faturas_cliente_atrasadas->num_rows > 0) {

// Destroi variaveis de uso geral
$valor_total_faturas = 0;
$descricao_faturas = "";

// Unifica as faturas caso esteja habilitado para o cliente
if($dados_clientes[unificar_faturas] == "sim") {

$sql_dados_texto_faturas_unificadas = $conexao->query("SELECT * FROM textos WHERE codigo = '".$dados_empresa[codigo_texto_faturas_unificadas]."'");
$dados_texto_faturas_unificadas = $sql_dados_texto_faturas_unificadas->fetch_array(MYSQLI_ASSOC);

$mes_atual = date("m");
$ano_atual = date("Y");

$sql_faturas_unificadas = $conexao->query("SELECT * FROM faturas WHERE status = 'off' AND MONTH(data_vencimento) <= '".$mes_atual."' AND YEAR(data_vencimento) = '".$ano_atual."' AND codigo_cliente = ".$dados_clientes[codigo]."");
while ($dados_faturas_unificadas = $sql_faturas_unificadas->fetch_array(MYSQLI_ASSOC)) {

// Fatura de Hospedagem(geral) - tipo: h
if($dados_faturas_unificadas[tipo] == 'h') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios
        WHERE codigo = '".$dados_faturas_unificadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Serviço Adicional	 - tipo: s
} elseif($dados_faturas_unificadas[tipo] == 's') {

$sql_dados_servico = $conexao->query("SELECT * FROM servicos_adicionais
    WHERE codigo = '".$dados_faturas_unificadas[codigo_servico]."'");
$dados_servico = $sql_dados_servico->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento WHERE codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);

// Fatura de Registro/Transferência de Domínio	 - tipo: d
} elseif($dados_faturas_unificadas[tipo] == 'd') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios_registro
    WHERE codigo = '".$dados_faturas_unificadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento WHERE codigo = '".$dados_dominios[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);
}

$conexao->query("UPDATE faturas set data_envio = NOW() WHERE codigo = '".$dados_faturas_unificadas[codigo]."'");

$valor_total_faturas += $dados_faturas_unificadas[valor];
$descricao_faturas .= $dados_faturas_unificadas[descricao]."<br>";

list($ano,$mes,$dia) = explode("-",$dados_faturas_unificadas[data_vencimento]);
$data_vencimento = $dia."/".$mes."/".$ano;

$codigo_fatura = $dados_faturas_unificadas[codigo];

}

$valor = number_format($valor_total_faturas,2,",",".");

// Forma de pagamento
$dados_pagamento = forma_pagamento($dados_formas_pagamento[codigo],$codigo_fatura);

$mensagem_editada = str_replace ( '[cliente_nome]' , $dados_clientes[nome] , $dados_texto_faturas_unificadas[texto] );
$mensagem_editada = str_replace ( '[cliente_email_principal]' , $dados_clientes[email1] , $mensagem_editada );
$mensagem_editada = str_replace ( '[cliente_senha]' , $dados_clientes[senha] , $mensagem_editada );
$mensagem_editada = str_replace ( '[vencimento]' , $data_vencimento , $mensagem_editada );
$mensagem_editada = str_replace ( '[valor]' , "R$ ".$valor , $mensagem_editada );
$mensagem_editada = str_replace ( '[descricao]' , $descricao_faturas , $mensagem_editada );
$mensagem_editada = str_replace ( '[dados_pagamento]' , $dados_pagamento , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_nome]' , $dados_empresa[nome] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_site]' , $dados_empresa[url_site] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_sistema]' , $dados_empresa[url_sistema] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_logo]' , $dados_empresa[logo] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_email]' , $dados_empresa[email] , $mensagem_editada );

// Envia o e-mail
$envia_Email_resultado = envia_Email($dados_clientes[nome],$dados_clientes[email1],$dados_clientes[email2],$dados_texto_faturas_unificadas[titulo],$mensagem_editada);

if($envia_Email_resultado == "ok") {
$log_fatura_atrasadas = "Fatura(s) atrasada(s) do cliente <strong>".$dados_clientes[nome]."</strong> enviada(s) com sucesso.";
} else {

// Envia o log para o banco de dados
$inserir_log = "INSERT INTO logs_sistema (descricao,data_hora,tipo,log) VALUES ('Enviar E-mail',NOW(),'erro','Mensagem de erro:<br><br><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #990000;\">".$envia_Email_resultado."</span>')";
$conexao->query($inserir_log);
$codigo_log = $conexao->insert_id();

$log_fatura_atrasadas = "Não foi possível enviar a(s) fatura(s) atrasada(s) do cliente <strong>".$dados_clientes[nome]."</strong>";
}

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_atrasadas')");

// Exibi o log
echo $log_fatura_atrasadas."<br>\n";

} else { // unificar == nao

$sql_faturas_atrasadas = $conexao->query("SELECT * FROM faturas WHERE status = 'off' AND data_vencimento <= '".$data_atrasada."' AND codigo_cliente = '".$dados_clientes[codigo]."'");
while ($dados_faturas_atrasadas = $sql_faturas_atrasadas->fetch_array(MYSQLI_ASSOC)) {

// Fatura de Hospedagem(geral) - tipo: h
if($dados_faturas_atrasadas[tipo] == 'h') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios
    WHERE codigo = '".$dados_faturas_atrasadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);


// Fatura de Serviço Adicional	 - tipo: s
} elseif($dados_faturas_atrasadas[tipo] == 's') {

$sql_dados_servico = $conexao->query("SELECT * FROM servicos_adicionais
    WHERE codigo = '".$dados_faturas_atrasadas[codigo_servico]."'");
$dados_servico = $sql_dados_servico->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_servico[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);


// Fatura de Registro/Transferência de Domínio	 - tipo: d
} elseif($dados_faturas_atrasadas[tipo] == 'd') {

$sql_dados_dominios = $conexao->query("SELECT * FROM dominios_registro
    WHERE codigo = '".$dados_faturas_atrasadas[codigo_dominio]."'");
$dados_dominios = $sql_dados_dominios->fetch_array(MYSQLI_ASSOC);

$sql_dados_formas_pagamento = $conexao->query("SELECT * FROM formas_pagamento
    WHERE codigo = '".$dados_dominios[codigo_forma_pagto]."'");
$dados_formas_pagamento = $sql_dados_formas_pagamento->fetch_array(MYSQLI_ASSOC);


}

$sql_dados_texto_faturas_atrasadas = $conexao->query("SELECT * FROM textos WHERE codigo = '".$dados_formas_pagamento[texto_fatura_atrasada]."'");
$dados_texto_faturas_atrasadas = $sql_dados_texto_faturas_atrasadas->fetch_array(MYSQLI_ASSOC);

$conexao->query("UPDATE faturas set data_envio = NOW() WHERE codigo = '".$dados_faturas_atrasadas[codigo]."'");

list($ano,$mes,$dia) = explode("-",$dados_faturas_atrasadas[data_vencimento]);
$data_vencimento = $dia."/".$mes."/".$ano;

$valor = number_format($dados_faturas_atrasadas[valor],2,",",".");

// Forma de pagamento
$dados_pagamento = forma_pagamento($dados_formas_pagamento[codigo],$dados_faturas_atrasadas[codigo]);

$mensagem_editada = str_replace ( '[cliente_nome]' , $dados_clientes[nome] , $dados_texto_faturas_atrasadas[texto] );
$mensagem_editada = str_replace ( '[cliente_email_principal]' , $dados_clientes[email1] , $mensagem_editada );
$mensagem_editada = str_replace ( '[cliente_senha]' , $dados_clientes[senha] , $mensagem_editada );
$mensagem_editada = str_replace ( '[vencimento]' , $data_vencimento , $mensagem_editada );
$mensagem_editada = str_replace ( '[valor]' , "R$ ".$valor , $mensagem_editada );
$mensagem_editada = str_replace ( '[descricao]' , $dados_faturas_atrasadas[descricao] , $mensagem_editada );
$mensagem_editada = str_replace ( '[dados_pagamento]' , $dados_pagamento , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_nome]' , $dados_empresa[nome] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_site]' , $dados_empresa[url_site] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_sistema]' , $dados_empresa[url_sistema] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_url_logo]' , $dados_empresa[logo] , $mensagem_editada );
$mensagem_editada = str_replace ( '[empresa_email]' , $dados_empresa[email] , $mensagem_editada );

// Envia o e-mail
$envia_Email_resultado = envia_Email($dados_clientes[nome],$dados_clientes[email1],$dados_clientes[email2],$dados_texto_faturas_atrasadas[titulo],$mensagem_editada);

if($envia_Email_resultado == "ok") {
$log_fatura_atrasadas = "Fatura atrasada do cliente <strong>".$dados_clientes[nome]."</strong> com data de vencimento em <strong>".$data_vencimento."</strong> enviada com sucesso.";
} else {

// Envia o log para o banco de dados
$inserir_log = "INSERT INTO logs_sistema (descricao,data_hora,tipo,log) VALUES ('Enviar E-mail',NOW(),'erro','Mensagem de erro:<br><br><span style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #990000;\">".$envia_Email_resultado."</span>')";
$conexao->query($inserir_log);
$codigo_log = $conexao->insert_id();

$log_fatura_atrasadas = "Não foi possível enviar a fatura atrasada do cliente <strong>".$dados_clientes[nome]."</strong> com data de vencimento em <strong>".$data_vencimento."</strong>";
}

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_atrasadas')");

// Exibi o log
echo $log_fatura_atrasadas."<br>\n";


}
}

} else { // total faturas atrasadas < 0
$log_fatura_atrasadas = "Nenhuma fatura <strong>atrasada</strong> do cliente <strong>".$dados_clientes[nome]."</strong> encontrada para este envio.";

// Envia o log para o banco de dados
$conexao->query("INSERT INTO logs (robot,data,log) VALUES ('Faturas',NOW(),'$log_fatura_atrasadas')");

// Exibi o log
echo $log_fatura_atrasadas."<br>\n";

} // fim total faturas atrasadas

} // fim clientes
?>
