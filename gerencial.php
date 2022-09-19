<?php
// inicio Sys
$app->get('/sys/', function() use ($app){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{
        return $app->redirect('/sys/painel');
    }
});

// Form Login
$app->get('/sys/login', function() use ($app){
    if (null === $app['session']->get('idUser')) {
        return $app['twig']->render('login.twig');
    }
    else{
        return $app->redirect('/sys');
    }

});

// Login Form Action
$app->post('/sys/login', function () use ($app, $conexao) {
    $username = $app['request']->request->get('username');
    $password = $app['request']->request->get('password');

    $login = new App\Model\Login($conexao);
    $login->setTable("operadores");
    $login->setUser($username);
    $login->setPass($password);
    $return = $login->toLogin();

    if ($return["error"] == "0") {
        $app['session']->set('user', array('username' => $username));
        $app['session']->set('userName', $username);
        $app['session']->set('idUser', $return["codigo"]);

        return $app['twig']->render('redirect.twig', array(
                'redirect' => '/sys/painel'
            ));
    }
    else{
        return $app['twig']->render('alerta_erro.twig', array(
                'errors', $return["error"],
                'msg' => $return["msg"]
            ));
    }
});

// Painel
$app->get('/sys/painel', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $clientes = new App\Model\Clientes($conexao);
        $clientes->setTable("clientes");
        $qtd_clientes_ativos = $clientes->getCountAllActive();
        $qtd_clientes_prop = $clientes->getCountAllProc();

        $licencas = new App\Model\Licenca($conexao);
        $licencas->setTable("licenca");
        $qtd_licencas = $licencas->getCountAllActive();

        $dataHoje = date("Y-m-d");
        $faturas = new App\Model\Faturas($conexao);
        $faturas->setDataFind($dataHoje);
        $vencendo_hoje = $faturas->vencendoPorData();
        $allFaturasAbertas = $faturas->getCountFaturasAberta();

        return $app['twig']->render('painel.twig',array(
            'qtd_clientes_ativos'  =>  $qtd_clientes_ativos,
            'qtd_licencas'  => $qtd_licencas,
            'qtd_clientes_prop' => $qtd_clientes_prop,
            'vencendo_hoje' => $vencendo_hoje,
            'allFaturasAbertas' => $allFaturasAbertas,
            ));
    }
});

// Clientes
$app->get('/sys/clientes', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{
        $clientes = new App\Model\Clientes($conexao);
        $clientes->setTable("clientes");
        $return = $clientes->getAll();

        return $app['twig']->render('clientes.twig', array(
                'clie' => $return,
            ));
    }
});

// Action Botao Cliente
$app->post('/sys/clientes/action', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

       $clientes = new App\Model\Clientes($conexao);

       $clientes->setTable("clientes");
       $clientes->setId($app['request']->request->get('codigo_cli'));
       $clientes->setAction($app['request']->request->get('opcoes'));
       $return = $clientes->change();

       if ($return["erro"] == "1") {
            return $app['twig']->render('alerta_erro.twig', array(
                'errors', $return["erro"],
                'msg' => $return["msg"]
            ));
       }
       if ($return["erro"] == "0" and $return["page"] == "0") {
            return $app['twig']->render('alerta_sucesso_reload.twig', array(
                  'msg' => $return["msg"]
            ));
       }
       if($return["erro"] == "0" and $return["page"] == "1"){
            return $app['twig']->render('redirect.twig', array(
                "redirect" => "/sys/cliente/editar/".$return["codigo_cliente"]
            ));
       }

    }
});

// Clientes Cadastrar
$app->get('/sys/cadastrar/clientes', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $planos = new App\Model\Planos($conexao);
        $planos->setTable("servicos_modelos");
        $return_planos_servicos = $planos->getAllPlano();

        $formaPagamento = new App\Model\PagamentoFormas($conexao);
        $formaPagamento->setTable("formas_pagamento");
        $return_forma_pagamento = $formaPagamento->getAll();

        return $app['twig']->render('clientesCadastrar.twig', array(
            "tipo_plano_servicos" => $return_planos_servicos,
            "forma_pagamento" => $return_forma_pagamento
            ));
    }
});

// Form Action Clientes Cadastrar
$app->post('/sys/cadastrar/clientes', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $cli_cadastro = new App\Model\ClienteCadastro($conexao);
        $cli_cadastro->tipoCliente($app['request']->request->get('tipo_cliente'));
        $cli_cadastro->nome($app['request']->request->get('nome'));
        $cli_cadastro->tipoPessoa($app['request']->request->get('tipo_pessoa'));
        $cli_cadastro->rg($app['request']->request->get('rg'));
        $cli_cadastro->cpf($app['request']->request->get('cpf'));
        $cli_cadastro->dataNascimento($app['request']->request->get('data_nac'));
        $cli_cadastro->cnpj($app['request']->request->get('cnpj'));
        $cli_cadastro->razaoSocial($app['request']->request->get('razao_social'));
        $cli_cadastro->telefone($app['request']->request->get('fone'));
        $cli_cadastro->celular($app['request']->request->get('celular'));
        $cli_cadastro->email($app['request']->request->get('email1'));
        $cli_cadastro->emailSecundario($app['request']->request->get('email2'));
        $cli_cadastro->senha($app['request']->request->get('senha'));
        $cli_cadastro->rSenha($app['request']->request->get('r_senha'));
        $cli_cadastro->obs($app['request']->request->get('obs'));
        $cli_cadastro->status($app['request']->request->get('status_cli'));

        $cli_cadastro->tipoPlano($app['request']->request->get('tipo_plano'));
        $cli_cadastro->formaPagamento($app['request']->request->get('forma_pagamento'));
        $cli_cadastro->diaVencimento($app['request']->request->get('dia_vencimento'));
        $cli_cadastro->parceiro($app['request']->request->get('parceiro'));

        $return = $cli_cadastro->check(new App\Model\DataValidator());

        if ($return["error"] == "1") {
            return $app['twig']->render('alerta_erro.twig', array(
                    'errors', $return["error"],
                    'msg' => $return["msg"]
                ));
        }
        else{
            $save = $cli_cadastro->save();
            if ($save["error"] == "1") {
                return $app['twig']->render('alerta_erro.twig', array(
                                    'errors', $save["error"],
                                    'msg' => $save["msg"]
                                ));
            }
            else{
                return $app['twig']->render('redirect.twig', array(
                        'redirect' => '/sys/cadastrar/licenca'
                    ));
            }
        }

    }
});

// Ativar Cliente
$app->post('/sys/cliente/mudastatus', function() use ($app, $conexao){
     if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $cliente = new App\Model\Clientes($conexao);
        $cliente->setTable("clientes");
        $cliente->setId($app['request']->request->get('id'));
        $cliente->setStatus($app['request']->request->get('valor_status'));
        $return_status = $cliente->mudaStatus();

        if ( $return_status["erro"] == "0") {

              $id_retorno = $return_status["id_valor"];
              $valoor_status_novo = $return_status["novo_status"];

              return $app['twig']->render('muda_status.twig', array(
                    "id_valor" => $id_retorno,
                    "novo_status" => $valoor_status_novo
                ));
        }
        else {
            return $app['twig']->render('alerta_erro.twig', array(
                                'msg' => $return_status["msg_erro"]
                            ));
        }


    }
});

// Licencas
$app->get('/sys/licencas', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{
        $licencas = new App\Model\Licenca($conexao);
        $licencas->setTable("licenca");
        $return = $licencas->getAll();

        return $app['twig']->render('licencas.twig',array(
             'licen' => $return,
            ));
    }
});

// Cadastrar Licenca
$app->get('/sys/cadastrar/licenca', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{
        $clientes = new App\Model\Clientes($conexao);
        $clientes->setTable("clientes");
        $return = $clientes->getAllForStatus("a");

        $licenca = new App\Model\LicencaCadastro($conexao);
        $key = $licenca->genKey();

        return $app['twig']->render('licencaCadastrar.twig', array(
                'clie' => $return,
                'key_gerada' => $key
            ));
    }
});

$app->post('/sys/cadastrar/licenca', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $licenca_cadastro = new App\Model\LicencaCadastro($conexao);

        $licenca_cadastro->cliente($app['request']->request->get('clientef'));
        $licenca_cadastro->subDominio($app['request']->request->get('sub_dominio'));
        $licenca_cadastro->key($app['request']->request->get('key_sub'));
        $licenca_cadastro->status($app['request']->request->get('status_sub'));
        $licenca_cadastro->setup($app['request']->request->get('setup'));

        $return = $licenca_cadastro->check(new App\Model\DataValidator());

        if ($return["error"] == "1") {
              return $app['twig']->render('alerta_erro.twig', array(
                      'errors', $return["error"],
                      'msg' => $return["msg"]
                  ));
        }
        else{
            $save =  $licenca_cadastro->save();
            if ($save["error"] == "1") {
                return $app['twig']->render('alerta_erro.twig', array(
                                    'errors', $save["error"],
                                    'msg' => $save["msg"]
                                ));
            }
            else{
                return $app['twig']->render('redirect.twig', array(
                        'redirect' => '/sys/licencas'
                    ));
            }
        }

    }
});

// Financeiro
$app->get('/sys/financeiro', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $faturas = new App\Model\Faturas($conexao);
        $faturas->setDataFind(date("Y-m-d"));
        $return = $faturas->vencendoPorData();
        $return_vencidas = $faturas->vencidas();

        return $app['twig']->render('financeiro.twig', array(
                "faturas_vencendo_hoje" => $return,
                "vencidas" => $return_vencidas
            ));
    }
});

// Info
$app->post('/sys/info', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{
       $id_cliente = $app['request']->request->get('id_cliente');

       $clientes = new App\Model\Clientes($conexao);
       $clientes->setTable("clientes");
       $return = $clientes->getForId($id_cliente);
       $tipo_cliente = $return["tipo_cliente"];

       $licencas = new App\Model\Licenca($conexao);
       $licencas->setTable("licenca");
       $return_qtd_licencas = $licencas->getCountForIdCliente($id_cliente);

       return $app['twig']->render('alerta_info.twig', array(
                           'tipo' => $tipo_cliente,
                           'qtd_licencas' => $return_qtd_licencas
                       ));
    }
});

// Cliente editar
// /sys/cliente/editar/
$app->get('/sys/cliente/editar/{id}', function($id) use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        // Todos os planos e formas
        $planos = new App\Model\Planos($conexao);
        $planos->setTable("servicos_modelos");
        $return_planos_servicos = $planos->getAllPlano();

        $formaPagamento = new App\Model\PagamentoFormas($conexao);
        $formaPagamento->setTable("formas_pagamento");
        $return_forma_pagamento = $formaPagamento->getAll();

        // Dados Cliente
        $clientes = new App\Model\Clientes($conexao);
        $clientes-> setId($id);
        $return_cliente = $clientes->getForEdit();

        // Forma e Modelo
        $pagamento = new App\Model\Pagamento($conexao);
        $pagamento->setIdUser($id);
        $return_pag = $pagamento->getFormaAndModelo();

        return $app['twig']->render('clientesEditar.twig', array(
            "tipo_plano_servicos" => $return_planos_servicos,
            "forma_pagamento" => $return_forma_pagamento,
            "cliente" => $return_cliente,
            "forma_cod" => $return_pag[0]["codigo_forma"],
            "plano_cod" => $return_pag[0]["codigo_modelo"],
            "dia_vencimento" => $return_pag[0]["data_pagto"]
        ));

    }
});

// Editar cliente
$app->post('/sys/cliente/editar/', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

       $clienteEditar = new App\Model\ClienteEditar($conexao);

       $clienteEditar->setCodCliente($app['request']->request->get('codigo'));
       $clienteEditar->status($app['request']->request->get('status_cli'));
       $clienteEditar->tipoCliente($app['request']->request->get('tipo_cliente'));
       $clienteEditar->nome($app['request']->request->get('nome'));
       $clienteEditar->tipoPessoa($app['request']->request->get('tipo_pessoa'));
       $clienteEditar->rg($app['request']->request->get('rg'));
       $clienteEditar->cpf($app['request']->request->get('cpf'));
       $clienteEditar->dataNascimento($app['request']->request->get('data_nac'));
       $clienteEditar->cnpj($app['request']->request->get('cnpj'));
       $clienteEditar->razaoSocial($app['request']->request->get('razao_social'));
       $clienteEditar->telefone($app['request']->request->get('fone'));
       $clienteEditar->celular($app['request']->request->get('celular'));
       $clienteEditar->email($app['request']->request->get('email1'));
       $clienteEditar->emailSecundario($app['request']->request->get('email2'));
       $clienteEditar->senha($app['request']->request->get('senha'));
       $clienteEditar->rSenha($app['request']->request->get('r_senha'));
       $clienteEditar->obs($app['request']->request->get('obs'));
       $clienteEditar->tipoPlano($app['request']->request->get('tipo_plano'));
       $clienteEditar->formaPagamento($app['request']->request->get('forma_pagamento'));
       $clienteEditar->diaVencimento($app['request']->request->get('dia_vencimento'));

       $retorno_edicao = $clienteEditar->edita();

       if ($retorno_edicao["erro"] == "1") {
            return $app['twig']->render('alerta_erro.twig', array(
                                'errors', $return["error"],
                                'msg' => $return["msg"]
                            ));
        }
        else {
             return $app['twig']->render('alerta_sucesso_com_redirect.twig', array(
                'msg' => $retorno_edicao["msg"],
                'redirect' => "/sys/clientes"
            ));
        }


    }
});

// Visualizar novos clientes
// /sys/clientes/novos

$app->get('/sys/clientes/novos', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $clientes = new App\Model\Clientes($conexao);
        $clientes->setTable("clientes");
        $return = $clientes->getAllNovos();

        return $app['twig']->render('clientesNovos.twig', array(
                'clie' => $return,
            ));
    }
});

// Financeiro Actions
// /sys/financeiro/action
$app->post('/sys/financeiro/action', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{
        $faturas = new App\Model\Faturas($conexao);
        $faturas->setAction($app['request']->request->get('opcoes'));
        $faturas->setIdFatura($app['request']->request->get('id_fatura'));
        $retorno_action = $faturas->actions();

        if ($retorno_action["erro"] == "0" and $retorno_action["page"] == "visualizar") {
            return $app['twig']->render("redirect.twig", array(
                    'redirect' => "/sys/financeiro/visualizar/".$retorno_action["idFatura"]
                ));
        }
        if ($retorno_action["erro"] == "0" and $retorno_action["page"] == "editar") {
            return $app['twig']->render("redirect.twig", array(
                    'redirect' => "/sys/financeiro/editar/".$retorno_action["idFatura"]
                ));
        }
        if ($retorno_action["erro"] == "0") {
            return $app['twig']->render('alerta_sucesso_reload.twig', array(
                    'msg' => $retorno_action["msg"]
                ));
        }
        if ($retorno_action["erro"] == "1") {
            return $app['twig']->render('alerta_erro.twig', array(
                    'errors', $retorno_action["erro"],
                    'msg' => $retorno_action["msg"]
                ));
        }

    }
});

// Visualizar fatura
// /sys/financeiro/visualizar/
$app->get('/sys/financeiro/visualizar/{id}', function($id) use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

         $faturas = new App\Model\Faturas($conexao);
         $faturas->setIdFatura($id);
         $retorno_fatura = $faturas->getFaturaById();

         $finan = new App\Model\Cliente\Financeiro($conexao);
         $forma_pagamento = $finan->formaDePagamento($id,$retorno_fatura[0]["codigo_cli"]);

         return $app['twig']->render('financeiroVisualizar.twig', array(
                "fatura" => $retorno_fatura,
                "forma_pagamento" => $forma_pagamento
            ));
    }
});

// Editar fatura
// /sys/financeiro/editar/

$app->get('/sys/financeiro/editar/{id}', function($id) use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $faturas = new App\Model\Faturas($conexao);
        $faturas->setIdFatura($id);
        $retorno_fatura = $faturas->getFaturaById();

        return $app['twig']->render('financeiroEditar.twig', array(
                'fatura' => $retorno_fatura
            ));
    }
});

// Editar fatura action
$app->post('/sys/financeiro/editar/', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $faturas = new App\Model\Faturas($conexao);

        $faturas->setIdFatura($app['request']->request->get('id_fatura'));
        $faturas->setDataVencimento($app['request']->request->get('data_vencimento'));
        $faturas->setStatus($app['request']->request->get('status'));
        $faturas->setValor($app['request']->request->get('valor'));
        $faturas->setDescricao($app['request']->request->get('descricao'));

        $return_edita = $faturas->edita();

        if ($return_edita["erro"] == "0") {
            return $app['twig']->render('alerta_sucesso_com_redirect.twig', array(
                    'msg' => $return_edita["msg"],
                    'redirect' => "/sys/financeiro"
                ));
        }
        if ($return_edita["erro"] == "1") {
            return $app['twig']->render('alerta_erro.twig', array(
                    'errors', $return_edita["erro"],
                    'msg' => $return_edita["msg"]
                ));
        }
    }
});

// Editar Licenca/Sub-dominio
// /sys/licenca/editar
$app->get('/sys/licenca/editar/{id}', function($id) use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $clientes = new App\Model\Clientes($conexao);
        $clientes->setTable("clientes");
        $return_cliente = $clientes->getAll();


        $licenca = new App\Model\Licenca($conexao);
        $licenca->setIdLicenca($id);
        $return_licencas = $licenca->getById();

        return $app['twig']->render('licencaEditar.twig', array(
               //"clie" => $return_cliente,
                "licenca" => $return_licencas
            ));

    }
});

// Editar Licenca ACtion
// /sys/editar/licenca
$app->post('/sys/editar/licenca', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $licenca_editar = new App\Model\LicencaEditar($conexao);
        $licenca_editar->idLicenca($app['request']->request->get('idlicenca'));
        $licenca_editar->cliente($app['request']->request->get('clientef'));
        $licenca_editar->subDominio($app['request']->request->get('sub_dominio'));
        $licenca_editar->key($app['request']->request->get('key_sub'));
        $licenca_editar->status($app['request']->request->get('status_sub'));
        $return_edita = $licenca_editar->edita();

        if ($return_edita["erro"] == "0") {
            return $app['twig']->render('alerta_sucesso_com_redirect.twig', array(
                    'msg' => $return_edita["msg"],
                    'redirect' => "/sys/licencas"
                ));
        }
        if ($return_edita["erro"] == "1") {
            return $app['twig']->render('alerta_erro.twig', array(
                    'errors', $return_edita["erro"],
                    'msg' => $return_edita["msg"]
                ));
        }
    }
});


// Licenca Actions
// /sys/licenca/action
$app->post('/sys/licenca/action', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{
        $licenca = new App\Model\Licenca($conexao);
        $licenca->setAction($app['request']->request->get('opcoes'));
        $licenca->setIdLicenca($app['request']->request->get('id_licenca'));
        $retorno_action = $licenca->actions();

        // if ($retorno_action["erro"] == "0" and $retorno_action["page"] == "visualizar") {
        //     return $app['twig']->render("redirect.twig", array(
        //             'redirect' => "/sys/financeiro/visualizar/".$retorno_action["idFatura"]
        //         ));
        // }
        if ($retorno_action["erro"] == "0" and $retorno_action["page"] == "editar") {
            return $app['twig']->render("redirect.twig", array(
                    'redirect' => "/sys/licenca/editar/".$retorno_action["idLicenca"]
                ));
        }
        if ($retorno_action["erro"] == "0" and $retorno_action["page"] == ""){
            return $app['twig']->render('alerta_sucesso_reload.twig', array(
                    'msg' => $retorno_action["msg"]
                ));
        }
        if ($retorno_action["erro"] == "1") {
            return $app['twig']->render('alerta_erro.twig', array(
                    'errors', $retorno_action["erro"],
                    'msg' => $retorno_action["msg"]
                ));
        }

    }
});

// Servicos Adicional
// /sys/servico
$app->get('/sys/servico', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $planos = new App\Model\Planos($conexao);
        $retorno_action = $planos->getAll();

        return $app['twig']->render('visualizarServicos.twig',array(
                "servicos" => $retorno_action
            ));
    }
});


// Faturas Abertas
// /sys/faturas/abertas
$app->get('/sys/faturas/abertas', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $faturas = new App\Model\Faturas($conexao);
        $retorno_action = $faturas->getAllFaturasOpen();

        return $app['twig']->render('visualizarFaturasAbertas.twig',array(
                "faturas_abertas" => $retorno_action
            ));
    }
});


// /sys/faturas/abertas
$app->get('/sys/faturas/fechadas', function() use ($app, $conexao){
    if (null === $app['session']->get('idUser')) {
        return $app->redirect('/sys/login');
    }
    else{

        $faturas = new App\Model\Faturas($conexao);
        $retorno_action = $faturas->getAllFaturasClose();

        return $app['twig']->render('visualizarFaturasFechadas.twig',array(
                "faturas_fechadas" => $retorno_action
            ));
    }
});


// logout
$app->get('/sys/logout', function() use ($app){
    $app['session']->set('user', false);
	$app['session']->set('idUser', false);
	$app['session']->clear();
	return $app->redirect('/sys/login');
});
