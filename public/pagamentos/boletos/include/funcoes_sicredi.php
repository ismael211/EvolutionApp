<?php
################# Configurações do Boleto ##################

$valor = $dadosboleto["valor_boleto"];
$vencimento = $dadosboleto["data_vencimento"];
$documento = $dadosboleto["numero_documento"];

$banco = '748';
$agencia = $dadosboleto["agencia"];
$conta = $dadosboleto["conta"];
$posto_cedente = $dadosboleto["posto_cedente"];
$codigo_cedente = $dadosboleto["codigo_cedente"];
$tipo_moeda = '9';
$carteira = $dadosboleto["carteira"];
$byte = '2';
$especie = $dadosboleto["especie_doc"];
$aceite = $dadosboleto["aceite"];
$moeda = $dadosboleto["especie"];
$tipo_cobranca = '3';
$tipo_carteira = '1';

//Variáveis automáticas
$ano = date('y');
$hoje = date('j/m/Y');

//Funções
function RetiraDigito($argumento){
	return str_replace("-","",$argumento);
}

function digito_verificador($argumento){
	$quantidade = strlen($argumento);
	
	//Realiza multiplicação e soma
	$fator = 2;
	for($i = $quantidade - 1; $i >= 0; $i--){
		$multiplicacao = $argumento[$i] * $fator;
		$soma += $multiplicacao;
		
		//Define novo fator
		if($fator == 9){
			$fator = 2;
		}else{
			$fator++;
		}
	}
	
	//Restaga resto da divisão
	$valor1 = floor($soma / 11);
	$valor2 = $valor1 * 11;
	$resto = $soma - $valor2;
	
	$digito_verificador = 11 - $resto;
	
	//Verifica digito
	if($digito_verificador > 9){
		$digito_verificador = 0;
	}
	
	return $digito_verificador;
}

function dv_codigo_barras($argumento){
	$quantidade = strlen($argumento);
	
	//Realiza multiplicação e soma
	$fator = 2;
	for($i = $quantidade - 1; $i >= 0; $i--){
		$multiplicacao = $argumento[$i] * $fator;
		$soma += $multiplicacao;
		
		//Define novo fator
		if($fator == 9){
			$fator = 2;
		}else{
			$fator++;
		}
	}
	
	//Restaga resto da divisão
	$valor1 = floor($soma / 11);
	$valor2 = $valor1 * 11;
	$resto = $soma - $valor2;
	
	$digito_verificador = 11 - $resto;
	
	//Verifica digito
	if($digito_verificador > 9 || $digito_verificador < 2){
		$digito_verificador = 1;
	}
	
	return $digito_verificador;
}

function modulo10($argumento){
	//Valores de multiplicação
	$modulo10 = '212121212121212121212';
	$quantidade = strlen($argumento);
	
	//Realiza multiplicação e soma
	for($i = $quantidade; $i >= 1; $i--){
		$multiplicacao = $argumento[$quantidade - $i] * $modulo10[$i - 1];
		
		if($multiplicacao > 9){
			$multiplicacao -= 9;
		}
		
		$soma += $multiplicacao;
	}
	
	//Restaga resto da divisão
	$valor1 = floor($soma / 10);
	$valor2 = $valor1 * 10;
	$resto = $soma - $valor2;
	
	$digito_verificador = 10 - $resto;
	
	//Verifica digito
	if($digito_verificador == 10){
		$digito_verificador = 0;
	}
	
	return $digito_verificador;
}

function fator_vencimento($inicio,$termino){
	$inicio = explode("/", $inicio);
	
	$dia = $inicio[0];
	$mes = $inicio[1];
	$ano = $inicio[2];
	
	$inicio = mktime(0, 0, 0, $mes, $dia, $ano);
	
	$termino = explode("/", $termino);
	
	$dia = $termino[0];
	$mes = $termino[1];
	$ano = $termino[2];
	
	$termino = mktime(0, 0, 0, $mes, $dia, $ano);
	
	$tempo = $termino - $inicio;
	$tempo = floor($tempo / 86400);
	
	return $tempo;
}

function zeros($caracteres,$argumento){
	$quantidade = strlen($argumento);
	$addZeros = '';
	
	if($quantidade < $caracteres){
		for($i = $quantidade; $i < $caracteres; $i++){
			$addZeros .= '0';
		}
	}
	
	$resultado = $addZeros . $argumento;
	
	return $resultado;
}

function monta_campo($campo){
   $parte_1 = substr($campo,0,5);
   $parte_2 = substr($campo,5);
   
   $resultado = $parte_1 . '.' . $parte_2;
   
   return $resultado;
}

function formata_numero($argumento){
	$argumento = str_replace("/","",$argumento);
	$argumento = str_replace("-","",$argumento);
	$argumento = str_replace(",","",$argumento);
	$argumento = str_replace(".","",$argumento);
	
	return $argumento;
}

#### NOSSO NÚMERO ####

//Digito verificador
$digito_verificador = $agencia . $posto_cedente . RetiraDigito($conta) . $ano . $byte . $documento;

$digito_verificador = digito_verificador($digito_verificador);

//Nosso número
$nosso_numero = $ano . "/" . $byte . $documento . '-' . $digito_verificador;

#### CODIGO DE BARRAS ####

//Cria campo livre
$campo_livre = $tipo_cobranca . $tipo_carteira . formata_numero($nosso_numero) . $agencia . $posto_cedente . $codigo_cedente . '1' . '0';

$campo_livre .= digito_verificador($campo_livre);

//Verifica fator de vencimento
$fator_vencimento = fator_vencimento('07/10/1997',$vencimento);

//Monta código de barras
$codigo_barras = $banco . $tipo_moeda . zeros(4,$fator_vencimento) . zeros(10,formata_numero($valor)) . $campo_livre;

//Calcula DV do código de barras
$dv = dv_codigo_barras($codigo_barras);

//Insere DV no código de barras
$primeira_parte = substr($codigo_barras,0,4);
$ultima_parte = substr($codigo_barras,4);

$codigo_barras = $primeira_parte . $dv . $ultima_parte;

#### LINHA DIGITADA ####

$parte_1 = $banco . $tipo_moeda . substr($campo_livre,0,5);
$parte_1 .= modulo10($parte_1);

$parte_2 = substr($campo_livre,5,10);
$parte_2 .= modulo10($parte_2);

$parte_3 = substr($campo_livre,15);
$parte_3 .= modulo10($parte_3);

$parte_4 = $dv;

$parte_5 = zeros(4,$fator_vencimento) . zeros(10,formata_numero($valor));

$parte_1 = monta_campo($parte_1);
$parte_2 = monta_campo($parte_2);
$parte_3 = monta_campo($parte_3);

$linha_digitada = $parte_1 . ' ' . $parte_2 . ' ' . $parte_3 . ' ' . $parte_4 . ' ' . $parte_5;
?>