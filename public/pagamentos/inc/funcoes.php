<?php
header("Content-Type: text/html;  charset=ISO-8859-1",true);

// Funчуo para codificar e decodificar strings
function encode_decode($texto, $tipo = "E") {

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
  
  } 
  else
  {
  
		$alpha_array =array('Y','D','U','R','P','S','B','M','A','T','H');

		$decoded = base64_decode($texto);

		//list($decoded,$letter) = split("\+",$decoded);
		list($decoded,$letter) = preg_split("/\+/",$decoded);

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


