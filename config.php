<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('inc/config.php');

$core = new IsistemCore();
$core->Connect();


?>


<!-- funcao abre modal processando -->
<script>
    function processando(faca) {
        if (faca == "1") {
            $.blockUI({
                message: '<div class="spinner-border text-primary" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8
                }
            });

        }
        if (faca == "0") {
            $.blockUI({
                message: '<div class="spinner-border text-primary" role="status"></div>',
                timeout: 10,
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8
                }
            });
        }
    }
</script>

<!-- Apaga um elemento da string -->
<script>
    function remove(str, sub) {
        i = str.indexOf(sub);
        r = "";
        if (i == -1) return str;
        r += str.substring(0, i) + remove(str.substring(i + sub.length), sub);
        return r;
    }
</script>

<!-- Função para validar CPF -->
<script>
    function validarCPF() {

        var cpf = document.getElementById("cpf").value;
        var filtro = /^\d{3}.\d{3}.\d{3}-\d{2}$/i;
        if (cpf == "") {
            return true;
        }
        if (!filtro.test(cpf)) {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }

        cpf = remove(cpf, ".");
        cpf = remove(cpf, "-");

        if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
            cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
            cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
            cpf == "88888888888" || cpf == "99999999999") {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }

        soma = 0;
        for (i = 0; i < 9; i++)
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        resto = 11 - (soma % 11);
        if (resto == 10 || resto == 11)
            resto = 0;
        if (resto != parseInt(cpf.charAt(9))) {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }
        soma = 0;
        for (i = 0; i < 10; i++)
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        resto = 11 - (soma % 11);
        if (resto == 10 || resto == 11)
            resto = 0;
        if (resto != parseInt(cpf.charAt(10))) {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }
        return true;
    }
</script>

<?php
// Metodo que gera Key
function generateKey()
{

    $a = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
    $b = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
    $c = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
    $d = strtoupper(substr(md5(rand(11111,99999)), 5, 5));
    $e = strtoupper(substr(md5(rand(11111,99999)), 5, 5));

    return "$a-$b-$c-$d-$e";
}

function novaFatura()
{
    $erro = "0";
    $msg = "";
    $retorno_cadastra = "";

    // Quantidade de licenca do cliente
    $licenca->setTable("licenca");
    $quantidade = $licenca->getCountForIdCliente($this->idCliente);

    // Tipo de Cliente
    $cliente = new Clientes($this->conn);
    $cliente->setTable("clientes");
    $cliente = $cliente->getForId($this->idCliente);
    $tipo_cliente = $cliente["tipo_cliente"];

    if ($tipo_cliente == "r") {

        $sql = $this->conn->query("SELECT codigo, valor, codigo_servico FROM faturas
            WHERE codigo_cliente = '" . $this->idCliente . "' ORDER BY codigo DESC LIMIT 1");
        $linha = $sql->fetch_array(MYSQLI_ASSOC);

        if ($quantidade > 10) {

            $pagamento = new Pagamento($this->conn);
            $pagamento->setIdUser($this->idCliente);
            $retorno_pagamento = $pagamento->getFormaAndModelo();

            $novo_valor = $this->calculoLicencas($quantidade, $linha["valor"], $retorno_pagamento[0]["valor"]);
            $this->conn->query("UPDATE faturas SET valor = '" . $novo_valor . "' WHERE codigo = '" . $linha["codigo"] . "' LIMIT 1");
        }
        if ($linha["codigo_servico"] != "4" and $quantidade == "1") {

            // Cadastra Fatura
            $pagamento = new Pagamento($this->conn);
            $pagamento->setIdUser($this->idCliente);
            $pagamento->where("servicos_modelos.codigo <> '4'");
            $return_pagamento = $pagamento->getFormaAndModeloWhithWhere();
            // Setup
            $data_vencimento = $this->dataAumentaDia(1);

            $this->setCodigoCliente($this->idCliente);
            $this->setCodigoServico($return_pagamento[0]["codigo"]);
            $this->setDataVencimento($data_vencimento);
            $this->setValor($this->moneyFormatView($return_pagamento[0]["valor"]));
            $this->setDescricao($return_pagamento[0]["descricao"]);
            $retorno_cadastra = $this->cadastra();
        }
    }

    // Caso o Cliente seja USUARIO (Uma Licenca e fatura por cadastro)
    if ($tipo_cliente == "u") {

        // Busca Dados
        $pagamento = new Pagamento($this->conn);
        $pagamento->setIdUser($this->idCliente);
        $pagamento->where("servicos_modelos.codigo <> '4'");
        $return_pagamento = $pagamento->getFormaAndModeloWhithWhere();

        $data_vencimento = $this->dataAumentaDia(1);

        if ($quantidade != "1") {
            // Cadastra Servico Adicional
            $plano = new Planos($this->conn);
            $plano->codigoPlano($return_pagamento[0]["codigo_servico"]);
            $plano->codigoCliente($this->idCliente);
            $plano->codigoFormaPagamento($return_pagamento[0]["codigo_forma"]);
            $plano->dataPagamento($data_vencimento);
            $plano->valorPlano($return_pagamento[0]["valor"]);
            $plano->repetir("sim");
            $plano->periodo("1");
            $plano->totalParcelas("0");
            $plano->descricao($return_pagamento[0]["descricao"]);
            $plano->save();
        }

        $return_pagamento_atu = $pagamento->getFormaAndModeloWhithWhere();
        // Cadastra nova fatura
        $this->setCodigoCliente($this->idCliente);
        $this->setCodigoServico($return_pagamento_atu[0]["codigo"]);
        $this->setDataVencimento($data_vencimento);
        $this->setValor($this->moneyFormatView($return_pagamento_atu[0]["valor"]));
        $this->setDescricao($return_pagamento_atu[0]["descricao"]);
        $retorno_cadastra = $this->cadastra();
    }


    $erro = "0";
    $msg = "";

    return array("erro" => $erro, "msg" => $msg, "retorno_cadastra" => $retorno_cadastra["ultimoId"]);
}


?>