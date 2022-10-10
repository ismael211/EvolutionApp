<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

include('../index.php');

include('nav.php');
include('side-bar.php');
include('../funcoes.php');

$core = new IsistemCore();
$core->Connect();

$forma_pag = $core->FetchAll("SELECT * FROM `formas_pagamento`");

$planos = $core->FetchAll("SELECT * FROM `servicos_modelos`");


?>


<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Isistem Painel Gerenciavel</title>
    <link rel="apple-touch-icon" href="../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/bordered-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->


    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <!-- END: Custom CSS-->


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

    <script src="https://unpkg.com/imask"></script>


</head>
<!-- END: Head-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Clientes</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Cadastrar Clientes
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <span id="status_volta"></span>

                <div class="col-xl-12 col-md-12 col-12">
                    <div class="card">

                        <div class="card-body">

                            <div id="page-wrapper">

                                <div class="row">
                                    <div class="col-lg-12">

                                        <form role="form">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>Status</label>
                                                    <select class="form-control" id="status_cli" name="status_cli">
                                                        <option value="" selected="selected">Selecione um status</option>
                                                        <option value="a">Ativo</option>
                                                        <option value="p">Prospect</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Tipo de Cliente</label>
                                                    <select class="form-control" id="tipo_cliente" name="tipo_cliente">
                                                        <option value="" selected="selected">Selecione uma opção</option>
                                                        <option value="u">Usuário</option>
                                                        <option value="r">Revendedor</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Nome Responsável</label>
                                                <input class="form-control" type="text" name="nome" id="nome">
                                            </div>


                                            <div class="collapse-margin" id="accordionExample">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card">

                                                            <div class="card-header" id="headingOne" data-toggle="collapse" onclick="limpa_campos()" role="button" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                                <span class="lead collapse-title">Fisica</span>
                                                            </div>

                                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div id="fisica" class="well">

                                                                        <div class="form-group">
                                                                            <label>RG:</label>
                                                                            <input class="form-control" type="text" name="rg" id="rg" maxlength="7" onkeypress="if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>CPF</label>
                                                                            <input class="form-control custom-delimiter-mask" type="text" name="cpf" id="cpf" onblur="return validarCPF()">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Data de Nascimento</label>
                                                                            <input class="form-control mask_data" type="text" name="data_nac" id="data_nac" maxlength="10">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-header" onclick="limpa_campos()" id="headingTwo" data-toggle="collapse" role="button" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                <span class="lead collapse-title">Juridica</span>
                                                            </div>
                                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div id="juridica" class="well">

                                                                        <div class="form-group">
                                                                            <label>CNPJ:</label>
                                                                            <input class="form-control mask_cnpj" type="text" name="cnpj" id="cnpj" maxlength="18" onkeypress="if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Razão Social:</label>
                                                                            <input class="form-control" type="text" name="razao_social" id="razao_social">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>Telefone:</label>
                                                    <input class="form-control mask_tel" type="text" name="fone" id="fone">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Celular:</label>
                                                    <input class="form-control mask_tel" type="text" name="celular" id="celular">
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="form-group col-md-6">
                                                    <label>Email Principal:</label>
                                                    <input class="form-control" type="text" name="email1" id="email1">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Email Secundário:</label>
                                                    <input class="form-control" type="text" name="email2" id="email2">
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="form-group col-md-6">
                                                    <label>Senha:</label>
                                                    <input class="form-control" type="password" name="senha" id="senha">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Repetir Senha:</label>
                                                    <input class="form-control" type="password" name="r_senha" id="r_senha">
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label>Obs</label>
                                                <textarea class="form-control" rows="3" name="obs" id="obs"></textarea>
                                            </div>

                                            <div class="card border">
                                                <div class="card-header">
                                                    <div class="form-group">
                                                        <label>Parceiro</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="1" id="parceiro" name="parceiro" onClick="check()">
                                                                Sim
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label>Tipo de Plano</label>
                                                            <select class="form-control" id="tipo_plano" name="tipo_plano">
                                                                <option value="" selected="selected">Selecione um plano</option>
                                                                <?php foreach ($planos as $valor) { ?>
                                                                    <option value="<?= $valor['codigo'] ?>"><?= $valor['nome'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>



                                                        <div class="form-group col-md-4">
                                                            <label>Forma de Pagamento</label>
                                                            <select class="form-control" id="forma_pagamento" name="forma_pagamento">
                                                                <option value="" selected="selected">Selecione um plano</option>
                                                                <?php foreach ($forma_pag as $valor) { ?>
                                                                    <option value="<?= $valor['codigo'] ?>"><?= $valor['nome'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group  col-md-4">
                                                            <label>Dia de Vencimento:</label>
                                                            <input class="form-control" type="text" name="dia_vencimento" id="dia_vencimento">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="well">
                                                <button type="button" class="btn btn-primary" id="cadastrar_cliente">Cadastrar</button>
                                            </div>

                                        </form>

                                    </div>

                                </div><!-- /#page-wrapper -->

                            </div>
                            <div class="card-footer">
                                <h6>Painel Licença</h6>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

</body>

<!-- END: Body-->

</html>

<!-- BEGIN: Vendor JS-->
<script src="../../app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="../../app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
<script src="../../app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="../../app-assets/js/core/app-menu.js"></script>
<script src="../../app-assets/js/core/app.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="../../app-assets/js/scripts/forms/form-input-mask.js"></script>
<!-- END: Page JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="../../app-assets/vendors/js/extensions/polyfill.min.js"></script>
<!-- END: Page Vendor JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>


<!-- limpa campos -->
<script>
    function limpa_campos() {
        document.getElementById('rg').value = ""
        document.getElementById('cpf').value = ""
        document.getElementById('data_nac').value = ""
        document.getElementById('cnpj').value = ""
        document.getElementById('razao_social').value = ""

    }
</script>

<!-- captura valor do checkbox -->
<script>
    function check() {
        var pacote = document.getElementsByName('parceiro');
        for (var i = 0; i < pacote.length; i++) {
            if (pacote[i].checked) {
                var pacote_val = pacote[i].value;
                return pacote_val;
            } else {
                var pacote_val = '';
                return pacote_val;
            }
        }
    }
</script>

<!-- Action cadastrar cliente  -->
<script>
    $("#cadastrar_cliente").click(function() {

        processando(1);

        var tipo_cliente = $("#tipo_cliente").val();
        var nome = $("#nome").val();
        var rg = $("#rg").val();
        var cpf = $("#cpf").val();
        var data_nac = $("#data_nac").val();
        var cnpj = $("#cnpj").val();
        var razao_social = $("#razao_social").val();
        var fone = $("#fone").val();
        var celular = $("#celular").val();
        var email1 = $("#email1").val();
        var email2 = $("#email2").val();
        var senha = $("#senha").val();
        var r_senha = $("#r_senha").val();
        var obs = $("#obs").val();
        var status_cli = $("#status_cli").val();
        var tipo_plano = $("#tipo_plano").val();
        var forma_pagamento = $("#forma_pagamento").val();
        var dia_vencimento = $("#dia_vencimento").val();
        var parceiro = check();

        if (rg != '') {
            var tipo_pessoa = "fisica";
        } else {
            var tipo_pessoa = "juridica";
        }

        if (senha !== r_senha) {
            processando(0)
            Swal.fire({
                title: 'Atenção',
                html: 'As senhas não são idênticas',
                icon: 'error',
                width: '900px',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false,
                allowOutsideClick: false
            })
        } else {

            $.ajax({
                type: "POST",
                url: "/views/cadastraClientes.php",
                data: {
                    'tipo_cliente': tipo_cliente,
                    'nome': nome,
                    'tipo_pessoa': tipo_pessoa,
                    'rg': rg,
                    'cpf': cpf,
                    'data_nac': data_nac,
                    'cnpj': cnpj,
                    'razao_social': razao_social,
                    'fone': fone,
                    'celular': celular,
                    'email1': email1,
                    'email2': email2,
                    'senha': senha,
                    'r_senha': r_senha,
                    'obs': obs,
                    'status_cli': status_cli,
                    'tipo_plano': tipo_plano,
                    'forma_pagamento': forma_pagamento,
                    "dia_vencimento": dia_vencimento,
                    'parceiro': parceiro
                },
                success: function(msg) {

                    processando(0);

                    data = msg.split("||");

                    Swal.fire({
                        title: 'Atenção',
                        html: data[1],
                        icon: data[0],
                        width: '900px',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false,
                        allowOutsideClick: false
                    })

                }
            });
        }


    });
</script>

<!-- mascara da data -->
<script>
    var input = document.querySelectorAll('.mask_data')[0];

    var dateInputMask = function dateInputMask(elm) {
        elm.addEventListener('keypress', function(e) {
            if (e.keyCode < 47 || e.keyCode > 57) {
                e.preventDefault();
            }

            var len = elm.value.length;

            // If we're at a particular place, let the user type the slash
            // i.e., 12/12/1212
            if (len !== 1 || len !== 3) {
                if (e.keyCode == 47) {
                    e.preventDefault();
                }
            }

            // If they don't add the slash, do it for them...
            if (len === 2) {
                elm.value += '/';
            }

            // If they don't add the slash, do it for them...
            if (len === 5) {
                elm.value += '/';
            }
        });
    };

    dateInputMask(input);
</script>

<!-- mascara do cnpj -->
<script>
    var input = document.querySelectorAll('.mask_cnpj')[0];

    var dateInputMask = function dateInputMask(elm) {
        elm.addEventListener('keypress', function(e) {
            // if (e.keyCode < 47 || e.keyCode > 57) {
            //     e.preventDefault();
            // }

            var len = elm.value.length;

            // If we're at a particular place, let the user type the slash
            // i.e., 12/12/1212
            // if (len !== 1 || len !== 3) {
            //     if (e.keyCode == 47) {
            //         e.preventDefault();
            //     }
            // }

            // If they don't add the slash, do it for them...
            if (len === 2) {
                elm.value += '.';
            }

            // If they don't add the slash, do it for them...
            if (len === 6) {
                elm.value += '.';
            }

            if (len === 10) {
                elm.value += '/';
            }

            if (len === 15) {
                elm.value += '-';
            }
        });
    };

    dateInputMask(input);
</script>

<script>
    var maskCpfOuCnpj = IMask(document.getElementById('celular'), {
        mask: [{
            mask: '(00) 00000-0000',
            maxLength: 15
        }]
    });
</script>
