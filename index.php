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
