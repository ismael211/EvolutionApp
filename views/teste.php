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
    <title>Basic Card - Vuexy - Bootstrap HTML admin template</title>
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
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Primary card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Secondary card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Success card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Danger card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up.</p>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="row">
                        <div class="col-lg-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <i class="fa fa-plus fa-5x"></i>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <p class="announcement-heading">{{ qtd_clientes_prop }}</p>
                                            <p class="announcement-text"> + Clientes</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="/sys/clientes">
                                    <div class="panel-footer announcement-bottom">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                Visualizar
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <i class="fa fa-barcode fa-5x"></i>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <p class="announcement-heading">{{ allFaturasAbertas }}</p>
                                            <p class="announcement-text">Faturas</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="/sys/faturas">
                                    <div class="panel-footer announcement-bottom">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                Visualizar
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <i class="fa fa-bars fa-5x"></i>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <p class="announcement-heading">{{ qtd_licencas }}</p>
                                            <p class="announcement-text">Licenças</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="/sys/licencas">
                                    <div class="panel-footer announcement-bottom">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                Visualizar
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <i class="fa fa-users fa-5x"></i>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <p class="announcement-heading">{{ qtd_clientes_ativos }}</p>
                                            <p class="announcement-text">Clientes</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="/sys/clientes">
                                    <div class="panel-footer announcement-bottom">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                Visualizar
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div><!-- /.row -->

                    <!-- FINALIZADO PAINEL DE INFORMAÇÔES -->


                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-money"></i> Faturas Vencendo Hoje</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped tablesorter">
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
                                                {% if vencendo_hoje|length > 0 %}
                                                {% for valor in vencendo_hoje %}
                                                <tr>
                                                    <td></td>
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