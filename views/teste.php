<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

include('nav.php');
include('side-bar.php');

$core = new IsistemCore();
$core->Connect();

$qtd_clientes = $core->RowCount("SELECT * FROM `clientes`");

$qtd_clientes_ativos = $core->RowCount("SELECT * FROM `clientes` WHERE `status` = 'a'");

$qtd_clientes_prop = $core->RowCount("SELECT * FROM `clientes` WHERE `status` = 'p'
AND data_cadastro BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");

$qtd_licencas = $core->RowCount("SELECT * FROM `licenca` WHERE `status` = '1'");

$allFaturasAbertas = $core->RowCount("SELECT codigo FROM faturas WHERE status = 'on'");

$dataHoje = date("Y-m-d");

$qtd_vencendo_hj = $core->RowCount("SELECT clientes.nome, clientes.tipo_cliente, faturas.valor, faturas.data_vencimento, faturas.codigo FROM faturas LEFT JOIN clientes ON clientes.codigo = faturas.codigo_cliente LEFT JOIN servicos_adicionais ON servicos_adicionais.codigo = faturas.codigo_servico WHERE faturas.data_vencimento = '2022-07-26' AND faturas.status = 'on' ");

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
    <link rel="apple-touch-icon" href="../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/bordered-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <!-- END: Custom CSS-->

    <script src="../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="../app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>

    <script src="../app-assets/js/scripts/tables/table-datatables-advanced.js"></script>

    <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>

</head>
<!-- END: Head-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            <div id="page-wrapper">

                <div class="content-header row">
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-left mb-0">Clientes</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Visualizar Clientes Novos
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <span id="status_volta"></span>

                <div class="row">

                    <div class="col-lg-12">

                        <ol class="breadcrumb">
                            <li class="active form-inline">

                                <div class="form-group">
                                    <select class="form-control" name="opcoes" id="opcoes">
                                        <option value="0" selected="selected"> -- Opções -- </option>
                                        <option value="ativar">Ativar</option>
                                        <option value="desativar">Desativar</option>
                                        <option value="editar">Editar</option>
                                        <option value="remover">Remover</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="action_bt_opcoes">OK</button>
                                </div>

                            </li>
                        </ol>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th># <i class="fa fa-sort"></i></th>
                                    <th>ID <i class="fa fa-sort"></i></th>
                                    <th>Cliente <i class="fa fa-sort"></i></th>
                                    <th>Email <i class="fa fa-sort"></i></th>
                                    <th>Tipo <i class="fa fa-sort"></i></th>
                                    <th>Data Cadastro <i class="fa fa-sort"></i></th>
                                    <th>Status <i class="fa fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if ($qtd_clientes > 0) {

                                    $clientes = $core->FetchAll("SELECT codigo, nome, email1, email2, fone, celular, data_cadastro, status,
                        tipo_cliente FROM clientes");
                                    foreach ($clientes as $row) {
                                        $nome = substr($row['nome'], 0, 30);
                                ?>

                                        <tr>
                                            <td>
                                                <input type="checkbox" value="<?= $row['codigo'] ?>" name="codigo_cli" id="codigo_cli">
                                                <input type="hidden" id="idcliente" name="idcliente" value="<?= $row['codigo'] ?>">
                                            </td>
                                            <td><?= $row['codigo'] ?></td>
                                            <td><?= $nome ?></td>
                                            <td><?= $row['email'] ?></td>

                                            <?php if ($row['tipo_cliente'] = 'u') { ?>
                                                <td>Usuário</td>
                                            <?php } else { ?>
                                                <td>Revendedor</td>
                                            <?php } ?>

                                            <td><?= $row['data_cadastro'] ?></td>


                                            <?php if ($row['status'] == 'a') { ?>
                                                <td>
                                                    <a href="#" class="limpa-estilo muda_status" id="{{ valor.codigo }}" vstatus="{{ valor.status }}">
                                                        <span class="label label-success">Ativado</span>
                                                    </a>
                                                </td>
                                            <?php } else { ?>
                                                <td>
                                                    <a href="#" class="limpa-estilo muda_status" id="{{ valor.codigo }}" vstatus="{{ valor.status }}">
                                                        <span class="label label-danger">Desativado</span>
                                                    </a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php

                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td>Nenhum Registro</td>
                                        <td></td>
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

                </div><!-- /.row -->

            </div><!-- /#page-wrapper -->

        </div>
    </div>
</body>

<!-- BEGIN: Vendor JS-->
<script src="../app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<!-- END: Page Vendor JS-->

<script src="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- BEGIN: Theme JS-->
<script src="../app-assets/js/core/app-menu.js"></script>
<script src="../app-assets/js/core/app.js"></script>
<!-- END: Theme JS-->

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

<!-- BEGIN: Page JS-->
<script src="../app-assets/js/scripts/ui/ui-feather.js"></script>
<!-- END: Page JS-->

<!-- BEGIN: Page JS-->
<script src="../app-assets/js/scripts/pagination/components-pagination.js"></script>
<!-- END: Page JS-->

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