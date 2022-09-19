<?php
// inicio painel cliente
$app->get('/', function() use ($app){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{
        return $app->redirect('/painel');
    }
});

// Form Login cliente
$app->get('/login', function() use ($app){
    if (null === $app['session']->get('idUserCliente')) {
        return $app['twig']->render('cliente/login.twig');
    }
    else{
        return $app->redirect('/');
    }    
});

// Painel
$app->get('/painel', function() use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{

        return $app['twig']->render('/cliente/painel_cliente.twig');
       
    }
});

// Login Form Action
$app->post('/login', function () use ($app, $conexao) {
    $username = $app['request']->request->get('username');
    $password = $app['request']->request->get('password');
    
    $login = new App\Model\Cliente\Login($conexao);
    $login->setUser($username);
    $login->setPass($password);
    $return = $login->toLogin();

    if ($return["error"] == "0") {
        $app['session']->set('userCliente', $return["nome"]);
        $app['session']->set('idUserCliente', $return["codigo"]);

        return $app['twig']->render('redirect.twig', array(
                'redirect' => '/painel'
            ));
    }
    else{
        return $app['twig']->render('alerta_erro.twig', array(
                'errors', $return["error"],
                'msg' => $return["msg"]
            ));
    }
});

// Licenca
$app->get('/licencas', function() use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{

        $licenca = new App\Model\Cliente\Licenca($conexao);
        $licenca->setCodUser($app['session']->get('idUserCliente'));
        $return = $licenca->getAll();

        // Clientes
        $cliente = new App\Model\Cliente\Cliente($conexao);
        $cliente->setCodUser($app['session']->get('idUserCliente'));
        $status_cliente = $cliente->viewDados();

        return $app['twig']->render('/cliente/licencas.twig', array(
                "licen" => $return,
                "tipo_cliente" => $status_cliente[0]["tipo_cliente"]
            ));  
    }
});

// Cadastrar Licenca
$app->get('/cadastrar/licenca', function() use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{

        return $app['twig']->render('/cliente/cadastrar_licenca.twig');  
    }
});

// Financeiro
$app->get('/financeiro', function() use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{

        $finan = new App\Model\Cliente\Financeiro($conexao);
        $finan->setCodUser($app['session']->get('idUserCliente'));
        $return_historico = $finan->getAllFaturas();
        $return_abertas = $finan->getAllFaturasOpen();

        return $app['twig']->render('/cliente/financeiro.twig', array(
                "faturas" => $return_historico,
                "faturas_abertas" => $return_abertas
            ));  
    }
});


// Visualizar Fatura
$app->get('/visualizar/fatura/{codigo}', function($codigo) use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{

       $finan = new App\Model\Cliente\Financeiro($conexao);
       $return = $finan->getFaturaById($codigo);

       $forma_pagamento = $finan->formaDePagamento($codigo,$app['session']->get('idUserCliente'));

       return $app['twig']->render('/cliente/visualizar_fatura.twig', array(
            "fatura" => $return,
            "forma_pagamento" => $forma_pagamento
        )); 
    }
});

// Cadastrar licenca action
// /cadastrar/licenca
$app->post('/cadastrar/licenca', function () use ($app, $conexao) {
    
    $sub_dominio = $app['request']->request->get('sub_dominio');
    $status_sub = $app['request']->request->get('status_sub');
    
    $licenca = new App\Model\Cliente\Licenca($conexao);
    $licenca->setCodUser($app['session']->get('idUserCliente'));
    $licenca->setSubDominio($sub_dominio);

    $return_check = $licenca->check(new App\Model\DataValidator());
    if ($return_check["error"] == "1") {
        return $app['twig']->render('alerta_erro.twig', array(
                'errors', $return_check["error"],
                'msg' => $return_check["msg"]
            ));
    }
    else{

          $return = $licenca->cadastrarLicenca();
          if($return["error"] == "1"){
                return $app['twig']->render('alerta_erro.twig', array(
                    'errors', $return["error"],
                    'msg' => $return["msg"]
                ));
          }
          else{
                return $app['twig']->render('redirect.twig', array(
                        'redirect' => '/visualizar/key/'.$return["key"].''
                    ));
          }
    }

});

// Visualizar Key
$app->get('/visualizar/key/{key}', function($key) use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{
        return $app['twig']->render('/cliente/visualizar_key.twig', array(
            'key_valor' => $key
        ));
    }
});

// Acoes licenca clientes
// /licenca/actions/
$app->post('/licenca/actions', function() use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{

        $opcoes = $app['request']->request->get('opcoes');
        $id_licenca = $app['request']->request->get('idlicenca');

        $licenca = new App\Model\Cliente\Licenca($conexao);
        $licenca->setAction($opcoes);
        $licenca->setCodUser($app['session']->get('idUserCliente'));
        $licenca->setCodLicenca($id_licenca);
        $return = $licenca->action();

        if($return["error"] == "1" and $return["sucess"] == "0"){
              return $app['twig']->render('alerta_erro.twig', array(
                  'errors', $return["error"],
                  'msg' => $return["msg"]
              ));
        }
        else{
               return $app['twig']->render('alerta_sucesso_reload.twig', array(
                  'msg' => $return["msg"]
              ));
        }
    }
});


// Acesso Perfil Cliente
$app->get('/perfil', function() use ($app, $conexao){
    if (null === $app['session']->get('idUserCliente')) {
        return $app->redirect('/login');
    }
    else{

        $cliente = new App\Model\Cliente\Cliente($conexao);
        $cliente->setCodUser($app['session']->get('idUserCliente'));
        $return = $cliente->viewDados();

        return $app['twig']->render('/cliente/perfil.twig', array(
                "nome" => $return[0]["nome"],
                "email" => $return[0]["email1"],
                "email_sec" => $return[0]["email2"],
                "telefone" => $return[0]["fone"],
                "celular" => $return[0]["celular"]
            ));
    }
});

// logout
$app->get('/logout', function() use ($app){
    $app['session']->set('userCliente', false);
    $app['session']->set('idUserCliente', false);
    $app['session']->clear();
    return $app->redirect('/login');
});