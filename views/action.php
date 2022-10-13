<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

$core = new IsistemCore();
$core->Connect();

if ($_POST['tipo'] == 'ativar') {

    $array_cod = array();
    foreach ($_POST['codigo'] as $codigo) {
        array_push($array_cod, $codigo);
    }

    $array_errors = array();
    foreach ($array_cod as $value) {

        $erro = "0";
        $msg_erro = "";
        $id = "0";
        $novoStatus = "0";

        if ($value != "") {

            if(isset($_POST['ativa'])){
                $novoStatus = "a";
                $desativa = "1";
            }else{
                $novoStatus = "p";
                $desativa = "0";
            }

            try {

                $query = $core->Prepare("UPDATE `clientes` SET `status` = '" . $novoStatus . "' WHERE codigo = '" . $value . "' ");
                $resposta = $query->Execute();

                $query = $core->Prepare("UPDATE `licenca` SET `status` = '$desativa' WHERE id_cliente = '" . $value . "'");
                $resposta = $query->Execute();

                $query = $core->Prepare("UPDATE `servicos_adicionais` SET `status` = '$desativa' WHERE `codigo_cliente` = '" . $value . "'");
                $resposta = $query->Execute();
            } catch (Exception $e) {
                $erro = "1";
                $msg_erro = "Ocorreu um erro: $e \n";
                $return = array("erro" => $erro, "msg_erro" => "$msg_erro", "id_valor" => "$value", "novo_status" => "$novoStatus");
                return $return;
            }
        }
    }
    $return = array("erro" => $erro, "msg_erro" => "$msg_erro", "id_valor" => "$value", "novo_status" => "$novoStatus");
    echo print_r($return);
    return $return;

} else if ($_POST['tipo'] == 'remover') {

    $erro = "0";
    $msg_erro = "";

    if ($this->id != "") {
        try {

            if (is_array($this->id)) {
                foreach ($this->id as $value) {

                    // Remove servico
                    // $planos = new Planos($this->conn);
                    $planos->codigoCliente($value);
                    $planos->removeByCliente();

                    // Remove Faturas
                    // $faturas = new Faturas($this->conn);
                    $faturas->idCliente($value);
                    $faturas->removeFaturaByCliente();

                    // Remove licenca
                    // $licenca = new Licenca($this->conn);
                    $licenca->idCliente($value);
                    $licenca->removeByCliente();

                    $this->conn->query("DELETE FROM clientes WHERE codigo = '" . $value . "'");

                    $msg_erro = "Excluído com sucesso!";
                }
            } else {
                $erro = "1";
                $msg_erro .= "Erro: $this->id \n";
            }
        } catch (Exception $e) {

            $erro = "1";
            $msg_erro = "Ocorreu um erro: $e";
        }
    } else {
        $erro = "1";
        $msg_erro = "Não foi possível Remover!";
    }

    $return = array("erro" => "$erro", "msg_erro" => "$msg_erro");
    return $return;
} else {
    echo "<h1>Você não tem permissão para acessar esta página!</h1>";
}
