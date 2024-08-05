<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('layouts/admin/meta-tag'); ?>
    <?php $this->load->view('layouts/headerlink'); ?>
</head>

<body>
    <main>
        <div id="wrapper" class="">
            <?php $this->load->view('layouts/admin/sidebar.php'); ?>
            <?php $this->load->view('layouts/admin/navbar.php'); ?>

            <section id="content-wrapper">
                <div class="row">
                    <div class="col-11 mx-auto" style="position: relative;">

                        <div class="col text-right">
                            <h1 class="content-title"><?php echo $title_h1; ?></h1>

                        </div>

                        <div class="col text-right">
                            <p class="pak-bup-top-right">
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-dp2kbp3a-kab-kediri-circle.png" width="62" />
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-mas-bup-merah-kab-kediri.png" width="75" />
                            </p>
                        </div>


                        <div class="row">
                            <div class="col-12 mx-auto my-5">
                                Selamat Datang !

                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <?php $this->load->view('layouts/footer'); ?>

</body>

</html>