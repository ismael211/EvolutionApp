<?php

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

$core = new IsistemCore();
$core->Connect();

$cod_adm = $_SESSION["codigo_adm"];

$operador = $core->Fetch("SELECT * FROM `operadores` WHERE `codigo` = '".$cod_adm."'");

if ($operador['layout'] == 0) {
    $tema = '';
    $icone_tema = 'moon';
} else {
    $tema = 'dark-layout';
    $icone_tema = 'sun';
}

$menu = $operador['menu'];

?>


<script src="../funcoes.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">

        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
            <style>
                #icon_nav_top:hover {
                    -ms-transform: scale(1.3);
                    /* IE 9 */
                    -webkit-transform: scale(1.3);
                    /* Safari 3-8 */
                    transform: scale(1.3);
                }
            </style>
            <ul class="nav navbar-nav bookmark-icons">

                <li class="nav-item d-none d-lg-block" style="margin-top: -5px; margin-left: 30px ;">
                </li>

                <li class="nav-item d-none d-lg-block" style="margin-top: -5px;">
                    <ul class="search-list search-list-main" style="position: absolute; display: none ;" id="Resultado_Pesquisa">
                        <div class="card border border-primary">
                            <div class="row">
                                <div class="col-md-12" style="margin-top: 10px; ">
                                    <div id="descricao" style="width: auto; overflow: auto; max-height:300px; ">
                                        <br>
                                        <span id="contador">
                                        </span>
                                        <span id="descricao">
                                        </span>
                                        <br>
                                    </div>
                                    <div id="ver_mas" style="text-align: center; margin-bottom: 10px ; margin-top: 10px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>


        <ul class="nav navbar-nav align-items-center ml-auto">
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i id="<?= $tema ?>" class="ficon tema" data-feather="<?= $icone_tema ?>" onclick="mudartema(this.id)"></i></a>
            <li class="nav-item dropdown dropdown-notification mr-25"><a class="nav-link" href="javascript:void(0);" data-toggle="dropdown"><i class="ficon" data-feather="bell"></i></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 mr-auto">Notificações</h4>
                        </div>
                    </li>
                    <li class="scrollable-container media-list">
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <h6>secondary</h6>
                                </div>
                                <div class="media-body">
                                    <div class="badge bg-secondary">secondary</div>
                                </div>
                            </div>
                        </a>
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <h6>primary</h6>
                                </div>
                                <div class="media-body">
                                    <div class="badge bg-primary">primary</div>
                                </div>
                            </div>
                        </a>
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <h6>success</h6>
                                </div>
                                <div class="media-body">
                                    <div class="badge bg-success">success</div>
                                </div>
                            </div>
                        </a>
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <h6>info</h6>
                                </div>
                                <div class="media-body">
                                    <div class="badge bg-info">info</div>
                                </div>
                            </div>
                        </a>
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <h6>warning</h6>
                                </div>
                                <div class="media-body">
                                    <div class="badge bg-warning">warning</div>
                                </div>
                            </div>
                        </a>
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <h6>danger</h6>
                                </div>
                                <div class="media-body">
                                    <div class="badge bg-danger">danger</div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="dropdown-menu-footer"><a class="btn btn-primary btn-block" href="#">Visualizar todos</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">Admin</span></div><span class="avatar"><img class="round" src="../app-assets/images/portrait/small/user.png" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user"><a class="dropdown-item" href="#"><i class="mr-50" data-feather="user"></i> Perfil</a><a class="dropdown-item" href="#"><i class="mr-50" data-feather="settings"></i> Configurações</a><a class="dropdown-item" href="/Sair"><i class="mr-50" data-feather="power"></i> Sair</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Tema - Dark/Light -->
<script>
    function mudartema(id) {

        var tema = id

        if (tema == 'dark-layout') {
            mudar_tema = ' ';
            icone = 'moon'

        } else {
            mudar_tema = '0';
            icone = 'sun'
        }
        var dados = {
            tema: mudar_tema
        };


        $.post('/tema.php', dados, function(rest) {
            console.log(rest)
            //location.reload();
        });

    }
</script>

<!-- Posição da barra lateral -->
<script>
    function mudar_menu(ret) {

        var cod = '<?= $_SESSION['codigo_adm'] ?>';

        var dados = {
            menu: ret,
            codigo: cod
        }

        //Salvando o menu no banco de dados
        $.post('/sidebar_pin.php', dados, function(volta) {
            console.log(volta)
        });

    }
</script>