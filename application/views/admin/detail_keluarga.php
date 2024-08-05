<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('layouts/admin/meta-tag'); ?>
    <?php $this->load->view('layouts/headerlink'); ?>
    <style>
        #resultFilter>thead>tr {
            display: none;
        }
    </style>
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
                            <p class="pak-bup-top-right">
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-dp2kbp3a-kab-kediri-circle.png" width="62" />
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-mas-bup-merah-kab-kediri.png" width="75" />
                            </p>
                        </div>


                        <div class="row">
                            <div class="col-lg-6 mx-auto mt-2">
                                <h3 class="content-title text-center my-3"><?php echo $title_h1; ?></h3>

                                <div class="text-center ajax_status">
                                    <div class="spinner-border" role="status" id="loading">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                                <table id="resultFilter" class="display table mt-5">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="mt-4">
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-11 mx-auto">

                                <table id="resultFilterQuestionnaire" class="display table mt-5">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>No. </th>
                                            <th>Pertanyaan</th>
                                            <th>Jawaban</th>
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
                            <div class="col-6 pt-3 mx-auto text-center">
                                <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-light w-50" type="button" style="background-color: #11152A; border: none; border-radius: 12px; margin-right: .5rem;">Kembali</button>
                                <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-dark" type="button" style="background-color: #FFA800; border: none; border-radius: 12px;" onclick="ExportToExcel('xlsx')">Cetak</button>

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

        $(document).ready(function() {
            loadDataWithFilter({
                id_keluarga: '<?php echo $id_keluarga; ?>'
            });
        });

        function loadDataWithFilter(data) {
            $("#resultFilter > tbody").empty();
            $("#pagination_text").empty();
            $(".paginationUnderResultTable").empty();

            $("#resultFilter > tr").remove();
            // $('#resultFilter').DataTable().clear().destroy();
            $(".ajax_status").show();
            var oneRowData = "";
            var oneRowDataKuisioner = "";

            currentRequest = $.ajax({
                headers: {
                    "x-api-key": "<?php echo $this->session->userdata('x-api-key'); ?>",
                },
                data: data,
                url: "<?php echo base_url(); ?>api/detail_data_kskps",
                type: 'post',
                dataType: 'json',
                success: function(data, status, xhr) {
                    for (let key in data[0].keluarga) {
                        if (data[0].keluarga.hasOwnProperty(key)) {
                            var data_detail = data[0].keluarga[key];

                            oneRowData += "<tr><td>1</td><td>Nama Kepala Keluarga</td><td>:</td> <td>" + data_detail.name_kk + "</td></tr>";
                            if (data_detail['nik'] === null) {
                                oneRowData += "<tr><td>2</td><td>NIK</td><td>:</td> <td><span class='text-black'>" + "Tidak Ada NIK" + "</span></td></tr>";
                            } else {
                                oneRowData += "<tr><td>2</td><td>NIK</td><td>:</td> <td><span class='text-black'>" + data_detail.nik + "</span></td></tr>";
                            }
                            oneRowData += "<tr><td>3</td><td>Jenis Kelamin</td><td>:</td> <td>" + data_detail.gender_kk + "</td></tr>";
                            oneRowData += "<tr><td>4</td><td>Kecamatan</td><td>:</td> <td>" + data_detail.kecamatan + "</td></tr>";
                            oneRowData += "<tr><td>5</td><td>Desa</td><td>:</td> <td>" + data_detail.desa + "</td></tr>";
                            oneRowData += "<tr><td>6</td><td>Dusun</td><td>:</td> <td>" + data_detail.desa + "</td></tr>";
                            oneRowData += "<tr><td>7</td><td>RT / RW</td><td>:</td> <td>" + parseInt(data_detail.rt) + " / " + parseInt(data_detail.rw) + "</td></tr>";
                            oneRowData += "<tr><td>8</td><td>Status Keluarga</td><td>:</td> <td>" + data_detail.status + "</td></tr>";
                            oneRowData += "<tr><td>9</td><td>Hasil Pendataan</td><td>:</td> <td>" + data_detail.score + "</td></tr>";


                        }
                    }

                    for (let key in data[0].kuisioner) {
                        if (data[0].kuisioner.hasOwnProperty(key)) {
                            var data_detail = data[0].kuisioner[key];
                            var id = data_detail.id;
                            var array_id = ['0', '1', '2', '3', '3a', '3b', '3c', '3d', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38'];
                            oneRowDataKuisioner += "<tr><td>" + array_id[data_detail.id] + "</td><td>" + data_detail.question + "</td><td>" + data_detail.choice + "</td></tr>";
                        }
                    }



                    $("#resultFilter tbody").append(oneRowData);
                    $("#resultFilterQuestionnaire tbody").append(oneRowDataKuisioner);


                    table = $('#resultFilter').DataTable({
                        columnDefs: [{
                            className: 'dt-center',
                            targets: '_all'
                        }, {
                            target: 0,
                            visible: false,
                            searchable: false
                        }, ],
                        ordering: false,
                        searching: false,
                        paging: false,
                        info: false,
                        bDestroy: true,
                        dom: 'Bfrtip',
                    });

                    table = $('#resultFilterQuestionnaire').DataTable({
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

</body>

</html>