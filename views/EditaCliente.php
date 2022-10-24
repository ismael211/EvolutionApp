<?php


header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

$core = new IsistemCore();
$core->Connect();



if ($_POST['CodCliente'] != "") {

    if ($_POST['senha'] != "") {

        $senha = $_POST['senha'];

        $senha = password_hash($senha, PASSWORD_DEFAULT, ['cost' => 12]);

        $clasula_mod_senha = ",senha='" . $senha . "'";

    } else {
        $clasula_mod_senha = "";
    }

    try {

        $codigo = $_POST['CodCliente'];
        $tipo_cliente = $_POST['tipo_cliente'];
        $nome = $_POST['nome'];
        $tipo_pessoa = $_POST['tipo_pessoa'];
        $rg = $_POST['rg'];
        $cpf = $_POST['cpf'];
        $data_nasc = $_POST['data_nac'];
        $cnpj = $_POST['cnpj'];
        $razao_social = $_POST['razao_social'];
        $fone = $_POST['fone'];
        $celular = $_POST['celular'];
        $email1 = $_POST['email1'];
        $email2 = $_POST['email2'];
        $obs = $_POST['obs'];
        $status_cli = $_POST['status_cli'];
        $tipo_plano = $_POST['tipo_plano'];
        $forma_pagamento = $_POST['forma_pagamento'];
        $dia_vencimento = $_POST["dia_vencimento"];
        $parceiro = $_POST['parceiro'];


        if ($data_nasc != "") {
            $ex = explode("/", $data_nasc);
            $data_nasc = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
        } else {
            $data_nasc = date("Y-m-d");
        }


        $query = $core->Prepare("UPDATE clientes SET nome='" . $nome . "',email1='" . $email1 . "',
            email2='" . $email2 . "', fone='" . $fone . "',celular='" . $celular . "',tipo_pessoa='" . $tipo_pessoa . "', cpf='" . $cpf . "',
            rg='" . $rg . "',razao_social='" . $razao_social . "',cnpj='" . $cnpj . "',obs='" . $obs . "',status='" . $status_cli . "',
            data_nac='" .$data_nasc. "',tipo_cliente='" . $tipo_cliente . "'$clasula_mod_senha WHERE codigo = '" . $codigo . "'");
        $result = $query->execute();


        $query = $core->Prepare("UPDATE servicos_adicionais SET codigo_servico='" . $tipo_plano . "',codigo_forma_pagto='" . $forma_pagamento . "',data_pagto='" . $dia_vencimento . "' WHERE codigo_cliente = '" . $codigo . "'");
        $result = $query->execute();

    } catch (Exception $e) {
        $resposta = "error||Não foi possivel editar cliente. #$e";
        echo $resposta;
        exit();
    }


    $resposta = "success||Cliente editado com sucesso";
    echo $resposta;
    exit();

} else {
    
    $resposta = "error||Nenhum cliente para salvar edição";
    echo $resposta;
    exit();

}
