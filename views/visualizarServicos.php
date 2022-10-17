<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

include('../index.php');

include('../funcoes.php');

include('nav.php');
include('side-bar.php');


$core = new IsistemCore();
$core->Connect();

$qtd_serv = $core->RowCount("SELECT * FROM servicos_adicionais AS sa LEFT JOIN servicos_modelos AS sm ON sa.codigo_servico = sm.codigo
LEFT JOIN clientes as cli ON sa.codigo_cliente = cli.codigo");

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
    <title>DataTables</title>
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
                                    <li class="breadcrumb-item active">Visualizar Serviços
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
                                <div class="col-md-3">

                                    <div id="menu_opcoes" style="display: block;">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Opções
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <div class="container" style="margin-left: 10px; width: 190px;">

                                                    <div class="dropdown-item" style="cursor:pointer;" id="visualizar" class="opcoes"><i class="bi bi-circle-fill" style="color: green;"></i> Visualizar </div>
                                                    <br>                                                   
                                                    <div class="dropdown-item" style="cursor: pointer;" id="editar" class="opcoes"><i class="bi bi-circle-fill" style="color: yellow;"></i> Editar Fatura</div>
                                                    <br>
                                                    <div class="dropdown-item" style="cursor: pointer;" id="remover" class="opcoes"><i class="bi bi-circle-fill" style="color: red;"></i> Remover Cliente(s)</div>
                                                    <br>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-cogs"></i>Serviços</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th># <i class="fa fa-sort"></i></th>
                                                            <th>Responsável <i class="fa fa-sort"></i></th>
                                                            <th>Serviços <i class="fa fa-sort"></i></th>
                                                            <th>Parcelas <i class="fa fa-sort"></i></th>
                                                            <th>Repetir <i class="fa fa-sort"></i></th>
                                                            <th>Valor(R$) <i class="fa fa-sort"></i></th>
                                                            <th>Status <i class="fa fa-sort"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                        if ($qtd_serv > 0) {

                                                            $servicos = $core->FetchAll("SELECT sa.codigo as codigo_servico, sa.valor, sa.repetir, sa.periodo,
                                                            sa.parcela_atual, sa.total_parcelas, sa.descricao,
                                                            sm.codigo as codigo_modelo, sm.nome as nome_modelo, sm.descricao as descricao_modelo,
                                                            cli.codigo as codigo_cliente, cli.nome as nome_cliente, cli.email1, cli.email2,
                                                            cli.tipo_cliente, sa.status
                                                            FROM servicos_adicionais AS sa
                                                            LEFT JOIN servicos_modelos AS sm ON sa.codigo_servico = sm.codigo
                                                            LEFT JOIN clientes as cli ON sa.codigo_cliente = cli.codigo");

                                                            foreach ($servicos as $row) {
                                                                $nome = substr($row['nome_cliente'], 0, 30);
                                                        ?>
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" value="<?= $row['codigo_servico'] ?>" name="codigo_servico" id="codigo_servico">
                                                                        <input type="hidden" id="id_servico" name="id_servico" value="<?= $row['codigo_servico'] ?>">
                                                                    </td>
                                                                    <td><?= $nome ?></td>
                                                                    <td><?= utf8_encode($row['descricao']) ?></td>
                                                                    <td><?= $row['total_parcelas'] ?></td>

                                                                    <?php if ($row['repetir'] = 'sim') { ?>

                                                                        <td><span class="badge bg-success">Sim</span></td>
                                                                    <?php } else { ?>
                                                                        <td><span class="badge bg-danger">Não</span></td>
                                                                    <?php } ?>

                                                                    <td><?= 'R$ '.moneyFormatBD($row['valor']) ?></td>
                                                                    <td>
                                                                        <?php if ($row['status'] == '1') { ?>

                                                                            <span class="badge bg-success">Ativado</span>
                                                                        <?php } elseif ($row['status'] == '0') { ?>

                                                                            <span class="badge bg-danger">Desativado</span>
                                                                        <?php } ?>
                                                                    </td>

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

<!-- Traduzir tabela -->
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

<!-- Ações de opções -->
<script>
    var itens = '';

    $("input[name='codigo_cli[]']").change(function(e) {

        //$("#opt_editar").first().fadeIn("slow");

        itens = $("input[name='codigo_cli[]']:checked").map(function() {
            return $(this).val();
        }).get();
        // console.log(itens)

        if (itens.length == 0) {

            document.getElementById('opt_editar').style.display = 'none'
            if (isoption == true) {
                isoption = false;

            }

        } else if (itens.length > 1) {

            document.getElementById('opt_editar').style.display = 'none'

            $("#opt_editar").fadeOut("slow");
        } else if (itens.length == 1) {

            if (isoption == false) {
                $("#menu_opcoes").first().fadeIn("slow");
                isoption = true;
            }
        }
    });

    $("#ativar").click(function(e) {
        //console.log(itens[0]);
        if (itens.length == 0) {
            alert('Por favor, selecione algum cliente');
        } else {
            if (window.confirm("Deseja realmente ativar o(s) cliente(s)?")) {
                processando(1);
                $.post("/views/action.php", {
                        ativa: '0',
                        tipo: 'ativar',
                        codigo: itens
                    },
                    function(resposta) {
                        processando(0);

                        var data = resposta.split("||");

                        // Quando terminada a requisição

                        // Se a resposta é um erro
                        if (data[0] == 'error') {
                            Swal.fire({
                                title: 'Atenção',
                                html: 'As alterações não foram concluídas'.data[3],
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
                                html: 'Alterações feitas com sucesso',
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
                        }
                    }
                );
            }
        }
    });

    $("#desativar").click(function(e) {
        //console.log(itens[0]);
        if (itens.length == 0) {
            alert('Por favor, selecione algum cliente');
        } else {
            if (window.confirm("Deseja realmente desativar o(s) cliente(s)?")) {
                processando();
                $.post("/views/action.php", {
                        tipo: 'ativar',
                        codigo: itens
                    },
                    function(resposta) {
                        processando(0);

                        var data = resposta.split("||");

                        // Quando terminada a requisição

                        // Se a resposta é um erro
                        if (data[0] == 'error') {
                            Swal.fire({
                                title: 'Atenção',
                                html: 'As alterações não foram concluídas'.data[3],
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
                                html: 'Alterações feitas com sucesso',
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
                        }
                    }
                );
            }
        }
    });

    $("#editar").click(function(e) {
        //console.log(itens[0]);
        if (itens.length == 0) {
            alert('Por favor, selecione algum cliente');

        } else if (itens.length == 1) {
            if (window.confirm("Deseja realmente editar o cliente?")) {
                processando();
                $.post("/views/clientesEditar.php", {
                    codigo: itens
                })
            }
        } else {
            alert('Você só pode editar um cliente por vez')
        }
    });

    $("#remover").click(function(e) {
        //console.log(itens[0]);
        if (itens.length == 0) {
            alert('Por favor, selecione algum cliente');
        } else {
            if (window.confirm("Deseja realmente DELETAR o(s) cliente(s)?")) {
                processando();
                $.post("/views/action.php", {
                    tipo: 'remover',
                    codigo: itens
                }, function(resposta) {
                    processando(0);

                    var data = resposta.split("||");

                    // Quando terminada a requisição

                    // Se a resposta é um erro
                    if (data[0] == 'error') {
                        Swal.fire({
                            title: 'Atenção',
                            html: data[3],
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
                            html: data[3],
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
                    }
                });
            }
        }
    });
</script>