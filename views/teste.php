<?php

session_start();
require_once('../inc/config.php');

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
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>TESTE</title>
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

    <div class="sixteen wide col mdc-bg-grey-50" id="content_context" style="height: 100%">
        <div class="container" style="padding: 8px">
            <div class="segment stackable grid">

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
                                                <table class="datatables-basic table">
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
                                                                    <td><?= utf8_encode($nome); ?></td>
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

            </div>
        </div>

    </div>

</body>

<!-- BEGIN: Page Vendor JS-->
<script src="../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
<script src="../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Page JS-->
<script src="../app-assets/js/scripts/tables/table-datatables-basic.js"></script>
<!-- END: Page JS-->