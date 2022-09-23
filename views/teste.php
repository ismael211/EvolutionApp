<?php

session_start();
require_once('../inc/config.php');

$core = new IsistemCore();
$core->Connect();

$clientes = $core->Fetch("SELECT * FROM `clientes`");

$qtd_clientes_ativos = $core->RowCount("SELECT * FROM `clientes` WHERE `status` = 'a'");

$qtd_clientes_prop = $core->RowCount("SELECT * FROM `clientes` WHERE `status` = 'p'
AND data_cadastro BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");

$licencas = $core->Fetch("SELECT * FROM `licenca`");

$qtd_licencas = $core->RowCount("SELECT * FROM `licenca` WHERE `status` = '1'");

$faturas = $core->Fetch("SELECT * FROM `faturas`");

$dataHoje = date("Y-m-d");
// $faturas = new App\Model\Faturas($conexao);
// $faturas->setDataFind($dataHoje);
// $vencendo_hoje = $faturas->vencendoPorData();
// $allFaturasAbertas = $faturas->getCountFaturasAberta();

// return $app['twig']->render('painel.twig', array(
//     'qtd_clientes_ativos'  =>  $qtd_clientes_ativos,
//     'qtd_licencas'  => $qtd_licencas,
//     'qtd_clientes_prop' => $qtd_clientes_prop,
//     'vencendo_hoje' => $vencendo_hoje,
//     'allFaturasAbertas' => $allFaturasAbertas,
// ));


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
                            <h2 class="content-header-title float-left mb-0">Home</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Painel Inicial
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <div id="page-wrapper">

                    <!-- INCIALIZADO PAINEL DE INFORMAÇÔES -->

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h2 class="text-white"><i data-feather='user-plus'></i> <?= $qtd_clientes_prop ?></h2>
                                    <h6 class="text-white" style="float: right;">+ Clientes</6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-white" href="#">Visualizar</a><i data-feather='arrow-right-circle' style="float: right;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h2 class="text-white"><i data-feather='dollar-sign'></i> 3</h2>
                                    <h6 class="text-white" style="float: right;"> Faturas</6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-white" href="#">Visualizar</a><i data-feather='arrow-right-circle' style="float: right;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h2 class="text-white"><i data-feather='align-justify'></i> <?= $qtd_licencas ?></h2>
                                    <h6 class="text-white" style="float: right;">Licenças</6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-white" href="#">Visualizar</a><i data-feather='arrow-right-circle' style="float: right;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h2 class="text-white"><i data-feather='users'></i> <?= $qtd_clientes_ativos ?></h2>
                                    <h6 class="text-white" style="float: right;">Clientes</6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-white" href="#">Visualizar</a><i data-feather='arrow-right-circle' style="float: right;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-money"></i> Faturas Vencendo Hoje</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th># <i class="fa fa-sort"></i></th>
                                                    <th>Cliente <i class="fa fa-sort"></i></th>
                                                    <th>Tipo Cliente <i class="fa fa-sort"></i></th>
                                                    <th>Valor(R$) <i class="fa fa-sort"></i></th>
                                                    <th>Data Vencimento <i class="fa fa-sort"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                ?>
                                                {% if vencendo_hoje|length > 0 %}
                                                {% for valor in vencendo_hoje %}
                                                <tr>
                                                    <td>{{ valor.nome}}</td>
                                                    {% if valor.tipo_cliente == "r" %}
                                                    <td>Revendedor</td>
                                                    {% else %}
                                                    <td>Usuário</td>
                                                    {% endif %}
                                                    <td>{{ valor.valor }}</td>
                                                    <td>{{ valor.data_vencimento }}</td>
                                                </tr>
                                                {% endfor %}
                                                {% else %}
                                                <tr>
                                                    <td>Nenhuma fatura vencendo hoje </td>                                         
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                {% endif %}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-right">
                                        <a href="#">Visualizar Todos <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.row -->

                </div><!-- /#page-wrapper -->

            </div>
        </div>
    </div>

</body>

</html>

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