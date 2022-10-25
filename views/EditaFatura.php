<?php

session_start();

require_once('../inc/config.php');
require_once('../funcoes.php');


$core = new IsistemCore();
$core->Connect();


if (isset($_POST['id_fatura'])) {

    $erro = 0;
    $msg = "Fatura alterada com sucesso!";

    $idFatura = $_POST['id_fatura'];
    $dataVencimento = $_POST['data_vencimento'];
    $status = $_POST['status'];
    $valor = $_POST['valor'];
    $descricao = $_POST['descricao'];


    if ($idFatura != "") {
        try {

            $dataVencimento = convertDataBD($dataVencimento);

            // echo 'TREM BALA DA COLINA';
            // exit();

            $query = $core->Prepare("UPDATE faturas SET status = '" . $status . "', data_vencimento = '" . $dataVencimento . "'
                , valor = '" . $valor . "', descricao = '" . $descricao . "' WHERE codigo = '" . $idFatura . "'");
            $result = $query->Execute();
        } catch (Exception $e) {
            $erro = 1;
            $msg = "Não foi possivel inserir dados de faturas - $e";
            echo "error||". $msg;
            return "error||". $msg;
            exit();
        }
    }

    echo "success||". $msg;
    return "success||". $msg;
    exit();
} else {
    echo '<h1>Você não tem permissão para acessar esta página.</h1>';
}
