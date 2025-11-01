<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Login Page">
    <meta name="author" content="Your Company">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/favicon.ico">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center" style="height: 100vh;">

            <div class="col-lg-6 col-md-8">

                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-4">
                        <!-- Login Form -->
                        <div class="text-center mb-4">
                            <h1 class="h4 text-gray-900">Welcome Back!</h1>
                        </div>

                        <!-- Error Message -->
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger text-center">
                                <?= $this->session->flashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= site_url('auth/login') ?>" class="user" onsubmit="return validateForm()">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" 
                                    name="username" 
                                    id="username" 
                                    placeholder="Enter Username" 
                                    required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" 
                                    name="password" 
                                    id="password" 
                                    placeholder="Password" 
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Login
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <a class="small" href="#">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="#">Create an Account!</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url(); ?>assets/js/sb-admin-2.min.js"></script>

    <!-- Simple Client-Side Validation -->
    <script>
        function validateForm() {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                alert('Username dan Password harus diisi.');
                return false;
            }
            return true;
        }
    </script>

</body>

</html>
