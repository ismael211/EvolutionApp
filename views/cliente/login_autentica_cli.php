<?php

session_start();
require_once('../../inc/config.php');

$core = new IsistemCore();
$core->Connect();


if (isset($_POST['username'])) {

    $return = '';

    if ($_POST['username'] == "") {
        $return = "Usuário em branco!";

    } elseif ($_POST['password'] == "") {
        $return = "Senha em branco!";
    } else {

        $user = $_POST['username'];
        $pass = $_POST['password'];
        
        $qtd = $core->RowCount("SELECT * FROM `clientes` WHERE `email1` = '" . $user  . "' OR `email2` = '" . $user  . "' LIMIT 1");

        if ($qtd == 1) {

            $query = $core->Fetch("SELECT `codigo`,`nome`,`senha` FROM `operadores` WHERE `login` = '" . $user  . "' LIMIT 1");

            if (password_verify($pass, $query['senha'])) {
                $_SESSION['codigo_adm'] = $query['codigo'];
                $_SESSION['nome_adm'] = $query['nome'];
                exit();
            } else {
                $return = "Usuário ou Senha Inválido!";
            }
        } else {
            $return = "Usuário ou Senha Inválido!";
        }
    }
    echo $return;
}
