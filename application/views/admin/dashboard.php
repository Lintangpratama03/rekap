<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('layouts/admin/meta-tag'); ?>
    <?php $this->load->view('layouts/headerlink'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

                        <div class="col-12 mx-auto my-5">
                            Selamat Datang !
                        </div>
                        <h3 class="mt-4">Cari Berdasarkan</h3>
                        <form id="mainForm" method="post">
                            <div class="row">
                                <div class="col-2">
                                    <div class="form-floating">
                                        <select class="form-control selectpicker " id="dataKecamatan" name="dataKecamatan" data-live-search="true">
                                            <option value="">Semua Kecamatan</option>
                                        </select>
                                        <label for="floatingSelect">Kecamatan</label>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-floating">
                                        <select class="form-control selectpicker" id="dataDesa" name="dataDesa" data-live-search="true">
                                            <option value="">Semua Desa</option>
                                        </select>
                                        <label for="floatingSelect">Desa</label>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-floating">
                                        <select class="form-control selectpicker" id="dataDusun" name="dataDusun" data-live-search="true">
                                            <option value="">Semua Dusun</option>
                                        </select>
                                        <label for="floatingSelect">Dusun</label>
                                    </div>
                                </div>

                                <div class="col-1">
                                    <label for="dataRt"></label>
                                    <input type="number" class="form-control" id="dataRt" name="dataRt" placeholder="RT" oninput="maxLengthCheck(this)" type="number" maxlength="3" min="1" max="999">
                                </div>

                                <div class="col-1">
                                    <label for="dataRw"></label>
                                    <input type="number" class="form-control" id="dataRw" name="dataRw" placeholder="RW" oninput="maxLengthCheck(this)" type="number" maxlength="3" min="1" max="999">
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-3 pt-3">
                                    <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-light w-50" type="submit" style="background-color: #11152A; border: none; border-radius: 12px; margin-right: .5rem;" onclick="filterData()">Telusuri</button>
                                    <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-dark" type="button" style="background-color: #FFA800; border: none; border-radius: 12px;" onclick="ExportToExcel('xlsx')">Cetak</button>
                                </div>
                            </div>
                        </form>

                        <!-- KK Sejahtera Bar Chart -->
                        <div class="col-12 my-4">
                            <h4>KK Sejahtera</h4>
                            <canvas id="kkSejahteraChart"></canvas>
                        </div>

                        <!-- KK Pra Sejahtera Bar Chart -->
                        <div class="col-12 my-4">
                            <h4>KK Pra Sejahtera</h4>
                            <canvas id="kkPraSejahteraChart"></canvas>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php $this->load->view('layouts/footer'); ?>

    <script>
        // Initialize Chart.js for KK Sejahtera
        const kkSejahteraCtx = document.getElementById('kkSejahteraChart').getContext('2d');
        const kkSejahteraChart = new Chart(kkSejahteraCtx, {
            type: 'bar',
            data: {
                labels: ['Total', 'KK Laki-Laki', 'KK Perempuan'],
                datasets: [{
                    label: 'Jumlah',
                    data: [0, 0, 0], // initial dummy data
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Initialize Chart.js for KK Pra Sejahtera
        const kkPraSejahteraCtx = document.getElementById('kkPraSejahteraChart').getContext('2d');
        const kkPraSejahteraChart = new Chart(kkPraSejahteraCtx, {
            type: 'bar',
            data: {
                labels: ['Total', 'KK Laki-Laki', 'KK Perempuan'],
                datasets: [{
                    label: 'Jumlah',
                    data: [0, 0, 0], // initial dummy data
                    backgroundColor: ['#f6c23e', '#e74a3b', '#858796'],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Function to filter data and update charts
        function filterData() {
            // You can get the selected filter values here
            const selectedKecamatan = document.getElementById('dataKecamatan').value;
            const selectedDesa = document.getElementById('dataDesa').value;
            const selectedDusun = document.getElementById('dataDusun').value;
            const selectedRt = document.getElementById('dataRt').value;
            const selectedRw = document.getElementById('dataRw').value;

            // Perform AJAX call or other logic to get filtered data
            // For demonstration purposes, we'll use dummy data
            const kkSejahteraData = {
                total: Math.floor(Math.random() * 100), 
                male: Math.floor(Math.random() * 50), 
                female: Math.floor(Math.random() * 50)
            };

            const kkPraSejahteraData = {
                total: Math.floor(Math.random() * 100),
                male: Math.floor(Math.random() * 50),
                female: Math.floor(Math.random() * 50)
            };

            // Update the KK Sejahtera chart with new data
            kkSejahteraChart.data.datasets[0].data = [kkSejahteraData.total, kkSejahteraData.male, kkSejahteraData.female];
            kkSejahteraChart.update();

            // Update the KK Pra Sejahtera chart with new data
            kkPraSejahteraChart.data.datasets[0].data = [kkPraSejahteraData.total, kkPraSejahteraData.male, kkPraSejahteraData.female];
            kkPraSejahteraChart.update();
        }
    </script>
</body>

</html>
