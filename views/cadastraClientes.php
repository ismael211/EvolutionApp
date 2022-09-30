<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

$core = new IsistemCore();
$core->Connect();


if ($_POST['nome'] == ''){
    $resposta = 'error||Por favor digite um nome';

}else if($_POST['email'] == ''){
    $resposta = 'error||Por favor digite um email';

}else if($_POST['emailSecundario'] == ''){
    $resposta = 'error||Por favor digite um email secundário';

}else if($_POST['celular'] == ''){
    $resposta = 'error||Por favor digite um número de celular';

}

if($_POST['tipo_pessoa'] == 'fisica'){

    if($_POST['cpf'] == ''){
        $resposta = 'error||Por favor digite seu CPF';
    
    }else if($_POST['rg'] == ''){
        $resposta = 'error||Por favor digite seu RG';
    
    }else if($_POST['data_nac'] == ''){
        $resposta = 'error||Por favor digite sua data de nascimento';
    
    }

}else{
    if($_POST['razao_social'] == ''){
        $resposta = 'error||Por favor digite a razão social';
    
    }else if($_POST['cnpj'] == ''){
        $resposta = 'error||Por favor digite o CNPJ';
    
    }

}

if($_POST['status_cli'] == ''){
    $resposta = 'error||Por favor selecione um status';

}else if($_POST['senha'] == ''){
    $resposta = 'error||Por favor digite uma senha';

}else if($_POST['tipo_cliente'] == ''){
    $resposta = 'error||Por favor selecione um Tipo de Cliente';

}else if($_POST['forma_pagamento'] == ''){
    $resposta = 'error||Por favor selecione uma Forma de Pagamento';

}else if($_POST['dia_vencimento'] == ''){
    $resposta = 'error||Por favor digite o dia do vencimento';

}else if($_POST['tipo_plano'] == ''){
    $resposta = 'error||Por favor selecione o Tipo de Plano';

}else{
    
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $email_sec = $_POST['emailSecundario'];
    $telefone = $_POST['telefone'];
    $celular = $_POST['celular'];
    $tipoPessoa = $_POST['tipoPessoa'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $razaoSocial = $_POST['razao_social'];
    $cnpj = $_POST['cnpj'];
    $status = $_POST['status_cli'];
    $senha = $_POST['senha'];
    $dataNascimento = $_POST['data_nac'];
    $tipoCliente = $_POST['tipo_cliente'];
    $obs = $_POST['obs'];
    $parceiro = $_POST['parceiro'];
    
    try {
    
        $data_cadastro = date("Y-m-d");
    
        if ($dataNascimento != "") {
            $ex = explode("/", $dataNascimento);
            $dataNascimento = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
        } else {
            $dataNascimento = date("Y-m-d");
        }
    
        $senha = password_hash(
            $senha,
            PASSWORD_DEFAULT,
            ['cost' => 12]
        );
    
        $query = $this->conn->query("INSERT INTO clientes(nome,email1,email2,fone,celular,tipo_pessoa,cpf,rg,razao_social,
        cnpj,data_cadastro,status,senha,data_nac,tipo_cliente,obs,parceiro)
        VALUES ('" . $nome . "','" . $email . "','" . $email_sec . "','" . $telefone . "','" . $celular . "',
        '" . $tipoPessoa . "','" . $cpf . "','" . $rg . "','" . $razaoSocial . "','" . $cnpj . "','$data_cadastro',
        '" . $status . "','" . $senha . "','" . $dataNascimento . "','" . $tipoCliente . "', '" . $obs . "',
        '" . $parceiro . "')");
    
        // Salva Serviço (Plano)
        $id_cliente = $this->conn->insert_id;
    } catch (Exception $e) {
        $erro = 1;
        $msg .= "Não foi possivel registrar. #$e \n";
    }
    
    return $id_cliente;

}
