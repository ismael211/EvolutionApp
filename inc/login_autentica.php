<?php


if (isset($_POST['username'])) {

    if ($_POST['username'] == "") {
        $return = array("error" => "1", "msg" => "Usuário em branco!");

    } elseif ($_POST['password'] == "") {
        $return = array("error" => "1", "msg" => "Senha em branco!");
    } else {

        $user = $_POST['username'];
        $pass = $_POST['password'];

        $query = $this->conn->query("SELECT `codigo`,`nome`,`senha` FROM `operadores` WHERE `login` = '" . $user  . "' LIMIT 1");
        $cnt = $query->num_rows;
        if ($cnt == 1) {
            $linha = $query->fetch_array();
            if (password_verify($this->pass, $linha["2"])) {
                $codigo = $linha["0"];
                $nome = $linha["1"];
                $return = array("error" => "0", "codigo" => "$codigo", "nome" => "$nome");
            } else {
                $return = array("error" => "1", "msg" => "Usuário ou Senha Inválido!");
            }
        } else {
            $return = array("error" => "1", "msg" => "Usuário ou Senha Inválido!");
        }
    }
    echo $return;
}
