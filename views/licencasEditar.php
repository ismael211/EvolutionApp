<?php

if (!isset($_POST['codigo'])) {
  // echo "<h1>Você não tem permissão para acessar esta página.</h1>";
  // exit();
}

header("Content-type: text/html; charset=utf-8");

session_start();
require_once('../inc/config.php');

include('../index.php');

include('nav.php');
include('side-bar.php');

$core = new IsistemCore();
$core->Connect();


$codigo = $_GET['i'];

$dados_licenca = $core->Fetch("SELECT * FROM `licenca` WHERE `id` = '" . $codigo . "'");

$dados_cliente = $core->Fetch("SELECT * FROM `clientes` WHERE `codigo` = '" . $dados_licenca['id_cliente'] . "'");

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
              <h2 class="content-header-title float-left mb-0">Licença</h2>
              <div class="breadcrumb-wrapper">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">Editar Licença
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


              <div class="row">
                <div class="col-lg-12">

                  <form role="form" id="enviaNovaLicenca">
                    <!-- Diferencia o editar do criar licença -->
                    <input type="hidden" name="cod_licenca" id="cod_licenca" value="<?= $codigo ?>">

                    <div class="form-group">
                      <label>Cliente</label>
                      <select class="form-control" id="clientef" name="clientef">
                        <option value="<?= $dados_cliente['codigo'] ?>"><?= $dados_cliente['nome'] ?></option>
                      </select>
                    </div>

                    <div class="form-group" id="tipo_cliente" style="display:none;">
                      <span id="resultado_tp_cli"></span>
                    </div>

                    <div class="form-group">
                      <label>Sub-domínio</label>
                      <input class="form-control" type="text" name="sub_dominio" id="sub_dominio" value="<?= $dados_licenca['sub_dominio'] ?>">
                    </div>

                    <div class="form-group">
                      <label>Key</label>
                      <input class="form-control key_mascara" type="text" name="key_sub" id="key_sub" value="<?= $dados_licenca['key_licenca'] ?>">
                    </div>

                    <div class="form-group">
                      <label>Status</label>
                      <select class="form-control" id="status_sub" name="status_sub">
                        <option value="0" selected="selected">Desativar</option>
                        <option value="1">Ativar</option>
                      </select>
                    </div>


                    <div class="well">
                      <button type="button" class="btn btn-primary" id="bt_editar_licenca">Editar</button>
                    </div>

                  </form>

                </div>

              </div><!-- /#page-wrapper -->

              <div class="card-footer">
                <h6>Painel Licença</h6>
              </div>
            </div>
          </div>
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

<!-- Editar Licença  -->
<script>
  $("#enviaNovaLicenca").submit(function(e) {
    // processando(1);
    var formObj = $(this);
    var formURL = "/views/cadastraLicenca.php";

    if (window.FormData !== undefined) // for HTML5 browsers
    //	if(false)
    {

      var formData = new FormData(this);
      $.ajax({
        url: formURL,
        type: 'POST',
        data: formData,
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {

          // processando(0);
          data = data.split("||");

          if (data[0] != 'error') {
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
                window.location.href = '/Licencas';
              }
            })
          } else {
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
          }
        }
      });
      e.preventDefault();
      e.unbind();
    } else //for olden browsers
    {
      //generate a random id
      var iframeId = 'unique' + (new Date().getTime());

      //create an empty iframe
      var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');

      //hide it
      iframe.hide();

      //set form target to iframe
      formObj.attr('target', iframeId);

      //Add iframe to body
      iframe.appendTo('body');
      iframe.load(function(e) {
        var doc = getDoc(iframe[0]);
        var docRoot = doc.body ? doc.body : doc.documentElement;
        var data = docRoot.innerHTML;
        $("#msg").html('');
      });
    }

  });
  $("#bt_editar_licenca").click(function() {
    $("#enviaNovaLicenca").submit();
  });
</script>