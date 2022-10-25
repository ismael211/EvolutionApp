<?php

session_start();

header('Content-Type: text/html; charset=utf-8');

require 'vendor/autoload.php';


if($_SESSION["codigo_adm"] == '') {
	$pagina_inicial = "/Login";
	// header("Location: ".$pagina_inicial);
}
