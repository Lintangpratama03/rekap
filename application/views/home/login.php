<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1920">
    <meta name="description" content="In Kelud - Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repellat assumenda, qui dolorem ea officia similique dignissimos nisi omnis, debitis nulla dolorum ratione voluptatem quo perferendis. Harum quam est dignissimos iure.">
    <meta name="author" content="rahman">
    <meta name="theme-color" content="#001822">

    <title><?php echo $title; ?></title>
    <link rel="icon" href="<?php echo base_url(); ?>assets/img/main/logo-plain-dp2kbp3a-kab-kediri.png" type="image/x-icon">
    <meta property="og:url" content="<?php echo base_url(); ?>" />
    <meta property="og:image" content="<?php echo base_url(); ?>assets/img/main/logo-dp2kbp3a-kab-kediri.png" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="In Kelud - Sistem Ke dan Lud" />
    <meta property="og:description" content="In Kelud - Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repellat assumenda, qui dolorem ea officia similique dignissimos nisi omnis, debitis nulla dolorum ratione voluptatem quo perferendis. Harum quam est dignissimos iure." />
    <meta property="og:site_name" content="sikelud.go.id" />
    <link rel='canonical' href='https://sikelud.go.id/' />

    <!-- <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet"> -->


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" />

    <link href="<?php echo base_url(); ?>assets/css/custom_style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/font-awesome/all.min.css" rel="stylesheet">
</head>

<body>
    <main>
        <div class="container-fluid  ">
            <div class="row justify-content-center" style="height:100vh;    background: gainsboro;">
                <div class="col-12 col-md-7 row bg-white my-auto rounded" style="min-height: 737px;">
                    <div class="col-4 bg-image mx-auto m-5">
                        <img src="<?php echo base_url(); ?>assets/img/main/login-side-image.svg" style="width: 100%; height:100%" />
                    </div>

                    <div class="col-8 row p-5 bg-dark text-light" style="position: relative;">

                        <div class="col-8 mx-auto my-auto">
                            <div class="text-right">
                                <p class="pak-bup-top-right">
                                    <img src="<?php echo base_url(); ?>assets/img/main/logo-mas-bup-merah-kab-kediri.png" width="75" />
                                </p>
                            </div>
                            <h3 class="login-heading mb-4">Selamat Datang !</h3>
                            <form action="<?php echo base_url(); ?>auth" method="post">

                                <div class="form-floating mb-3 text-dark">
                                    <input type="text" class="form-control" id="floatingInput" name="name" placeholder="name@example.com">
                                    <label for="floatingInput">Nama User</label>
                                </div>
                                <div class="form-floating mb-3 text-dark">
                                    <input type="password" class="form-control" id="floatingPassword" name="pass" placeholder="Password">
                                    <label for="floatingPassword">Kata Sandi</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                                    <label class="form-check-label" for="rememberPasswordCheck">
                                        Simpan data saya
                                    </label>
                                </div>

                                <div class="d-grid">
                                    <?php if (isset($message)) echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $message . ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> </div>'; ?>
                                    <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-dark" type="submit" style="background-color: #FFA800; border: none;">Masuk</button>
                                    <div class="text-center">
                                        <!-- <a class="small text-light" href="#" id="">Lupa Kata Sandi?</a> -->
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <!-- <footer>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</footer> -->

    <script src="<?php echo base_url(); ?>assets/js/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/font-awesome/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

    <script>

    </script>

</body>

</html>