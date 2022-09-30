<?php

session_start();

header('Content-Type: text/html; charset=utf-8');

require 'vendor/autoload.php';

include('inc/config.php');

////////// SYS  ///////////
//include __DIR__ . '/gerencial.php';

///////// CLIENTE /////////
//include __DIR__ . '/cliente.php';


if ($_POST['pagina'] == "Login" || $_GET['pagina'] == "Sair") {
	unset($_SESSION['i']);
    unset($_SESSION["codigo_adm"]);
}


if($_SESSION["codigo_adm"] == '') {
	$pagina_inicial = "login";
} else {
	$pagina_inicial = "home";
}


if (isset($_GET['pagina'])) {
    include 'home';
}else{
    header("Location: ".$pagina_inicial);
}
