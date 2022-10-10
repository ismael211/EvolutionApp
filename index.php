<?php

session_start();

header('Content-Type: text/html; charset=utf-8');

require 'vendor/autoload.php';

// include('inc/config.php');


if($_SESSION["codigo_adm"] == '') {
	$pagina_inicial = "/Login";
	header("Location: ".$pagina_inicial);
}


// if (isset($_GET['pagina'])) {
//     include 'home';
// }else{
//     header("Location: ".$pagina_inicial);
// }
// if ($_POST['pagina'] == "Login" || $_GET['pagina'] == "Sair") {
// 	unset($_SESSION['i']);
//     unset($_SESSION["codigo_adm"]);
// }