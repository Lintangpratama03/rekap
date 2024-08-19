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
                            <div class="col-12 mx-auto">
                                <h6 class="mt-4">Berikut adalah detail jawaban dari pertanyaan <br><b>"<?= $pertanyaan['question'] ?>"</b> <br> Tipe : <b><?= $pertanyaan['type'] ?></b></h6>

                                <h3 class="mt-4">Cari Berdasarkan</h3>
                                <div class="row">
                                    <div class="col-2">
                                        <div class="form-floating">
                                            <select class="form-control selectpicker " id="dataKecamatan" name="dataKecamatan" data-live-search="true">
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
                                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-light w-50" style="background-color: #11152A; border: none; border-radius: 12px; margin-right: .5rem;">Telusuri</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="col-12 mx-auto">
                                <table id="table-data" class="display table mt-2">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>No.</th>
                                            <th>Pilihan Jawaban</th>
                                            <th>Total jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                                <div class="text-center ajax_status">
                                    <div class="spinner-border" role="status" id="loading">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <?php $this->load->view('layouts/footer'); ?>

    <script>
        var table = null;
        var currentRequest = null;
        var objectToPopulateDataKecamatan = <?php echo json_encode($list_kecamatan, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataDesa = <?php echo json_encode($list_desa, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataDusun = <?php echo json_encode($list_dusun, JSON_HEX_QUOT); ?>;

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

        $(document).ready(function() {
            console.log($('#dataKecamatan').val());
            loadData({
                id_question: '<?= $pertanyaan['id']; ?>',
                id_kecamatan: $('#dataKecamatan').val()
            });
        });

        function loadData(data) {
            var rowData = "";

            currentRequest = $.ajax({
                headers: {
                    "x-api-key": "<?php echo $this->session->userdata('x-api-key'); ?>",
                },
                data: data,
                url: "<?php echo base_url(); ?>api/detail_data_pertanyaan",
                type: 'post',
                dataType: 'json',
                success: function(data, status, xhr) {
                    let counter = 1;
                    for (let key in data) {
                        if (data.hasOwnProperty(key)) {
                            var item = data[key];
                            var total = item.total !== undefined && item.total !== null ? item.total : "-";
                            rowData += "<tr><td>" + counter + "</td><td>" + item.pilihan + "</td><td>" + total + "</td></tr>";
                            counter++;
                        }
                    }

                    $("#table-data tbody").append(rowData);

                    table = $('#table-data').DataTable({
                        columnDefs: [],
                        ordering: false,
                        searching: false,
                        paging: false,
                        info: false,
                        bDestroy: true,
                        dom: 'Bfrtip',
                    });

                    $(".ajax_status").hide();
                    currentRequest = null;
                }
            });
            return false;
        }
    </script>