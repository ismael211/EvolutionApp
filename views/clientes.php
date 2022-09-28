<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

include('nav.php');
include('side-bar.php');

$core = new IsistemCore();
$core->Connect();

$qtd_clientes = $core->RowCount("SELECT * FROM `clientes`");

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

    <script src="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"></script>
    
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>

    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>


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
                                    <li class="breadcrumb-item active">Visualizar Clientes
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
                                                <button type="button" class="btn btn-primary btn-sm" id="action_bt_opcoes" style="margin-left: 5px;">OK</button>
                                            </div>

                                        </li>
                                    </ol>

                                    <br>
                                    <div class="table-responsive-sm">

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
                                                            <td><?= $nome; ?></td>
                                                            <td><?= $row['email1'] ?></td>

                                                            <?php if ($row['tipo_cliente'] = 'u') { ?>
                                                                <td>Usuário</td>
                                                            <?php } else { ?>

                                                                <td>Revendedor</td>

                                                            <?php } ?>

                                                            <td><?= $row['data_cadastro'] ?></td>

                                                            <?php if ($row['status'] == 'a') {
                                                            ?>
                                                                <td>
                                                                    <a href="#" class="limpa-estilo muda_status" id="<?= $row['codigo'] ?>" vstatus="<?= $row['status'] ?>">
                                                                        <span class="badge bg-success">Ativado</span>
                                                                    </a>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td>
                                                                    <a href="#" class="limpa-estilo muda_status" id="<?= $row['codigo'] ?>" vstatus="<?= $row['status'] ?>">
                                                                        <span class="badge bg-danger">Desativado</span>
                                                                    </a>
                                                                </td>
                                                            <?php
                                                            }

                                                            ?>
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

                                        <!-- Dynamic Default Pagination starts -->
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Defauuul</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="page1-content" class="border-grey border-lighten-2 mb-1">You are on paage 1</div>
                                                    <ul class="pagination page1-links"></ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Dynamic Default Pagination ends -->

                                    </div>
                                </div>

                            </div><!-- /.row -->
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

<!-- BEGIN: Vendor JS-->
<script src="../app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<!-- END: Page Vendor JS-->



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