<?php 

session_start();

require_once 'inc/config.php';

$core = new IsistemCore();
$core->Connect();

if(isset($_POST['menu']) && isset($_POST['codigo'])){
	$estilo_menu = $_POST['menu'];
    $codigo_adm = $_POST['codigo'];

	if( $estilo_menu == 'expanded'){
		$estilo_menu = 'collapsed';
	}else{
		$estilo_menu = 'expanded';
	}

	$query = $core->Prepare("UPDATE `operadores` SET `menu` = '$estilo_menu' WHERE `codigo` = '$codigo_adm'");
	$result2 = $query->Execute(); 

	if($result2){
		echo "ok";
		exit;
	}else{
		echo "ERRO";
		exit;
	}
}