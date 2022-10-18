<?php


header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

$core = new IsistemCore();
$core->Connect();


if ($this->codigoCliente != "") {

    if ($this->senha != "" and $this->rSenha != "") {
        if ($this->rSenha == $this->senha) {

            $senha = password_hash(
                $this->senha,
                PASSWORD_DEFAULT,
                ['cost' => 12]
            );
            $clasula_mod_senha = ",senha='" . $senha . "'";
            $msg = "";
        } else {
            $clasula_mod_senha = "";
            $erro = 1;
            $msg = "Senhas não conferem!";
        }
    } else {
        $clasula_mod_senha = "";
    }

    if ($this->conn->query("UPDATE clientes SET nome='" . $this->nome . "',email1='" . $this->email . "',
				email2='" . $this->emailSecundario . "',
			fone='" . $this->telefone . "',celular='" . $this->celular . "',tipo_pessoa='" . $this->tipoPessoa . "', cpf='" . $this->cpf . "',
			rg='" . $this->rg . "',razao_social='" . $this->razaoSocial . "',cnpj='" . $this->cnpj . "',obs='" . $this->obs . "',status='" . $this->status . "',
			data_nac='" . $this->convertDataBD($this->dataNascimento) . "',tipo_cliente='" . $this->tipoCliente . "'$clasula_mod_senha
			WHERE codigo = '" . $this->codigoCliente . "'")) {

        if ($this->conn->query("UPDATE servicos_adicionais SET codigo_servico='" . $this->tipoPlano . "',codigo_forma_pagto='" . $this->formaPagamento . "',data_pagto='" . $this->diaVencimento . "' WHERE codigo_cliente = '" . $this->codigoCliente . "'")) {
            $msg = "Cliente Editado com sucesso!";
            $erro = "0";
        } else {
            $msg = "Não foi possivel salvar edição";
            $erro = "1";
        }
    }
} else {
    $erro = "1";
    $msg = "Nenhum cliente para  salvar edição";
}

$return = array("erro" => "$erro", "msg" => "$msg");
return $return;
