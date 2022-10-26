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

                    $query = $core->Prepare("UPDATE licenca SET status = '" . $desativa . "' WHERE id = '" . $value . "'");
                    $resposta = $query->Execute();

                    $erro = "0";

                    if ($erro == "0" and $desativa == "1") {

                        // $licencaEnvios->boasVindas();

                        $dados_licenca = $core->Fetch("SELECT * FROM `licenca` WHERE `id` = '" . $value . "'");

                        $cod_cliente = $dados_licenca['id_cliente'];

                        $returnCliente = $core->Fetch("SELECT * FROM `clientes` WHERE `codigo` = '" . $cod_cliente . "'");

                        $dados_sistema = $core->Fetch("SELECT * FROM sistema");

                        // $envio = new Envio();
                        // $envio->setConfigSmtp($dados_sistema["servidor_smtp"], $dados_sistema["servidor_smtp_porta"],
                        // $dados_sistema["servidor_smtp_usuario"], $dados_sistema["servidor_smtp_senha"]);

                        $subject = "[Isistem] - Bem Vindo ";

                        $dados_textos = $core->Fetch("SELECT texto FROM textos WHERE codigo = '34'");

                        $mensagem_editada = str_replace('[cliente_nome]', $returnCliente["nome"], $dados_textos["texto"]);
                        $mensagem_editada = str_replace('[key]', $dados_licenca["key_licenca"], $mensagem_editada);
                        $mensagem_editada = str_replace('[linksistema]', $dados_licenca["sub_dominio"], $mensagem_editada);
                        $mensagem_editada = str_replace('[cliente_email_principal]', $returnCliente["email1"], $mensagem_editada);

                        $envio = envia_Email(
                            $returnCliente["nome"],
                            $returnCliente["email1"],
                            $returnCliente["email2"],
                            $subject,
                            $mensagem_editada
                        );


                        if ($envio == "ok") {
                            $erro = '0';
                            $msg_erro = $envio;
                        } else {
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
} else if ($_POST['pagina'] == 'financeiro') {

    if ($_POST['tipo'] == 'quitar') {

        $array_cod = array();
        foreach ($_POST['codigo'] as $codigo) {
            array_push($array_cod, $codigo);
        }

        $array_errors = array();
        foreach ($array_cod as $value) {

            $erro = "0";
            $msg = "";


            // Uso Geral
            $dados_empresa = $core->Fetch("SELECT * FROM `empresa`");

            $dados_sistema = $core->Fetch("SELECT * FROM `sistema`");

            $dados_fatura = $core->Fetch("SELECT * FROM `faturas` WHERE `codigo` = '" . $value . "'");

            $dados_cliente = $core->Fetch("SELECT * FROM `clientes` WHERE `codigo` = '" . $dados_fatura["codigo_cliente"] . "'");


            try {

                if ($dados_fatura["tipo"] == 's' and $dados_fatura["status"] != 'on') {

                    // Quita a fatura
                    $query = $core->Prepare("UPDATE `faturas` SET `status` = 'on', `data_pagamento` = NOW() WHERE `codigo` = '" . $value . "'");
                    $result = $query->Execute();


                    $dados_servicos = $core->Fetch("SELECT * FROM `servicos_adicionais` WHERE `codigo` = '" . $dados_fatura["codigo_servico"] . "' LIMIT 1");

                    $dados_formas_pagamento = $core->Fetch("SELECT * FROM `formas_pagamento` WHERE `codigo` = '" . $dados_servicos["codigo_forma_pagto"] . "'");

                    // $dados_textos = $core->Fetch("SELECT * FROM `textos` WHERE `codigo` = '" . $dados_formas_pagamento["texto_confirmacao"] . "'");

                    $dados_textos = $core->Fetch("SELECT * FROM `textos` WHERE `codigo` = '10'");


                    if ($dados_servicos["repetir"] == 'sim') {
                        if ($dados_servicos["parcela_atual"] < $dados_servicos["total_parcelas"] || $dados_servicos["total_parcelas"] == 0) {

                            list($ano, $mes, $dia) = explode("-", $dados_fatura["data_vencimento"]);
                            $vencimento_proxima_fatura = date("Y-m-d", mktime(
                                0,
                                0,
                                0,
                                $mes + $dados_servicos["periodo"],
                                $dados_servicos["data_pagto"],
                                $ano
                            ));

                            $parcela_atual = $dados_servicos["parcela_atual"] + 1;

                            $query = $core->Prepare("UPDATE `servicos_adicionais` SET `parcela_atual` = '$parcela_atual' 
                                WHERE `codigo` = '" . $dados_servicos["codigo"] . "'");
                            $result = $query->Execute();

                            // Verifica se existe alguma fatura com os mesmo dados da próxima fatura
                            $total_faturas_atuais = $core->RowCount("SELECT * FROM faturas 
                                    WHERE codigo_cliente = '" . $dados_servicos["codigo_cliente"] . "'
                                    AND codigo_servico = '" . $dados_servicos["codigo"] . "'
                                    AND data_vencimento = '" . $vencimento_proxima_fatura . "'
                                    AND valor = '" . $dados_servicos["valor"] . "' AND status = 'off'");

                            if ($total_faturas_atuais == "0") {

                                $query = $core->Prepare("INSERT INTO faturas (codigo_cliente,codigo_servico,data_vencimento,valor,descricao,tipo) 
                                        VALUES ('$dados_servicos[codigo_cliente]','$dados_servicos[codigo]','$vencimento_proxima_fatura',
                                        '$dados_servicos[valor]','$dados_servicos[descricao]','s')");
                                $result = $query->Execute();
                            }


                            $query = $core->Prepare("UPDATE `licenca` SET `status` = '1' WHERE `id_fatura` = '" . $value . "'");
                            $result = $query->Execute();


                            // Chama Envio Email
                            $data_formatada = date_create($dados_fatura["data_vencimento"]);
                            $data_vencimento = date_format($data_formatada, "d/m/Y");

                            $valor = moneyFormatBD($dados_fatura["valor"]);

                            $mensagem_editada = str_replace('[cliente_nome]', $dados_cliente["nome"], $dados_textos["texto"]);
                            $mensagem_editada = str_replace('[cliente_email_principal]', $dados_cliente["email1"], $mensagem_editada);
                            $mensagem_editada = str_replace('[cliente_senha]', $dados_cliente["senha"], $mensagem_editada);
                            $mensagem_editada = str_replace('[vencimento]', $data_vencimento, $mensagem_editada);
                            $mensagem_editada = str_replace('[valor]', "R$ " . $valor, $mensagem_editada);
                            $mensagem_editada = str_replace('[descricao]', $dados_fatura["descricao"], $mensagem_editada);
                            $mensagem_editada = str_replace('[empresa_nome]', $dados_empresa["nome"], $mensagem_editada);
                            $mensagem_editada = str_replace('[empresa_url_site]', $dados_empresa["url_site"], $mensagem_editada);
                            $mensagem_editada = str_replace('[empresa_url_sistema]', $dados_empresa["url_sistema"], $mensagem_editada);
                            $mensagem_editada = str_replace('[empresa_url_logo]', $dados_empresa["logo"], $mensagem_editada);
                            $mensagem_editada = str_replace('[empresa_email]', $dados_empresa["email"], $mensagem_editada);

                            $envia = envia_Email(
                                $dados_cliente["nome"],
                                $dados_cliente["email1"],
                                $dados_cliente["email2"],
                                $dados_textos["titulo"],
                                $mensagem_editada
                            );


                            if ($envia != 'ok') {
                                $erro = '1';
                                // $msg = 'Alterado, mas email não enviado';
                                $msg = $envia;
                            }
                        }
                    }
                } else {
                    $erro = "1";
                    $msg = 'Fatura não cumpre os requisitos';
                    $return = "error||" . $erro . "||msg||" . $msg;
                    echo $return;
                    return $return;
                    exit();
                }
            } catch (Exception $e) {
                $erro = '1';
                $msg = 'Não foi possível quitar fatura. ' . $e;
                $return = "error||" . $erro . "||msg||" . $msg;
                echo $return;
                return $return;
                exit();
            }
        } // Fim foreach

        if ($erro == "1") {
            $return = "info||" . $erro . "||msg||" . $msg;
            echo $return;
            return $return;
            exit();
        } else {
            $msg = "Fatura(s) Quitada(s) com sucesso!";
            $return = "success||" . $erro . "||msg||" . $msg;
            echo $return;
            return $return;
            exit();
        }
    } else if ($_POST['tipo'] == 'remover') {

        $erro = "0";
        $msg = "";

        $array_cod = array();
        foreach ($_POST['codigo'] as $codigo) {
            array_push($array_cod, $codigo);
        }

        $array_errors = array();
        foreach ($array_cod as $value) {

            try{
                $query = $core->Prepare("DELETE FROM faturas WHERE codigo = '" .$value. "'");
                $result = $query->Execute();
            }catch (Exception $e){
                $erro = '1';
                $msg = 'Não foi possível deletar fatura. ' . $e;
                $return = "error||" . $erro . "||msg||" . $msg;
                echo $return;
                return $return;
                exit();
            }
        }

        $erro = '0';
        $msg = 'Excluido com sucesso.';
        $return = "success||" . $erro . "||msg||" . $msg;
        echo $return;
        return $return;
        exit();

    }
} else {
    echo "<h1>Você não tem permissão para acessar esta página!</h1>";
}
