<?php

session_start();

require_once('../inc/config.php');
include('../index.php');

include('nav.php');
include('side-bar.php');

include('../funcoes.php');


$core = new IsistemCore();
$core->Connect();

$codigo = $_GET['i'];

$fatura = $core->Fetch("SELECT * FROM `faturas` WHERE `codigo` = '" . $codigo . "'");

$cliente = $core->Fetch("SELECT * FROM `clientes` WHERE codigo = '" . $fatura['codigo_cliente'] . "' LIMIT 1");

$retorno = esqueletoFormaPagamento($formapagto['codigo_forma_pagto'], $codigo);

$data_formatada = date_create($fatura['data_vencimento']);
$data_formatada = date_format($data_formatada, "d/m/Y");

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
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
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

</head>
<!-- END: Head-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-<?= $menu . ' ' . $tema ?>" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Financeiro</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Editar Fatura
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-md-12 col-12">
                <div class="card">

                    <div class="card-body">

                        <div id="page-wrapper">

                            <div class="row">

                                <div class="col-lg-12">

                                    <form role="form">
                                        <div class="form-group">
                                            <label>Cliente</label>
                                            <input type="hidden" name="id_fatura" id="id_fatura" value="<?= $fatura['codigo'] ?>">
                                            <p><strong> <?= $cliente['nome'] ?> </strong></p>
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="off" <?php echo $fatura['status'] == 'off' ? 'selected' : '' ?>>Aberta</option>
                                                <option value="on" <?php echo $fatura['status'] == 'on' ? 'selected' : '' ?>>Quitada</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Data de Vencimento</label>
                                            <input class="form-control mask_data_br" type="text" name="data_vencimento" id="data_vencimento" value="<?= $data_formatada ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Obs</label>
                                            <textarea class="form-control" rows="3" name="obs" id="obs"><?= $fatura['descricao'] ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Valor</label>
                                            <input class="form-control" type="text" name="valor" id="valor" value="<?= $fatura['valor'] ?>">
                                        </div>

                                        <div class="well">
                                            <button type="button" class="btn btn-primary" id="bt_editar_fatura">Editar</button>
                                        </div>

                                    </form>
                                </div>

                            </div><!-- /.row -->

                        </div><!-- /#page-wrapper -->

                    </div>
                    <div class="card-footer">
                        <h6>Painel Licença</h6>
                    </div><!-- /#page-wrapper -->
                </div>
            </div>
        </div>
    </div>
</body>


<!-- BEGIN: Vendor JS-->
<script src="../../app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="../../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
<script src="../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="../../app-assets/js/core/app-menu.js"></script>
<script src="../../app-assets/js/core/app.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="../../app-assets/vendors/js/extensions/polyfill.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Page JS-->
<script src="../../app-assets/js/scripts/tables/table-datatables-basic.js"></script>
<!-- END: Page JS-->

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

<script>
    $("#bt_editar_fatura").click(function() {

        processando(1);
        var id_fatura = $("#id_fatura").val();
        var status = $("#status").val();
        var data_vencimento = $("#data_vencimento").val();
        var valor = $("#valor").val();
        var descricao = $("#obs").val();


        $.ajax({
            type: "POST",
            url: "/views/EditaFatura.php",
            data: {
                'id_fatura': id_fatura,
                'status': status,
                'data_vencimento': data_vencimento,
                'valor': valor,
                'descricao': descricao
            },
            success: function(msg) {

                processando(0);

                data = msg.split("||");

                if (data[0] == 'error') {
                    Swal.fire({
                        title: 'Atenção',
                        html: data[1],
                        icon: 'error',
                        width: '900px',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false,
                        allowOutsideClick: false
                    })
                } else {
                    Swal.fire({
                        title: 'Concluído',
                        html: data[1],
                        icon: 'success',
                        width: '900px',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false,
                        allowOutsideClick: false
                    }).then((result) => {
                        /* Read more about isConfirmed*/
                        if (result.isConfirmed) {
                            window.location.href = '';
                        }
                    })
                };

            }
        });

    });
</script>