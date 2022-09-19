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
  <link rel="stylesheet" type="text/css" href="../app-assets/css/plugins/forms/form-validation.css">
  <link rel="stylesheet" type="text/css" href="../app-assets/css/pages/page-auth.css">
  <!-- END: Page CSS-->

  <!-- BEGIN: Custom CSS-->
  <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
  <!-- END: Custom CSS-->


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">


  <!-- BEGIN: JavaScript -->
  <script src="/public/js/jquery-1.10.2.js"></script>
  <script src="/public/js/bootstrap.js"></script>
  <script src="/public/js/jquery.maskedinput.js"></script>

  <script src="/public/js/funcoes.js"></script>
  <!-- END: JavaScript-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
  <!-- BEGIN: Content-->
  <div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">
        <div class="auth-wrapper auth-v1 px-2">
          <div class="auth-inner py-2">
            <!-- Login v1 -->
            <div class="card mb-0">
              <div class="card-body">

                <div class="divider my-2">
                  <div class="divider-text">
                    <h1>Login</h1>
                  </div>
                </div>

                <form class="auth-login-form mt-2" role="form" action="javascript:func()" method="POST">
                  <div class="form-group">
                    <label for="login-email" class="form-label">Usuario</label>
                    <input type="text" class="form-control" placeholder="Usuário" name="username" id="username" aria-describedby="login-email" tabindex="1" autofocus />
                  </div>

                  <div class="form-group">
                    <div class="d-flex justify-content-between">
                      <label for="login-password">Senha</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                      <input class="form-control form-control-merge" placeholder="Senha" name="password" id="password" type="password" value="" tabindex="2" aria-describedby="login-password" />
                      <div class="input-group-append">
                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                      </div>
                    </div>
                  </div>
                  <button class="btn btn-primary btn-block" id="login_bt" tabindex="4">Entrar</button>
                </form>

              </div>
            </div>
            <!-- /Login v1 -->
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- END: Content-->


  <!-- BEGIN: Vendor JS-->
  <script src="../app-assets/vendors/js/vendors.min.js"></script>
  <!-- BEGIN Vendor JS-->

  <!-- BEGIN: Page Vendor JS-->
  <script src="../app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
  <!-- END: Page Vendor JS-->

  <!-- BEGIN: Theme JS-->
  <script src="../app-assets/js/core/app-menu.js"></script>
  <script src="../app-assets/js/core/app.js"></script>
  <!-- END: Theme JS-->

  <!-- BEGIN: Page JS-->
  <script src="../app-assets/js/scripts/pages/page-auth-login.js"></script>
  <!-- END: Page JS-->

  <!-- BEGIN: Page JS-->
  <script src="../../app-assets/js/scripts/extensions/ext-component-blockui.js"></script>
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
</body>
<!-- END: Body-->

</html>

<script>
    $('#logino').on('click', function () {
      $.blockUI({
        message: '<div class="spinner-border text-primary" role="status">Processando...</div>',
        timeout: 1000,
        css: {
          backgroundColor: 'transparent',
          border: '0'
        },
        overlayCSS: {
          backgroundColor: '#fff',
          opacity: 0.8
        }
      });
    });
</script>




<!--


  Modal quitar fatura com prazo
    <div class="modal fade" id="modal_processando" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">

          </div>
          <div class="modal-body">
            <span id="status_erro"></span>
            <center><img src="/public/images/loader.gif" width="100px" /></center>
            

          </div>
          <div class="modal-footer">

          </div>
        </div> /.modal-content 
      </div>< /.modal-dialog 
    </div> /.modal 

  </body>

  </html>
-->