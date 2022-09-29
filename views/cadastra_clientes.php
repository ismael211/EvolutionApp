<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

$core = new IsistemCore();
$core->Connect();

$nome = $_POST['nome'];
$email = $_POST['email'];
$email_sec = $_POST['emailSecundario'];
$telefone = $_POST['telefone'];
$celular = $_POST['celular'];

$tipoPessoa = $_POST['tipoPessoa'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];


try {

    $data_cadastro = date("Y-m-d");

    if ($this->dataNascimento != "") {
        $ex = explode("/", $this->dataNascimento);
        $dataNascimento = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
    } else {
        $dataNascimento = date("Y-m-d");
    }

    $senha = password_hash(
        $this->senha,
        PASSWORD_DEFAULT,
        ['cost' => 12]
    );

    $query = $this->conn->query("INSERT INTO clientes(nome,email1,email2,fone,celular,tipo_pessoa,cpf,rg,razao_social,
    cnpj,data_cadastro,status,senha,data_nac,tipo_cliente,obs,parceiro)
    VALUES ('" . $this->nome . "','" . $this->email . "','" . $this->emailSecundario . "','" . $this->telefone . "','" . $this->celular . "',
    '" . $this->tipoPessoa . "','" . $this->cpf . "','" . $this->rg . "','" . $this->razaoSocial . "','" . $this->cnpj . "','$data_cadastro',
    '" . $this->status . "','" . $senha . "','" . $dataNascimento . "','" . $this->tipoCliente . "', '" . $this->obs . "',
    '" . $this->parceiro . "')");

    // Salva Serviço (Plano)
    $id_cliente = $this->conn->insert_id;
} catch (Exception $e) {
    $erro = 1;
    $msg .= "Não foi possivel registrar. #$e \n";
}

return $id_cliente;
