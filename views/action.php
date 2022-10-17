<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');
require_once('../funcoes.php');



$core = new IsistemCore();
$core->Connect();

if ($_POST['pagina'] == 'licenca') {

    $erro = "0";
    $msg = "";
    $idLicenca = "";
    $page = "";

    if ($_POST['tipo'] == "ativar") {

        $array_cod = array();
        foreach ($_POST['codigo'] as $codigo) {
            array_push($array_cod, $codigo);
        }

        $array_errors = array();
        foreach ($array_cod as $value) {

            $erro = "0";
            $msg_erro = "";
            $id = "0";
            // $novoStatus = "0";

            if ($value != "") {

                if (isset($_POST['ativa'])) {
                    // $novoStatus = "a";
                    $desativa = "1";
                } else {
                    // $novoStatus = "p";
                    $desativa = "0";
                }

                try {

                    $query = $core->Prepare("UPDATE licenca SET status = '" . $desativa . "' WHERE id = '" .$value. "'");
                    $resposta = $query->Execute();

                    $erro = "0";

                    $fjlhdgaks = 'ccccccccccccccccc';

                    if ($erro == "0" AND $desativa == "1") {

                        $fjlhdgaks = 'bbbbbbbbbbbbbbbbbbb';

                        // $licencaEnvios->boasVindas();

                        $dados_licenca = $core->Fetch("SELECT * FROM `licenca` WHERE `id` = '".$value."'");

                        $cod_cliente = $dados_licenca['id_cliente'];

                        $returnCliente = $core->Fetch("SELECT * FROM `clientes` WHERE `codigo` = '".$cod_cliente."'");
   
                        $dados_sistema = $core->Fetch("SELECT * FROM sistema");
                        
                        // $envio = new Envio();
                        // $envio->setConfigSmtp($dados_sistema["servidor_smtp"], $dados_sistema["servidor_smtp_porta"],
                        // $dados_sistema["servidor_smtp_usuario"], $dados_sistema["servidor_smtp_senha"]);

                        $subject = "[Isistem] - Bem Vindo ";

                        $dados_textos = $core->Fetch("SELECT texto FROM textos WHERE codigo = '34'");
                        
                        $mensagem_editada = str_replace ( '[cliente_nome]' , $returnCliente["nome"] , $dados_textos["texto"] );
                        $mensagem_editada = str_replace ( '[key]' , $dados_licenca["key_licenca"] , $mensagem_editada );
                        $mensagem_editada = str_replace ( '[linksistema]' , $dados_licenca["sub_dominio"] , $mensagem_editada );
                        $mensagem_editada = str_replace ( '[cliente_email_principal]' , $returnCliente["email1"] , $mensagem_editada );
   
                        $envio = envia_Email($returnCliente["nome"], $returnCliente["email1"], $returnCliente["email2"],
                        $subject, $mensagem_editada);
  
                        $fjlhdgaks = 'AAAAAAAAAAAAAAA';

                        if($envio == "ok"){
                            $erro = '0';
                            $msg_erro = $envio;
                        }else{
                            $erro = '1';
                            $msg_erro = $envio;
                        }
                    }
                } catch (Exception $e) {
                    $erro = "1";
                    $msg_erro = "Ocorreu um erro: $e \n";
                    $return = "error||" . $envio . "||msg_erro||" . $msg_erro . "||id_valor||" . $value . "||novo_status||" . $novoStatus;
                    return $return;
                }
            }
        }
        $return = "success||" . $envio . "||msg_erro||" . $fjlhdgaks . "||id_valor||" . $value . "||novo_status||" . $novoStatus;
        echo $return;
        return $return;
    } else if ($_POST['tipo'] == "remover") {

        $erro = "0";
        $msg = "";

        try {

            $array_cod = array();
            foreach ($_POST['codigo'] as $codigo) {
                array_push($array_cod, $codigo);
            }

            $array_errors = array();
            foreach ($array_cod as $value) {

                // Remove licença
                $query = $core->Prepare("DELETE FROM licenca WHERE id = '" . $value . "'");
                $resposta = $query->Execute();

                $erro = "0";
                $msg = "Excluído com sucesso!";
            }
        } catch (Exception $e) {

            $erro = "1";
            $msg = "Ocorreu um erro: $e";

            $return = "error||" . $erro . "||msg||" . $msg;
            echo $return;
            return $return;
        }

        $return = "success||" . $erro . "||msg||" . $msg;
        echo $return;
        return $return;
    }
} else if ($_POST['pagina'] == 'clientes') {
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

                if (isset($_POST['ativa'])) {
                    $novoStatus = "a";
                    $desativa = "1";
                } else {
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
                    $return = "error||" . $erro . "||msg_erro||" . $msg_erro . "||id_valor||" . $value . "||novo_status||" . $novoStatus;
                    return $return;
                }
            }
        }
        $return = "success||" . $erro . "||msg_erro||" . $msg_erro . "||id_valor||" . $value . "||novo_status||" . $novoStatus;
        echo $return;
        return $return;
    } else if ($_POST['tipo'] == 'remover') {

        $erro = "0";
        $msg = "";

        try {

            $array_cod = array();
            foreach ($_POST['codigo'] as $codigo) {
                array_push($array_cod, $codigo);
            }

            $array_errors = array();
            foreach ($array_cod as $value) {

                // Remove servico
                $query = $core->Prepare("DELETE FROM servicos_adicionais WHERE codigo_cliente = '" . $value . "'");
                $resposta = $query->Execute();


                // Remove Faturas
                $query = $core->Prepare("DELETE FROM faturas WHERE codigo_cliente = '" . $value . "'");
                $resposta = $query->Execute();


                // Remove licenca
                $query = $core->Prepare("DELETE FROM licenca WHERE id_cliente = '" . $value . "'");
                $resposta = $query->Execute();


                $query = $core->Prepare("DELETE FROM clientes WHERE codigo = '" . $value . "'");
                $resposta = $query->Execute();

                $erro = "0";
                $msg = "Excluído com sucesso!";
            }
        } catch (Exception $e) {

            $erro = "1";
            $msg = "Ocorreu um erro: $e";

            $return = "error||" . $erro . "||msg||" . $msg;
            echo $return;
            return $return;
        }

        $return = "success||" . $erro . "||msg||" . $msg;
        echo $return;
        return $return;
    }
} else {
    echo "<h1>Você não tem permissão para acessar esta página!</h1>";
}
