<!DOCTYPE html>
<html lang="es">
    <head>
        <title>LexControl</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="author" content="Clover Team SAS" />

        <!-- Favicon icon -->
        <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
        <!-- fontawesome icon -->
        <link rel="stylesheet" href="assets/fonts/fontawesome/css/fontawesome-all.min.css">
        <!-- animation css -->
        <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">

        <!-- vendor css -->
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body style="background-color: #18232e;">
        <div class="auth-wrapper">
            <div class="auth-content container">
                <div class="card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="card-body">
                                <img src="assets/images/logoHorizontal_300.png" style="position: relative; margin: 0 80px 15px;" alt="Clover Team" title="Clover team"/>
                                <p>Bienvenido al sistema integral de gestión. Digite sus credenciales para acceder.</p>
                                    <div class="toggle-block">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Ingrese el usuario:</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Usuario">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Ingrese el password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                        </div>
                                        <div id="response" class="form-group mb-3"></div>
                                        <input type="hidden" id="rest" name="rest" value="<?php echo API; ?>">
                                        <button id="login-btn" class="btn btn-primary mb-4 btf">Entrar</button>
                                        
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-none d-md-block">
                            <img src="assets/images/lexControlImg.png" alt=""
                                class="img-fluid bd-placeholder-img bd-placeholder-img-lg d-block w-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- [ auth-signin ] end -->

    <!-- Required Js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/login.js"></script>
    </body>
</html>
