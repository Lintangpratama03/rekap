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
                                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-dark" type="button" style="background-color: #FFA800; border: none; border-radius: 12px;" onclick="ExportToExcel('xlsx')">Cetak</button>

                                    </div>
                                </div>
                            </form>
                        </div>



                        <div class="col text-right">
                            <p class="pak-bup-top-right">
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-dp2kbp3a-kab-kediri-circle.png" width="62" />
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-mas-bup-merah-kab-kediri.png" width="75" />
                            </p>
                        </div>


                        <div class="row">
                            <div class="col-12 mx-auto my-5">
                                <p>Tampilkan <select id="dataPerPage">
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select> Data Per Halaman </p>

                                <table id="resultFilter" class="display table my-3">
                                    <thead class="table-dark text-center">
                                        <tr id="removeWhenGenerateExcelBecauseItCannotHanddleRowspanAndColspan">
                                            <th style="vertical-align : middle;text-align:center">&nbsp;</th>
                                            <th style="vertical-align : middle;text-align:center">&nbsp;</th>
                                            <th style="vertical-align : middle;text-align:center">&nbsp;</th>
                                            <th style="vertical-align : middle;text-align:center">&nbsp;</th>
                                            <th colspan="3" style="text-align: center; background: #23899e">Jumlah KK Sejahtera</th>
                                            <th colspan="3" style="text-align: center; background: #ff4973;">Jumlah KK Pra Sejahtera</th>
                                        </tr>

                                        <tr>
                                            <th style=" vertical-align : middle;text-align:center">Id</th>
                                            <th style="vertical-align : middle;text-align:center">No</th>
                                            <th style="vertical-align : middle;text-align:center">Nama Daerah</th>
                                            <th style="vertical-align : middle;text-align:center">KK Terdata</th>
                                            <th>Total</th>
                                            <th>KK Perempuan</th>
                                            <th>KK Laki Laki</th>
                                            <th>Total</th>
                                            <th>KK Perempuan</th>
                                            <th>KK Laki Laki</th>
                                        </tr>

                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <div class="text-center" id="ajax_status">
                                    <div class="spinner-border" role="status" id="loading">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                                <div class="col-12 paginationUnderResultTable" id="pagination">

                                </div>
                                <p id="pagination_text" class="text-muted lead text-center" style="font-size: 0.75rem;"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <?php $this->load->view('layouts/footer'); ?>

    <script>
        $('#pagination').on('click', 'a', function(e) {
            e.preventDefault();
            pageno = $(this).attr('href');
            var parts = pageno.split('/');
            var pageno_after = parts[parts.length - 1];
            pageno = parseInt(pageno_after);

            data.page = pageno;
            console.log("data : " + JSON.stringify(data));
            if (pageno == null || isNaN(pageno)) {
                data.page = 1;
                loadDataWithFilter(0, data);
            } else if (pageno > 0 && pageno !== null) {
                loadDataWithFilter(pageno, data);
            } else {
                pageno = +pageno || 1;

                loadDataWithFilter(pageno, data);
            }
        });

        // DECLARE GLOBAL VARIABLE
        var table = null;
        var currentRequest = null;
        var objectToPopulateDataKecamatan = <?php echo json_encode($list_kecamatan, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataDesa = <?php echo json_encode($list_desa, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataDusun = <?php echo json_encode($list_dusun, JSON_HEX_QUOT); ?>;
        var objectToPopulateDataStatusKeluarga = <?php echo json_encode($list_status_keluarga, JSON_HEX_QUOT); ?>;
        var oneRowData = "";

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

        $('#pagination').on('click', 'a', function(e) {
            e.preventDefault();
            pageno = $(this).attr('href');
            var parts = pageno.split('/');
            var pageno_after = parts[parts.length - 1];
            pageno = parseInt(pageno_after);

            data.page = pageno;
            console.log("data : " + JSON.stringify(data));
            if (pageno == null || isNaN(pageno)) {
                data.page = 1;
                loadDataWithFilter(0, data);
            } else if (pageno > 0 && pageno !== null) {
                loadDataWithFilter(pageno, data);
            } else {
                pageno = +pageno || 1;

                loadDataWithFilter(pageno, data);
            }
        });

        // DECLARE GLOBAL VARIABLE
        var data = {
            "id_kecamatan": $('#dataKecamatan').val(),
            "id_desa": $('#dataDesa').val(),
            "rt": $('#dataRt').val(),
            "rw": $('#dataRw').val(),
            "data_per_page": $('#dataPerPage').val(),
            "page": 1
        };



        $(document).ready(function() {
            $('#dataKecamatan').trigger("change");
            loadDataWithFilter(data.page, data);
        });

        function loadDataWithFilter(pageno, data) {
            if (currentRequest) {
                currentRequest.abort();
            }

            $("#resultFilter > tbody").empty();
            $("#pagination_text").empty();
            $(".paginationUnderResultTable").empty();

            $("#resultFilter > tr").remove();
            $('#resultFilter').DataTable().clear().destroy();
            $("#ajax_status").show();
            var oneRowData = "";
            var dynamicHeaderForDaerah = '';


            currentRequest = $.ajax({
                headers: {
                    "x-api-key": "<?php echo $this->session->userdata('x-api-key'); ?>",
                },
                data: data,
                url: "<?php echo base_url(); ?>api/list_rekapitulasi_kskps/" + (pageno - 1),
                type: 'post',
                dataType: 'json',
                success: function(data, status, xhr) {

                    let total = 0;
                    let sejahtera = 0;
                    let kk_wanita_sejahtera = 0;
                    let kk_pria_sejahtera = 0;
                    let pra_sejahtera = 0;
                    let kk_wanita_pra = 0;
                    let kk_pria_pra = 0;

                    for (let key in data[0].filtered_data) {
                        if (data[0].filtered_data.hasOwnProperty(key)) {
                            var data_detail = data[0].filtered_data[key];

                            oneRowData += "<tr>";
                            oneRowData += "    <td>" + data_detail.id + "</td>";
                            oneRowData += "    <td>" + data_detail.nomer_tabel + "</td>";



                            if ($('#dataDusun').val() != '') {
                                dynamicHeaderForDaerah = "RT/RW";
                                oneRowData += "    <td>" + data_detail.rt + "/" + data_detail.rw + "</td>";
                            } else if ($('#dataDesa').val() != '') {
                                dynamicHeaderForDaerah = "Dusun";
                                oneRowData += "    <td>" + data_detail.dusun + "</td>";
                            } else if ($('#dataKecamatan').val() != '') {
                                dynamicHeaderForDaerah = "Desa";
                                oneRowData += "    <td>" + data_detail.desa + "</td>";
                            } else {
                                dynamicHeaderForDaerah = "Kecamatan";
                                oneRowData += "    <td>" + data_detail.kecamatan + "</td>";
                            }
                            oneRowData += "    <td>" + data_detail.total + "</td>";
                            oneRowData += "    <td>" + data_detail.sejahtera + "</td>";
                            oneRowData += "    <td>" + data_detail.kk_wanita_sejahtera + "</td>";
                            oneRowData += "    <td>" + data_detail.kk_pria_sejahtera + "</td>";
                            oneRowData += "    <td>" + data_detail.pra_sejahtera + "</td>";
                            oneRowData += "    <td>" + data_detail.kk_wanita_pra + "</td>";
                            oneRowData += "    <td>" + data_detail.kk_pria_pra + "</td>";
                            oneRowData += "</tr>";

                            total += parseInt(data_detail.total);
                            sejahtera += parseInt(data_detail.sejahtera);
                            kk_wanita_sejahtera += parseInt(data_detail.kk_wanita_sejahtera);
                            kk_pria_sejahtera += parseInt(data_detail.kk_pria_sejahtera);
                            pra_sejahtera += parseInt(data_detail.pra_sejahtera);
                            kk_wanita_pra += parseInt(data_detail.kk_wanita_pra);
                            kk_pria_pra += parseInt(data_detail.kk_pria_pra);

                        }
                    }
                    oneRowData += "<tr>";
                    oneRowData += "    <td></td>";
                    oneRowData += "    <td></td>";
                    oneRowData += "    <td><b>TOTAL DATA</b></td>";
                    oneRowData += "    <td>" + total + "</td>";
                    oneRowData += "    <td>" + sejahtera + "</td>";
                    oneRowData += "    <td>" + kk_wanita_sejahtera + "</td>";
                    oneRowData += "    <td>" + kk_pria_sejahtera + "</td>";
                    oneRowData += "    <td>" + pra_sejahtera + "</td>";
                    oneRowData += "    <td>" + kk_wanita_pra + "</td>";
                    oneRowData += "    <td>" + kk_pria_pra + "</td>";
                    oneRowData += "</tr>";



                    for (let key in data[0].pagination_data) {
                        if (data[0].pagination_data.hasOwnProperty(key)) {
                            var data_detail = data[0].pagination_data[key];
                            $("#pagination_text").html(data_detail.data_shown_to_user);
                            $(".paginationUnderResultTable").html(data_detail.html_data);
                        }
                    }

                    $("#resultFilter tbody").append(oneRowData);


                    table = $('#resultFilter').DataTable({
                        columnDefs: [{
                            className: 'dt-center',
                            targets: '_all'
                        }, {
                            target: 0,
                            visible: false,
                            searchable: false
                        }, {
                            title: dynamicHeaderForDaerah,
                            targets: 2
                        }],
                        ordering: false,
                        searching: false,
                        paging: false,
                        info: false,
                        bDestroy: true,
                    });

                    $("#ajax_status").hide();
                    currentRequest = null;
                }
            });


            return false;
        }

        $("#mainForm").on("submit", function(event) {
            data = {
                "id_kecamatan": $('#dataKecamatan').val(),
                "id_desa": $('#dataDesa').val(),
                "id_dusun": $('#dataDusun').val(),
                "rt": $('#dataRt').val(),
                "rw": $('#dataRw').val(),
                "data_per_page": $('#dataPerPage').val(),
                "page": 1
            };

            loadDataWithFilter(1, data);
            return false;
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

        $('#dataDusun').on('change', function(e) {
            console.log(this.value);
            console.log($("#dataDusun option:selected").text());
            console.log($("#dataDusun option:selected").data("desa-id"));
        });
    </script>

</body>

</html>