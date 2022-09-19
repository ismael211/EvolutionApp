<?php

$codigobanco = "027";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//carteira é CNR
$carteira = $dadosboleto["carteira"];
//codigocedente deve possuir 7 caracteres
$codigocedente = formata_numero($dadosboleto["codigo_cedente"],5,0);

$ndoc = $dadosboleto["numero_documento"];
$vencimento = $dadosboleto["data_vencimento"];

// número do documento (sem dvs) é 13 digitos
$nnum = formata_numero($dadosboleto["numero_documento"],13,0);
// nosso número (com dvs) é 16 digitos
$nossonumero = geraNossoNumero($nnum,$codigocedente,$vencimento,'4');

$vencjuliano = dataJuliano($vencimento);
$app = "2";

$barra = "$codigobanco$nummoeda$fator_vencimento$valor$codigocedente".substr($nnum,0,3).$carteira.substr($nnum,3,10). "027";

//base de 23 posições da chave de 25 digitos
$chave = $codigocedente.substr($nnum,0,3).$carteira.substr($nnum,3,10) . "027";

$dv1 = m10dv1($chave);
$dv2 = m10dv2($chave.$dv1);

//chave de 25 posições
$linha = $barra.$dv1.$dv2;

$dv = digitoVerificador_barra($linha, 9, 0);

//chave de 25 posições com o dígito verificador
$codigo_barras = substr($linha,0,4).$dv.substr($linha,4);


$agencia_codigo = $codigocedente;

$dadosboleto["codigo_barras"] = $codigo_barras;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($codigo_barras,$dv);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

function geraNossoNumero($ndoc) {
	return $ndoc."-".digitoVerificador_nossonumero($ndoc);
}

function dataJuliano($data) {
	$dia = (int)substr($data,0,2);
	$mes = (int)substr($data,3,2);
	$ano = (int)substr($data,6,4);
	$dataf = strtotime("$ano/$mes/$dia");
	$datai = strtotime(($ano-1).'/12/31');
	$dias  = (int)(($dataf - $datai)/(60*60*24));
  return str_pad($dias,3,'0',STR_PAD_LEFT).substr($data,9,4);
}

function digitoVerificador_nossonumero($numero) {
	$dv = modulo_11_invertido($numero);
	$dv = $dv.'3';
	$dv = $dv.modulo_11_invertido($numero.$dv);
	return $dv;
}

function digitoVerificador_barra($numero) {
	$resto2 = modulo_11($numero, 9, 1);
     if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
        $dv = 1;
     } else {
	 	$dv = 11 - $resto2;
     }
	 return $dv;
}


// FUNÇÕES
// Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		retira as virgulas
		formata o numero
		preenche com zeros
		*/
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo = "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}


function fbarcode($valor){

$fino = 1 ;
$largo = 3 ;
$altura = 50 ;

  $barcodes[0] = "00110" ;
  $barcodes[1] = "10001" ;
  $barcodes[2] = "01001" ;
  $barcodes[3] = "11000" ;
  $barcodes[4] = "00101" ;
  $barcodes[5] = "10100" ;
  $barcodes[6] = "01100" ;
  $barcodes[7] = "00011" ;
  $barcodes[8] = "10010" ;
  $barcodes[9] = "01010" ;
  for($f1=9;$f1>=0;$f1--){ 
    for($f2=9;$f2>=0;$f2--){  
      $f = ($f1 * 10) + $f2 ;
      $texto = "" ;
      for($i=1;$i<6;$i++){ 
        $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      }
      $barcodes[$f] = $texto;
    }
  }


//Desenho da barra


//Guarda inicial
?><img src=boletos/imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=boletos/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=boletos/imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=boletos/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
<?php
$texto = $valor ;
if((strlen($texto) % 2) <> 0){
	$texto = "0" . $texto;
}

// Draw dos dados
while (strlen($texto) > 0) {
  $i = round(esquerda($texto,2));
  $texto = direita($texto,strlen($texto)-2);
  $f = $barcodes[$i];
  for($i=1;$i<11;$i+=2){
    if (substr($f,($i-1),1) == "0") {
      $f1 = $fino ;
    }else{
      $f1 = $largo ;
    }
?>
    src=boletos/imagens/p.png width=<?php echo $f1?> height=<?php echo $altura?> border=0><img 
<?php
    if (substr($f,$i,1) == "0") {
      $f2 = $fino ;
    }else{
      $f2 = $largo ;
    }
?>
    src=boletos/imagens/b.png width=<?php echo $f2?> height=<?php echo $altura?> border=0><img 
<?php
  }
}

// Draw guarda final
?>
src=boletos/imagens/p.png width=<?php echo $largo?> height=<?php echo $altura?> border=0><img 
src=boletos/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=boletos/imagens/p.png width=<?php echo 1?> height=<?php echo $altura?> border=0> 
  <?php
} //Fim da função

function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

function fator_vencimento($data) {
	$data = split("/",$data);
	$ano = $data[2];
	$mes = $data[1];
	$dia = $data[0];
    return(abs((_dateToDays("1997","10","07")) - (_dateToDays($ano, $mes, $dia))));
}

function _dateToDays($year,$month,$day) {
    $century = substr($year, 0, 2);
    $year = substr($year, 2, 2);
    if ($month > 2) {
        $month -= 3;
    } else {
        $month += 9;
        if ($year) {
            $year--;
        } else {
            $year = 99;
            $century --;
        }
    }
    return ( floor((  146097 * $century)    /  4 ) +
            floor(( 1461 * $year)        /  4 ) +
            floor(( 153 * $month +  2) /  5 ) +
                $day +  1721119);
}

function modulo_10($num) { 
		$numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo (falor 10)
            // 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
            $temp = $numeros[$i] * $fator; 
            $temp0=0;
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }
		
        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }
		
        return $digito;
		
}


function m10dv1($num) {
	$ftini=2;
	$ftfim=1;
	
	$fator = $ftini;
    $soma = 0;
	
    for ($i = strlen($num); $i > 0; $i--) {
			$p = substr($num,$i-1,1) * $fator;
			if($p > 9 ) $soma += $p - 9;
			if($p <= 9) $soma +=  $p;
			
			if(--$fator < $ftfim) $fator = $ftini;
    }
    $resto = $soma % 10;
	
	if($resto == 0) $digito = 0;
	if($resto > 0) $digito = 10 - $resto;

	return $digito;
		
}
function somaPesos($num,$ftini=7,$ftfim=2){
	$fator = $ftini;
    $soma = 0;
    for ($i = 0; $i < strlen($num); $i++) {
			$soma += substr($num,$i,1) * $fator;
			if(--$fator < $ftfim) $fator = $ftini;
    }
	return $soma;
}
function m10dv2($num) { 
	$soma = somaPesos($num);
	
    $resto = $soma % 11;
	
	if($resto == 0) $y =  0;
	if($resto == 1){ $y= somaPesos( substr($num,$i,1) == 9 ? $num-9 : $num-+1 );}
	if($resto > 1){
		
		$y = 11 - $resto;
		$y = $y+2;
		if($y == 10) $y = 0;
		if($y == 11) $y = 1;
	}
	return $y;
		
}

function modulo_11($num, $base=9, $r=0)  {
    $soma = 0;
    $fator = 2;

    /* Separacao dos numeros */
    for ($i = strlen($num); $i > 0; $i--) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num,$i-1,1);
        // Efetua multiplicacao do numero pelo falor
        $parcial[$i] = $numeros[$i] * $fator;
        // Soma dos digitos
        $soma += $parcial[$i];
        if ($fator == $base) {
            // restaura fator de multiplicacao para 2 
            $fator = 1;
        }
        $fator++;
    }

    /* Calculo do modulo 11 */
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }
        return $digito;
    } elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}

function modulo_11_invertido($num)  { // Calculo de Modulo 11 "Invertido" (com pesos de 9 a 2  e não de 2 a 9)
    $ftini = 0;
		$ftfim = 9;
		$fator = $ftfim;
    $soma = 0;
	
    for ($i = strlen($num); $i > 0; $i--) {
			$soma += substr($num,$i-1,1) * $fator;
			if(--$fator < $ftini) $fator = $ftfim;
    }
	
    $digito = $soma % 11;
		if($digito > 9) $digito = 0;
	
		return $digito;
}

function monta_linha_digitavel($codigo,$dv) {
	// Posição 	Conteúdo
	// 1 a 3    Número do banco
	// 4        Código da Moeda - 9 para Real
	// 5        Digito verificador do Código de Barras
	// 6 a 9    Fator de Vencimento
	// 10 a 19  Valor (8 inteiros e 2 decimais)
	//          Campo Livre definido por cada banco (25 caracteres)
	// 20 a 26  Código do Cedente
	// 27 a 39  Código do Documento
	// 40 a 43  Data de Vencimento em Juliano (mmmy)
	// 44       Código do aplicativo CNR = 2
	
	// 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
	// do campo livre e DV (modulo10) deste campo
	
	$campo1 = substr($codigo,0,4) . substr($codigo,19,5);
	$campo1 = $campo1 . modulo_10($campo1);
	$campo1 = substr($campo1,0,5) . '.' . substr($campo1,5,5);
	
	// 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
	// e livre e DV (modulo10) deste campo
	$campo2 = substr($codigo,24,2) . substr($codigo,26,8);
	$campo2 = $campo2 . modulo_10($campo2);
	$campo2 = substr($campo2,0,5) . '.' . substr($campo2,5,6);

	
	// 3. Campo composto pelas posicoes 16 a 25 do campo livre
	// e livre e DV (modulo10) deste campo
	$campo3 = substr($codigo,34,5) . substr($codigo,39,4) . substr($codigo,43,1);
	$campo3 = $campo3 . modulo_10($campo3);
	$campo3 = substr($campo3,0,5) . '.' . substr($campo3,5,6);

	// 4. Campo - digito verificador do codigo de barras
	$campo4 = $dv;
	
	// 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
	// indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
	// tratar de valor zerado, a representacao deve ser 000 (tres zeros).
	$campo5 = substr($codigo, 5, 4) . substr($codigo, 9, 10);
	
	return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
}

function geraCodigoBanco($numero) {
    $parte1 = substr($numero, 0, 3);
    $parte2 = modulo_11($parte1);
    return $parte1 . "-" . $parte2;
}

?>