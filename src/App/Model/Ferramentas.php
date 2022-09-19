<?php
namespace App\Model;

trait Ferramentas {
	
	public function convertDataBD($data)
	{
		list($dia,$mes,$ano) = explode("/",$data);
		return $ano."-".$mes."-".$dia;
	}

	public function convertDataView($data)
	{
		list($ano,$mes,$dia) = explode("-",$data);
		return $dia."/".$mes."/".$ano;
	}

	public function moneyFormatBD($valor)
	{
		$valor = str_replace(".","",$valor);
		$valor = str_replace(",",".",$valor);
		return $valor;
	}

	public function moneyFormatView($valor)
	{
		return number_format($valor,2,",",".");
	}

	// Função para codificar e decodificar strings
	public function encode_decode($texto, $tipo = "E") {

	  if($tipo == "E") {
	  
	  $sesencoded = $texto;
	  $num = mt_rand(0,3);
	  for($i=1;$i<=$num;$i++)
	  {
	     $sesencoded = base64_encode($sesencoded);
	  }
	  $alpha_array =  array('Y','D','U','R','P','S','B','M','A','T','H');
	  $sesencoded =
	  $sesencoded."+".$alpha_array[$num];
	  $sesencoded = base64_encode($sesencoded);
	  return $sesencoded;
	  
	  } else {
	  
	  $alpha_array =
	   array('Y','D','U','R','P','S','B','M','A','T','H');
	   $decoded = base64_decode($texto);
	   list($decoded,$letter) = split("\+",$decoded);
	   for($i=0;$i<count($alpha_array);$i++)
	   {
	   if($alpha_array[$i] == $letter)
	   break;
	   }
	   for($j=1;$j<=$i;$j++)
	   {
	      $decoded = base64_decode($decoded);
	   }
	   return $decoded;
	  }
	}

	// Metodo que gera Key
	public function generateKey()
	{

		$a = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$b = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$c = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$d = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
		$e = strtoupper(substr(md5(rand(11111,99999)), 5, 5));

		return "$a-$b-$c-$d-$e";

	}

	public function dataAumentaDia($dias){
		  $datastamp = '86400' * $dias + mktime(0,0,0, date('m'), date('d'), date('Y'));
		  return date( "d/m/Y" ,$datastamp );
	} 

	public function calculoLicencas($quantidade, $valor, $base)
	{
 		switch ($quantidade) {
 			case '11':
 				return $valor + $base;
 				break;
 			case '21':
 				return $valor + $base;
 				break;
 			case '31':
 				return $valor + $base;
 				break;
 			case '41':
 				return $valor + $base;
 				break;
 			case '51':
 				return $valor + $base;
 				break;
 			case '61':
 				return $valor + $base;
 				break;
 			case '71':
 				return $valor + $base;
 				break;
 			case '81':
 				return $valor + $base;
 				break;
 			case '91':
 				return $valor + $base;
 				break;
 			case '101':
 				return $valor + $base;
 				break;
 			default:
		       return $valor;
		       break;
 		}

	}
	
}