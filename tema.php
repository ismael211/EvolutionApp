<?php

session_start();

require_once 'inc/config.php';

$core = new IsistemCore();
$core->Connect();

//0 = Tema claro / Light Theme
//1 = Tema escuro / Dark Theme

if(isset($_POST['tema'])){

	$estilo_menu = $_POST['tema'];

	if( $estilo_menu =='0'){
		$estilo_menu = '1';
	}else{
		$estilo_menu = '0';
	}

	$query = $core->Prepare("UPDATE `operadores` SET `layout` = '$estilo_menu' WHERE codigo = '".$_SESSION['codigo_adm']."'");
	$result2 = $query->Execute(); 

  
	if($result2){
		echo "ok";
		exit;
	}else{
		echo "ERRO";
		exit;
	}
}

?>