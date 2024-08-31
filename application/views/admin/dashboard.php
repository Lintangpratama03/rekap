<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('layouts/admin/meta-tag'); ?>
    <?php $this->load->view('layouts/headerlink'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                    <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-light w-50" type="submit" style="background-color: #11152A; border: none; border-radius: 12px; margin-right: .5rem;">Telusuri</button>
                                    <!-- <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-dark" type="button" style="background-color: #FFA800; border: none; border-radius: 12px;" onclick="ExportToExcel('xlsx')">Cetak</button> -->
                                </div>
                            </div>
                        </form>

                        <!-- KK Sejahtera Bar Chart -->
                        <div class="col-12 my-4">
                            <h4>KK Sejahtera</h4>
                            <div id="chart-sejahtera"></div>
                        </div>

                        <!-- KK Pra Sejahtera Bar Chart -->
                        <div class="col-12 my-4">
                            <h4>KK Pra Sejahtera</h4>
                            <div id="chart-pra-sejahtera"></div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php $this->load->view('layouts/footer'); ?>

    <script>
        var objectToPopulateDataKecamatan = <?php echo json_encode($list_kecamatan, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataDesa = <?php echo json_encode($list_desa, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataDusun = <?php echo json_encode($list_dusun, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataStatusKeluarga = <?php echo json_encode($list_status_keluarga, JSON_HEX_QUOT); ?>;

        // POPULATE SELECT DATA FROM PHP CONTROLLER
        $.each(objectToPopulateDataKecamatan, function(index, item) {
            if (item.kecamatan == '<?php echo strtoupper($this->session->userdata('user_name')); ?>') {
                $('#dataKecamatan').append($('<option>', {
                    value: item.id,
                    text: item.kecamatan,
                    selected: true
                }));


            } else {
                $('#dataKecamatan').append($('<option>', {
                    value: item.id,
                    text: item.kecamatan
                }));
            }
        });

        $.each(objectToPopulateDataDesa, function(index, item) {
            $('#dataDesa').append($('<option>', {
                value: item.id,
                text: item.desa,
                'data-kecamatan-id': item.id_kecamatan
            }));
        });

        $.each(objectToPopulateDataDusun, function(index, item) {
            $('#dataDusun').append($('<option>', {
                value: item.id,
                text: item.dusun,
                'data-desa-id': item.id_desa
            }));
        });

        $('#dataKecamatan').on('change', function(e) {
            const id_kecamatan = this.value
            $('#dataDesa').empty();
            $('#dataDesa').append($('<option>', {
                value: '',
                text: 'Semua Desa'
            }));

            if (id_kecamatan == 0) {
                $.each(objectToPopulateDataDesa, function(index, item) {
                    $('#dataDesa').append($('<option>', {
                        value: item.id,
                        text: item.desa,
                        'data-kecamatan-id': item.id_kecamatan
                    }));
                });


                $.each(objectToPopulateDataDusun, function(index, item) {
                    $('#dataDusun').append($('<option>', {
                        value: item.id,
                        text: item.dusun,
                        'data-desa-id': item.id_desa
                    }));
                });
            } else {
                $.each(objectToPopulateDataDesa, function(index, item) {
                    if (item.id_kecamatan == id_kecamatan) {
                        $('#dataDesa').append($('<option>', {
                            value: item.id,
                            text: item.desa,
                            'data-kecamatan-id': item.id_kecamatan
                        }));
                    }
                });
            }
            $('#dataDesa').selectpicker('refresh');
        });


        $('#dataDesa').on('change', function(e) {
            const id_desa = this.value
            $('#dataDusun').empty();
            $('#dataDusun').append($('<option>', {
                value: '',
                text: 'Semua Dusun'
            }));

            if (id_desa == 0) {
                $.each(objectToPopulateDataDusun, function(index, item) {
                    $('#dataDusun').append($('<option>', {
                        value: item.id,
                        text: item.dusun,
                        'data-desa-id': item.id_desa
                    }));
                });
            } else {
                $.each(objectToPopulateDataDusun, function(index, item) {
                    if (item.id_desa == id_desa) {
                        $('#dataDusun').append($('<option>', {
                            value: item.id,
                            text: item.dusun,
                            'data-desa-id': item.id_desa
                        }));
                    }
                });
            }
            $('#dataDusun').selectpicker('refresh');
        });

        // DECLARE GLOBAL VARIABLE
        var data = {
            "id_kecamatan": $('#dataKecamatan').val(),
            "id_desa": $('#dataDesa').val(),
            "id_dusun": $('#dataDusun').val(),
            "rt": $('#dataRt').val(),
            "rw": $('#dataRw').val(),
        };

        $(document).ready(function() {
            var kecamatan = $('#dataKecamatan').val();
            var desa = $('#dataDesa').val();
            var dusun = $('#dataDusun').val();
            var rt = $('#dataRt').val();
            var rw = $('#dataRw').val();
            filter_grafik_sejahtera(kecamatan, desa, dusun, rt, rw);
            filter_grafik_pra(kecamatan, desa, dusun, rt, rw);
        });

        $("#mainForm").on("submit", function(event) {
            var kecamatan = $('#dataKecamatan').val();
            var desa = $('#dataDesa').val();
            var dusun = $('#dataDusun').val();
            var rt = $('#dataRt').val();
            var rw = $('#dataRw').val();
            filter_grafik_sejahtera(kecamatan, desa, dusun, rt, rw);
            filter_grafik_pra(kecamatan, desa, dusun, rt, rw);
            return false;
        });

        function filter_grafik_sejahtera(kecamatan, desa, dusun, rt, rw) {
            $("#chart-sejahtera").empty();
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "<?= base_url(); ?>Home/grafik_sejahtera",
                data: {
                    "kecamatan": kecamatan,
                    "desa": desa,
                    "dusun": dusun,
                    "rt": rt,
                    "rw": rw,
                },
                cache: false,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                processData: true,
                success: function(result) {
                    var pria = result.kk_pria_sejahtera;
                    var wanita = result.kk_wanita_sejahtera;
                    var total = result.total;

                    chart_sejahtera(pria, wanita, total);
                },
                error: function(result) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },
            });
        }

        function filter_grafik_pra(kecamatan, desa, dusun, rt, rw) {
            $("#chart-pra-sejahtera").empty();
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "<?= base_url(); ?>Home/grafik_pra",
                data: {
                    "kecamatan": kecamatan,
                    "desa": desa,
                    "dusun": dusun,
                    "rt": rt,
                    "rw": rw,
                },
                cache: false,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                processData: true,
                success: function(result) {
                    var pria = result.kk_pria_pra;
                    var wanita = result.kk_wanita_pra;
                    var total = result.total;

                    chart_pra_sejahtera(pria, wanita, total);
                },
                error: function(result) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },
            });
        }

        function chart_sejahtera(pria, wanita, total) {
            var options = {
                series: [{
                    data: [total, wanita, pria]
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            var names = ['Total KK', 'KK Perempuan', 'KK Laki-laki'];
                            var selectedName = names[config.dataPointIndex];
                            var selectedValue = config.w.config.series[0].data[config.dataPointIndex].toLocaleString();
                            Swal.fire(selectedName + ": " + selectedValue);
                        }
                    }
                },
                colors: ['#4c4c4c', '#f44336', '#ff9800', '#ffeb3b', '#4caf50', '#4caf50'],
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: [
                        ['Total', 'KK'],
                        ['KK', 'Perempuan'],
                        ['KK', 'Laki-laki'],
                    ],
                    labels: {
                        style: {
                            colors: ['#001822'],
                            fontSize: '20px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value.toLocaleString();
                        },
                        style: {
                            colors: ['#001822'],
                            fontSize: '16px'
                        }
                    }
                },
                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        var names = ['Total KK', 'KK Perempuan', 'KK Laki-laki'];
                        var value = series[seriesIndex][dataPointIndex].toLocaleString();
                        return names[dataPointIndex] + ': ' + value;
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart-sejahtera"), options);
            chart.render();
        }


        function chart_pra_sejahtera(pria, wanita, total) {
            var options = {
                series: [{
                    data: [total, wanita, pria]
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            var names = ['Total KK', 'KK Perempuan', 'KK Laki-laki'];
                            var selectedName = names[config.dataPointIndex];
                            var selectedValue = config.w.config.series[0].data[config.dataPointIndex].toLocaleString();
                            Swal.fire(selectedName + ": " + selectedValue);
                        }
                    }
                },
                colors: ['#4c4c4c', '#f44336', '#ff9800', '#ffeb3b', '#4caf50', '#4caf50'],
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: [
                        ['Total', 'KK'],
                        ['KK', 'Perempuan'],
                        ['KK', 'Laki-laki'],
                    ],
                    labels: {
                        style: {
                            colors: ['#001822'],
                            fontSize: '20px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value.toLocaleString();
                        },
                        style: {
                            colors: ['#001822'],
                            fontSize: '16px'
                        }
                    }
                },
                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        var names = ['Total KK', 'KK Perempuan', 'KK Laki-laki'];
                        var value = series[seriesIndex][dataPointIndex].toLocaleString();
                        return names[dataPointIndex] + ': ' + value;
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart-pra-sejahtera"), options);
            chart.render();
        }
    </script>
</body>

</html>