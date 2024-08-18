<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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

                            <h3 class="mt-4 text-left d-none">Cari Berdasarkan</h3>
                            <form class="d-none" id="mainForm" method="post">
                                <div class="row mb-5">
                                    <label class="text-left">Kecamatan</label>
                                    <div class="col-2">
                                        <div class="form-floating">
                                            <select class="form-control selectpicker " id="dataKecamatan" name="dataKecamatan" data-live-search="true">
                                                <option>Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-3 pt-3 d-none">
                                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-light w-50" type="submit" style="background-color: #11152A; border: none; border-radius: 12px; margin-right: .5rem;">Telusuri</button>
                                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2 text-dark" type="button" style="background-color: #FFA800; border: none; border-radius: 12px;" onclick="ExportToExcel('xlsx')">Cetak</button>
                                    </div>
                                </div>
                            </form>
                        </div>



                        <div class="col text-right mb-5">
                            <p class="pak-bup-top-right mb-10">
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-dp2kbp3a-kab-kediri-circle.png" width="62" />
                                <img src="<?php echo base_url(); ?>assets/img/main/logo-mas-bup-merah-kab-kediri.png" width="75" />
                            </p>
                        </div>


                        <div class="row mt-10">
                            <div class="col-12 mx-auto my-5">
                                <p class="d-none">Tampilkan <select id="dataPerPage">
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select> Data Per Halaman </p>

                                <table id="resultFilter" class="display table my-3">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th width="10%">No.</th>
                                            <th width="80%">Pertanyaan</th>
                                            <th width="10%">Aksi</th>
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

            currentRequest = $.ajax({
                headers: {
                    "x-api-key": "<?php echo $this->session->userdata('x-api-key'); ?>",
                },
                data: data,
                url: "<?php echo base_url(); ?>api/list_data_jawaban/" + (pageno - 1),
                type: 'post',
                dataType: 'json',
                success: function(data, status, xhr) {
                    console.log("Received data:", data);
                    for (let key in data[0].filtered_data) {
                        if (data[0].filtered_data.hasOwnProperty(key)) {
                            var data_detail = data[0].filtered_data[key];
                            oneRowData += "<tr>";
                            oneRowData += "<td>" + data_detail.id + "</td>";
                            oneRowData += "<td>" + data_detail.question + "</td>";
                            oneRowData += "<td>" + '<button class="btn btn-outline-primary" data-toggle="modal" data-target=".bs-example-modal-lg" title="Edit Data" onclick="redirectToDetail(' + data_detail.id + ')">Detail</button>' + "</td>";
                            oneRowData += "</tr>";
                        } else {
                            console.error("Unexpected data structure:", data);
                            $("#resultFilter tbody").html("<tr><td colspan='2'>No data available</td></tr>");
                        }

                    }

                    for (let key in data[0].pagination_data) {
                        if (data[0].pagination_data.hasOwnProperty(key)) {
                            var data_detail = data[0].pagination_data[key];
                            $("#pagination_text").html(data_detail.data_shown_to_user);
                            $(".paginationUnderResultTable").html(data_detail.html_data);
                        }
                    }

                    if ($.fn.DataTable.isDataTable('#resultFilter')) {
                        $('#resultFilter').DataTable().destroy();
                    }

                    $("#resultFilter tbody").html(oneRowData);

                    table = $('#resultFilter').DataTable({
                        columnDefs: [{
                            className: 'dt-left',
                            targets: '_all'
                        }],
                        ordering: false,
                        searching: false,
                        paging: false,
                        info: false
                    });

                    $("#ajax_status").hide();
                    currentRequest = null;
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                    console.error("Response Text:", xhr.responseText);
                    $("#ajax_status").hide();
                    $("#resultFilter tbody").html("<tr><td colspan='2'>Error loading data</td></tr>");
                    currentRequest = null;
                }
            });

            return false;
        }

        function base64EncodeId(id) {
            return btoa(id); // Encode the id to base64
        }

        function redirectToDetail(id) {
            const encodedId = base64EncodeId(id);
            window.location.href = "rekapitulasi_jawaban/detail/" + encodedId;
        }



        // Call the loadDataWithFilter function when the form is submitted
        $("#mainForm").on("submit", function(event) {
            data = {
                "id_kecamatan": $('#dataKecamatan').val(),
                "data_per_page": $('#dataPerPage').val(),
                "page": 1
            };

            loadDataWithFilter(1, data);
            return false;
        });
    </script>
</body>

</html>