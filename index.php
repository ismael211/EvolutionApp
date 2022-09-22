<?php

header('Content-Type: text/html; charset=utf-8');

require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

$app = new Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views',
));
$app->register(new Silex\Provider\SessionServiceProvider());


//filter
$app->before(function() use ($app){
    $app['twig']->addGlobal('msg', "");
    $app['twig']->addGlobal('errors', 0);
});

// Conexao Banco
require 'ds8.php';
$conexao = new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);



////////// SYS  ///////////
include __DIR__ . '/gerencial.php';

///////// CLIENTE /////////
include __DIR__ . '/cliente.php';

$app->run();



session_start();
//////////////////////////////////////////////////////////////////////////
// Isistem Gerenciador Financeiro para Hosts  		                    //
// Descrição: Sistema de Gerenciamento de Clientes		                //
// Site: www.isistem.com.br       										//
//////////////////////////////////////////////////////////////////////////


if ($_POST['pagina'] == "Login" || $_GET['pagina'] == "Sair") {
	unset($_SESSION['i']);
    unset($_SESSION["codigo_cliente"]);
}




if($_SESSION["codigo_cliente"] == '') {
	$pagina_inicial = "Login.php";
} else {
	$dados_sistema = $core->Fetch("SELECT pagina_central_cliente FROM sistema");
	$pagina_inicial = $dados_sistema['pagina_central_cliente'];
}





if (isset($_GET['pagina'])) {
    include 'base.php';
}else{
    header("Location: index.php?pagina=".$pagina_inicial);
}

