<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

include('../index.php');

include('nav.php');
include('side-bar.php');

$core = new IsistemCore();
$core->Connect();

$qtd_abertas = $core->RowCount("SELECT * FROM faturas LEFT JOIN clientes ON faturas.codigo_cliente = clientes.codigo
WHERE faturas.status = 'off' ORDER BY faturas.data_vencimento DESC");

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

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

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <!-- END: Custom CSS-->

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
                            <h2 class="content-header-title float-left mb-0">Financeiro</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Visualizar Faturas Abertas
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <span id="status_volta"></span>

            <div class="col-xl-12 col-md-12 col-12">
                <div class="card">

                    <div class="card-body">

                        <div id="page-wrapper">

                            <span id="status_volta"></span>

                            <div class="row">

                                <div class="col-lg-12">

                                    <ol class="breadcrumb">
                                        <li class="active form-inline">

                                            <div class="form-group">
                                                <select class="form-control" name="opcoes" id="opcoes">
                                                    <option value="0" selected="selected"> -- Opções -- </option>
                                                    <option value="visualizar">Visualizar</option>
                                                    <option value="quitar">Quitar</option>
                                                    <option value="editar">Editar</option>
                                                    <option value="remover">Remover</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary" id="bt_action_financeiro">OK</button>
                                            </div>

                                        </li>
                                    </ol>

                                </div>

                            </div><!-- /.row -->

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-money"></i> Faturas Abertas</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th># <i class="fa fa-sort"></i></th>
                                                            <th>Codigo Fatura <i class="fa fa-sort"></i></th>
                                                            <th>Cliente <i class="fa fa-sort"></i></th>
                                                            <th>Tipo Cliente <i class="fa fa-sort"></i></th>
                                                            <th>Valor(R$) <i class="fa fa-sort"></i></th>
                                                            <th>Data Vencimento <i class="fa fa-sort"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if ($qtd_abertas > 0) {

                                                            $fat_abertas = $core->FetchAll("SELECT faturas.codigo, faturas.data_vencimento, faturas.valor, clientes.nome as nome_cliente,
                                                            clientes.tipo_cliente, faturas.status FROM faturas LEFT JOIN clientes ON faturas.codigo_cliente = clientes.codigo
                                                            WHERE faturas.status = 'off' ORDER BY faturas.data_vencimento DESC");
                                                            foreach ($fat_abertas as $row) {
                                                                $nome = substr($row['nome_cliente'], 0, 30);
                                                        ?>
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" value="<?= $row['codigo'] ?>" name="codigo_fatura" id="codigo_fatura">
                                                                        <input type="hidden" id="id_fatura" name="id_fatura" value="<?= $row['codigo'] ?>">
                                                                    </td>
                                                                    <td><?= $row['codigo'] ?></td>
                                                                    <td><?= $nome; ?></td>
                                                                    <?php if ($row['tipo_cliente'] = 'r') { ?>

                                                                        <td>Revendedor</td>
                                                                    <?php } else { ?>
                                                                        <td>Usuário</td>

                                                                    <?php } ?>
                                                                    <td><?= $row['valor'] ?></td>
                                                                    <td><?= $row['data_vencimento'] ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td>Nenhuma fatura vencendo hoje </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
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
    $(document).ready(function() {

        var iconealert = '<i class="bi bi-exclamation-triangle text-warning"></i>';
        var lupa = '<i class="bi bi-search"></i>';
        var table = $('.table').DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, 'All']
            ],
            paging: true,
            order: [
                [3, 'asc'],
                [0, 'asc']
            ],
            language: {
                "lengthMenu": "Mostrando _MENU_ registros página",
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": iconealert + " Nenhum registro disponivel",
                "infoFiltered": "(filtrado de MAX registros no total)",
                "search": lupa + " Pesquisar",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Proxima"
                }
            },
            "autoWidth": true
        });
    })
</script>