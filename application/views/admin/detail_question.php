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
                                        <!-- <?php $no = 1; ?>
                                        <?php foreach ($data as $item): ?>
                                            <tr class="text-center">
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($item['pilihan']); ?></td>
                                                <td><?= htmlspecialchars($item['total'] ?? 0); ?></td>
                                            </tr>
                                        <?php endforeach; ?> -->
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
            loadData({
                id_question: '<?= $pertanyaan['id']; ?>'
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