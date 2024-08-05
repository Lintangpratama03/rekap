<!-- <footer>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</footer> -->

<script src="<?php echo base_url(); ?>assets/js/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/font-awesome/all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js" integrity="sha512-UnrKxsCMN9hFk7M56t4I4ckB4N/2HHi0w/7+B/1JsXIX3DmyBcsGpT3/BsuZMZf+6mAr0vP81syWtfynHJ69JA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js" integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }

    $('#logoutConfirmation').click(function() {
        Swal.fire({
            title: 'Apakah Anda ingin keluar ?',
            showCancelButton: true,
            confirmButtonText: 'Iya',
            cancelButtonText: `Batalkan`,
        }).then((result) => {
            if (result.isConfirmed) {
                window.open('<?php echo base_url(); ?>logout', "_self");
            } else {
                return false;
            }
        })
    });

    function updateCurrentTime() {
        var now = new Date();
        var day = String(now.getDate()).padStart(2, '0');
        var month = String(now.getMonth() + 1).padStart(2, '0');
        var year = now.getFullYear();
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        var seconds = String(now.getSeconds()).padStart(2, '0');

        var formattedTime = day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds;

        var currentTimeElement = document.getElementById('currentTime');
        currentTimeElement.textContent = formattedTime;
    }

    // Initial call to display the current time
    updateCurrentTime();

    // Update the time every second
    setInterval(updateCurrentTime, 1000);

    const $button = document.querySelector('#sidebar-toggle');
    const $wrapper = document.querySelector('#wrapper');

    $button.addEventListener('click', (e) => {
        e.preventDefault();
        $wrapper.classList.toggle('toggled');
    });

    function ExportToExcel() {
        $('#removeWhenGenerateExcelBecauseItCannotHanddleRowspanAndColspan').empty();
        $('#removeWhenGenerateExcelBecauseItCannotHanddleRowspanAndColspan').html('<th>&nbsp;</th> <th>&nbsp;</th> <th>&nbsp;</th> <th style="text-align: center; background: #23899e">Jumlah KK Sejahtera</th> <th>&nbsp;</th> <th>&nbsp;</th> <th style="text-align: center; background: #ff4973;">Jumlah KK Pra Sejahtera</th> <th>&nbsp;</th> <th>&nbsp;</th>');


        // Create a new Excel workbook
        const workbook = new ExcelJS.Workbook();

        // Add the first sheet for the 'resultFilter' table
        const sheet1 = workbook.addWorksheet('Sheet 1');
        const table1 = document.getElementById('resultFilter');
        const rows1 = table1.querySelectorAll('tr');

        rows1.forEach((row, rowIndex) => {
            const cells = row.querySelectorAll('th, td');
            const rowData = [];
            cells.forEach((cell, cellIndex) => {
                rowData.push(cell.innerText);
            });

            // Use the first row to detect header cells (TH)
            if (rowIndex === 0) {
                sheet1.addRow(rowData).font = {
                    bold: true
                };

                if ($('#removeWhenGenerateExcelBecauseItCannotHanddleRowspanAndColspan').length) {
                    sheet1.mergeCells('D1:F1');
                    sheet1.mergeCells('G1:I1');
                }

            } else {
                sheet1.addRow(rowData);
            }
        });

        // Check if the 'resultFilterQuestionnaire' table exists
        const table2 = document.getElementById('resultFilterQuestionnaire');
        if (table2) {
            // Add the second sheet for the 'resultFilterQuestionnaire' table
            const sheet2 = workbook.addWorksheet('Sheet 2');
            const rows2 = table2.querySelectorAll('tr');

            rows2.forEach((row, rowIndex) => {
                const cells = row.querySelectorAll('th, td');
                const rowData = [];
                cells.forEach((cell, cellIndex) => {
                    rowData.push(cell.innerText);
                });

                // Use the first row to detect header cells (TH)
                if (rowIndex === 0) {
                    sheet2.addRow(rowData).font = {
                        bold: true
                    };
                } else {
                    sheet2.addRow(rowData);
                }
            });

            // Apply borders to all cells in the second sheet
            sheet2.eachRow((row) => {
                row.eachCell((cell) => {
                    cell.border = {
                        top: {
                            style: 'thin'
                        },
                        left: {
                            style: 'thin'
                        },
                        bottom: {
                            style: 'thin'
                        },
                        right: {
                            style: 'thin'
                        },
                    };
                });
            });
        }

        // Apply borders to all cells in the first sheet (Sheet 1)
        sheet1.eachRow((row) => {
            row.eachCell((cell) => {
                cell.border = {
                    top: {
                        style: 'thin'
                    },
                    left: {
                        style: 'thin'
                    },
                    bottom: {
                        style: 'thin'
                    },
                    right: {
                        style: 'thin'
                    },
                };
            });
        });

        // Save the workbook as an Excel file
        workbook.xlsx.writeBuffer().then((buffer) => {
            const blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            });
            saveAs(blob, '<?php echo $title_h1; ?>' + '.xlsx'); // Replace 'YourFileName' with your desired file name.
        });

        $('#removeWhenGenerateExcelBecauseItCannotHanddleRowspanAndColspan').empty();
        $('#removeWhenGenerateExcelBecauseItCannotHanddleRowspanAndColspan').html('<th style="vertical-align : middle;text-align:center">&nbsp;</th> <th style="vertical-align : middle;text-align:center">&nbsp;</th> <th style="vertical-align : middle;text-align:center">&nbsp;</th> <th colspan="3" style="text-align: center; background: #23899e">Jumlah KK Sejahtera</th> <th colspan="3" style="text-align: center; background: #ff4973;">Jumlah KK Pra Sejahtera</th>');

    }
</script>