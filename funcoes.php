<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('inc/config.php');

$core = new IsistemCore();
$core->Connect();

$quantidade = $core->RowCount("SELECT * FROM licenca");

// Metodo que gera Key
function generateKey()
{

    $a = strtoupper(substr(md5(rand(11111, 99999)), 5, 5));
    $b = strtoupper(substr(md5(rand(11111, 99999)), 5, 5));
    $c = strtoupper(substr(md5(rand(11111, 99999)), 5, 5));
    $d = strtoupper(substr(md5(rand(11111, 99999)), 5, 5));
    $e = strtoupper(substr(md5(rand(11111, 99999)), 5, 5));

    return "$a-$b-$c-$d-$e";
}

function novaFatura($id)
{


    $core = new IsistemCore();
    $core->Connect();

    $erro = "0";
    $msg = "";
    $retorno_cadastra = "";


    $cliente = $core->Fetch("SELECT tipo_cliente FROM clientes WHERE codigo = '$id'");

    $tipo_cliente = $cliente['tipo_cliente'];


    // Quantidade de licenca do cliente
    $quantidade = $core->RowCount("SELECT id FROM licenca WHERE id_cliente = '$id'");


    if ($tipo_cliente == "r") {

        $linha = $core->Fetch("SELECT codigo, valor, codigo_servico FROM faturas
            WHERE codigo_cliente = '" . $id . "' ORDER BY codigo DESC LIMIT 1");

        $return_faturas = array("erro" => 0);

        if ($quantidade > 10) {

            $qtd = $core->RowCount("SELECT * FROM servicos_adicionais LEFT JOIN servicos_modelos
                    ON servicos_adicionais.codigo_servico = servicos_modelos.codigo
                    LEFT JOIN formas_pagamento ON servicos_adicionais.codigo_forma_pagto = formas_pagamento.codigo
                    WHERE servicos_adicionais.codigo_cliente = '" . $id . "';");

            if ($qtd > 0) {

                $sql = $core->Fetch("SELECT servicos_modelos.codigo as codigo_modelo, servicos_modelos.nome as nome_modelo,
                    formas_pagamento.codigo as codigo_forma, formas_pagamento.nome as formas_nome,
                    servicos_adicionais.data_pagto, servicos_adicionais.codigo_servico as codigo_servico,
                    servicos_adicionais.valor as valor, servicos_adicionais.descricao as descricao
                    FROM servicos_adicionais
                    LEFT JOIN servicos_modelos ON servicos_adicionais.codigo_servico = servicos_modelos.codigo
                    LEFT JOIN formas_pagamento ON servicos_adicionais.codigo_forma_pagto = formas_pagamento.codigo
                    WHERE servicos_adicionais.codigo_cliente = '" . $id . "';");

                $retorno_pagamento = $sql;
            }

            $novo_valor = calculoLicencas($quantidade, $linha["valor"], $retorno_pagamento["valor"]);

            try {
                $query = $core->Prepare("UPDATE faturas SET valor = '" . $novo_valor . "' WHERE codigo = '" . $linha["codigo"] . "' LIMIT 1");
                $result = $query->Execute();
            } catch (Exception $e) {
                $erro = 1;
                $msg = "Não foi possivel atualizar os dados da fatura - $e";
            }
            $return_faturas = array("erro" => $erro, "msg" => $msg);

        } else if ($linha["codigo_servico"] != "4" and $quantidade == "1") {

            // Cadastra Fatura
            $return_pagamento = $core->Fetch("SELECT servicos_modelos.codigo as codigo_modelo, servicos_modelos.nome as nome_modelo,
            formas_pagamento.codigo as codigo_forma, formas_pagamento.nome as formas_nome,
            servicos_adicionais.data_pagto, servicos_adicionais.codigo_servico as codigo_servico,
            servicos_adicionais.valor as valor, servicos_adicionais.descricao as descricao,
            servicos_adicionais.codigo as codigo
            FROM servicos_adicionais
            LEFT JOIN servicos_modelos ON servicos_adicionais.codigo_servico = servicos_modelos.codigo
            LEFT JOIN formas_pagamento ON servicos_adicionais.codigo_forma_pagto = formas_pagamento.codigo
            WHERE servicos_adicionais.codigo_cliente = '" . $id . "' AND servicos_modelos.codigo <> '4'
            ORDER BY servicos_adicionais.codigo DESC LIMIT 1");

            // Setup
            $data_vencimento = dataAumentaDia(1);

            $retorno_cadastra = cadastra($id, $return_pagamento["codigo"], $data_vencimento, $return_pagamento["valor"], $return_pagamento["descricao"]);
        }

        if ($return_faturas['erro'] == 1) {
            $retorno_cadastra = $return_faturas;
        }
    } else if ($tipo_cliente == "u") { // Caso o Cliente seja USUARIO (Uma Licenca e fatura por cadastro)


        // Busca Dados
        $return_pagamento = $core->Fetch("SELECT servicos_modelos.codigo as codigo_modelo, servicos_modelos.nome as nome_modelo,
        formas_pagamento.codigo as codigo_forma, formas_pagamento.nome as formas_nome,
        servicos_adicionais.data_pagto, servicos_adicionais.codigo_servico as codigo_servico,
        servicos_adicionais.valor as valor, servicos_adicionais.descricao as descricao,
        servicos_adicionais.codigo as codigo
        FROM servicos_adicionais
        LEFT JOIN servicos_modelos ON servicos_adicionais.codigo_servico = servicos_modelos.codigo
        LEFT JOIN formas_pagamento ON servicos_adicionais.codigo_forma_pagto = formas_pagamento.codigo
        WHERE servicos_adicionais.codigo_cliente = '" . $id . "' AND servicos_modelos.codigo <> '4'
        ORDER BY servicos_adicionais.codigo DESC LIMIT 1");


        // Data de amanhã
        $data_vencimento = dataAumentaDia(1);

        if ($quantidade > 1) {

            // Cadastra Servico Adicional
            $repetir = "sim";
            $periodo = "1";
            $totalParcelas = "0";
            $descricao = $return_pagamento["descricao"];

            //Salva em serviços adicionais
            $plano = save(
                $return_pagamento["codigo_servico"],
                $id,
                $return_pagamento["codigo_forma"],
                $data_vencimento,
                $return_pagamento["valor"],
                $repetir,
                $periodo,
                $totalParcelas,
                $descricao
            );

            if ($plano['erro'] == 0) {
                $retorno_cadastra = cadastra($id, $return_pagamento["codigo"], $data_vencimento, $return_pagamento["valor"], $return_pagamento["descricao"]);
            } else {
                $retorno_cadastra = $plano;
            }
        }
        // Cadastra nova fatura
    }

    if ($retorno_cadastra['erro'] == 0) {
        $erro = "0";
        $msg = "";
    } else {
        $erro = $retorno_cadastra['erro'];
        $msg = $retorno_cadastra['msg'];
    }


    // return $plano;
    return array("erro" => $erro, "msg" => $msg, "retorno_cadastra" => $retorno_cadastra["ultimoId"]);
}

function calculoLicencas($quantidade, $valor, $base)
{
    switch ($quantidade) {
        case '11':
            return $valor + $base;
            break;
        case '21':
            return $valor + $base;
            break;
        case '31':
            return $valor + $base;
            break;
        case '41':
            return $valor + $base;
            break;
        case '51':
            return $valor + $base;
            break;
        case '61':
            return $valor + $base;
            break;
        case '71':
            return $valor + $base;
            break;
        case '81':
            return $valor + $base;
            break;
        case '91':
            return $valor + $base;
            break;
        case '101':
            return $valor + $base;
            break;
        default:
            return $valor;
            break;
    }
}

function dataAumentaDia($dias)
{
    $datastamp = '86400' * $dias + mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    return date("d/m/Y", $datastamp);
}


function cadastra($codigoCliente, $codigoServico, $dataVencimento, $valor, $descricao)
{
    $core = new IsistemCore();
    $core->Connect();

    $erro = 0;
    $msg = "";

    try {
        $query = $core->Prepare("INSERT INTO faturas (codigo_cliente,codigo_servico,data_vencimento,valor,descricao,tipo)
                VALUES ('" . $codigoCliente . "','" . $codigoServico . "','" . convertDataBD($dataVencimento) . "',
                '" . moneyFormatBD($valor) . "','" . $descricao . "','s')");

        $result = $query->Execute();

        $ultimo_gravado = $core->Fetch("SELECT Max(codigo) FROM faturas LIMIT 1");

        $ultimo_gravado = $ultimo_gravado['Max(codigo)'];
    } catch (Exception $e) {
        $erro = 1;
        $msg = "Não foi possivel inserir dados de faturas - $e";
    }

    return array("erro" => $erro, "msg" => $msg, "ultimoId" => $ultimo_gravado);
}

function convertDataBD($data)
{
    list($dia, $mes, $ano) = explode("/", $data);
    return $ano . "-" . $mes . "-" . $dia;
}

function moneyFormatBD($valor)
{
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", ".", $valor);
    return $valor;
}


function save($codigoPlano, $codigoCliente, $dataPagamento, $codigoFormaPagamento, $valorPlano, $repetir, $periodo, $totalParcelas, $descricao)
{
    $core = new IsistemCore();
    $core->Connect();

    $erro = 0;
    $msg = "";

    try {
        $query = $core->Prepare("INSERT INTO servicos_adicionais(codigo_servico,codigo_cliente,data_pagto,codigo_forma_pagto,valor,
				repetir,periodo,total_parcelas,descricao)
				VALUES ('" . $codigoPlano . "', '" . $codigoCliente . "', '" . $dataPagamento . "',
				'" . $codigoFormaPagamento . "', '" . $valorPlano . "', '" . $repetir . "', '" . $periodo . "',
                '" . $totalParcelas . "', '" . $descricao . "')");

        $result = $query->Execute();
    } catch (Exception $e) {
        $erro = 1;
        $msg = "Não foi possivel inserir dados de serviços adicionais - $e";
    }

    return array("erro" => $erro, "msg" => $msg);
}


?>

