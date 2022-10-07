<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');


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

    require_once('../funcoes.php');


    $cliente = $_POST['clientef'];
    $subdominio = $_POST['sub_dominio'];
    $key = $_POST['key_sub'];
    $status = $_POST['status_sub'];
    $setup = $_POST['setup'];

    $sql = $core->RowCount("SELECT parceiro FROM clientes WHERE codigo = '" . $cliente . "' AND parceiro = '1'");

    try {

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

        $query = $core->Prepare("INSERT INTO licenca(sub_dominio, status, key_licenca, id_cliente, id_fatura, data_cadastro)
				VALUES('" . $subdominio . "', '" . $status . "','" . strtoupper($key) . "','" . $cliente . "','" . $id_fatura . "', NOW())");

        $result = $query->Execute();

        // Caso selecionado o Setup
        if ($setup != "") {

            $data_vencimento = dataAumentaDia(1);

            $return_modelo = $core->Fetch("SELECT * FROM `servicos_modelos` WHERE `codigo` = '4'");

            $return_pagamento = $core->Fetch("SELECT servicos_modelos.codigo as codigo_modelo, servicos_modelos.nome as nome_modelo,
								formas_pagamento.codigo as codigo_forma, formas_pagamento.nome as formas_nome,
								servicos_adicionais.data_pagto, servicos_adicionais.codigo_servico as codigo_servico,
								servicos_adicionais.valor as valor, servicos_adicionais.descricao as descricao
								FROM servicos_adicionais
								LEFT JOIN servicos_modelos ON servicos_adicionais.codigo_servico = servicos_modelos.codigo
								LEFT JOIN formas_pagamento ON servicos_adicionais.codigo_forma_pagto = formas_pagamento.codigo
								WHERE servicos_adicionais.codigo_cliente = '" . $cliente . "';");


            // Cadastro servivo adicional/Plano
            $codigoPlano = $return_modelo["codigo"];
            $codigoCliente = $cliente;
            $codigoFormaPagamento = $return_pagamento["codigo_forma"];
            $dataPagamento = $data_vencimento;
            $valorPlano = $return_modelo["valor"];
            $repetir = "nao";
            $periodo = "1";
            $totalParcelas = "0";
            $descricao = $return_modelo["descricao"];

            $plano_setup = save($codigoPlano, $codigoCliente, $codigoFormaPagamento, $dataPagamento, $valorPlano, $repetir, $periodo, $totalParcelas, $descricao);

            $ultimo_gravado = $core->Fetch("SELECT Max(codigo) FROM servicos_modelos LIMIT 1");

            $ultimo_gravado = $ultimo_gravado['Max(codigo)'];

            // Setup
            $CodigoCliente = $cliente;
            $CodigoServico = $ultimo_gravado;
            $DataVencimento = $data_vencimento;
            $Valor = $return_modelo["valor"];
            $Descricao = $return_modelo["descricao"];

            // Cadastro da fatura
            $fatura_setup = cadastra($CodigoCliente, $CodigoServico, $DataVencimento, $Valor, $Descricao);
        }
    } catch (Exception $e) {
        $resposta = "error||Não foi possivel registrar. #$e";
        echo $resposta;
        exit();
    }

    $resposta = "success||Cadastro realizado com sucesso";
    echo $resposta;
    exit();
}
