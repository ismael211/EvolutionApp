<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');
// require_once('../src/App/Model/Faturas.php');
require_once('../funcoes.php');


$core = new IsistemCore();
$core->Connect();


if ($_POST['clientef'] == '') {
    $resposta = 'error||Por favor selecione um cliente';
    echo $resposta;
    exit();
} else if ($_POST['sub_dominio'] == '') {
    $resposta = 'error||Por favor digite um Sub-Dominio';
    echo $resposta;
    exit();
} else if ($_POST['key_sub'] == '') {
    $resposta = 'error||Por favor não apague a chave Key, recarregue a pagina';
    echo $resposta;
    exit();
} else {

    $cliente = $_POST['clientef'];
    $subdominio = $_POST['sub_dominio'];
    $key = $_POST['key_sub'];
    $status = $_POST['status_sub'];
    $setup = $_POST['setup'];



    $sql = $core->RowCount("SELECT parceiro FROM clientes WHERE codigo = '" . $cliente . "' AND parceiro = '1'");

    if ($sql == 0) {

        $returnFatura = novaFatura($cliente);

        $erro = $returnFatura["erro"];
        $msg = $returnFatura["msg"];
        $id_fatura = $returnFatura["retorno_cadastra"];

    } else {
        $erro = '1';
        $msg = 'Não entrou';
        $id_fatura = 0;
    }

    // echo 'ERRO = '.$erro .'       ||      MSG = '.$msg.'       ||      ID = '.$id_fatura;

    echo  'Retorno Fatura: '.$returnFatura;

    $query = $core->Prepare("INSERT INTO licenca(sub_dominio, status, key_licenca, id_cliente, id_fatura, data_cadastro)
				VALUES('" . $subdominio . "', '" . $status . "','" . strtoupper($key) . "','" . $cliente . "','" . $id_fatura . "', NOW())");

    $result = $query->Execute();


    // Caso selecionado o Setup
    if ($setup != "") {

        $data_vencimento = $this->dataAumentaDia("1");

        // $modelo_setup = new ModeloPlanos($this->conn);
        $modelo_setup->setCodigo('4');
        $return_modelo = $modelo_setup->getModelos();

        // $pagamento_setup = new Pagamento($this->conn);
        $pagamento_setup->setIdUser($this->cliente);
        $return_pagamento = $pagamento_setup->getFormaAndModelo();

        // Cadastro servivo adicional/Plano
        // $plano_setup = new Planos($this->conn);
        $plano_setup->codigoPlano($return_modelo["codigo"]);
        $plano_setup->codigoCliente($this->cliente);
        $plano_setup->codigoFormaPagamento($return_pagamento[0]["codigo_forma"]);
        $plano_setup->dataPagamento($data_vencimento);
        $plano_setup->valorPlano($return_modelo["valor"]);
        $plano_setup->repetir("nao");
        $plano_setup->periodo("1");
        $plano_setup->totalParcelas("0");
        $plano_setup->descricao($return_modelo["descricao"]);
        $plano_setup->save();

        // Setup
        // $fatura_setup = new Faturas($this->conn);
        $fatura_setup->setCodigoCliente($this->cliente);
        $fatura_setup->setCodigoServico($plano_setup->ultimoId());
        $fatura_setup->setDataVencimento($data_vencimento);
        $fatura_setup->setValor($return_modelo["valor"]);
        $fatura_setup->setDescricao($return_modelo["descricao"]);
        $fatura_setup->cadastra();
    }

    return array("error" => "1", "msg" => "Não foi possivel registrar. #$e");

    return array("error" => $erro, "msg" => $msg);














    $data_cadastro = date("Y-m-d");



    $senha = password_hash(
        $senha,
        PASSWORD_DEFAULT,
        ['cost' => 12]
    );

    $query = $core->Prepare("INSERT INTO clientes(nome,email1,email2,fone,celular,tipo_pessoa,cpf,rg,razao_social,
        cnpj,data_cadastro,status,senha,data_nac,tipo_cliente,obs,parceiro)
        VALUES ('" . $nome . "','" . $email . "','" . $email_sec . "','" . $telefone . "','" . $celular . "',
        '" . $tipoPessoa . "','" . $cpf . "','" . $rg . "','" . $razaoSocial . "','" . $cnpj . "','$data_cadastro',
        '" . $status . "','" . $senha . "','" . $dataNascimento . "','" . $tipoCliente . "', '" . $obs . "',
        '" . $parceiro . "')");

    $result = $query->Execute();

    if (!$result) {
        $resposta = 'error||Cadastro não efetuado.';
    } else {
        $resposta = 'success||Cadastro efetuado com sucesso';
    }
}

echo $resposta;
exit();
