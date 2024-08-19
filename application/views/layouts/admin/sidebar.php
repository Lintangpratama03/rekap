<aside id="sidebar-wrapper">
    <div class="sidebar-brand text-light p-2 m-3 mt-2">
        <div class="row g-0">
            <div class="col-2 mx-auto my-auto">
                <img src="<?php echo base_url(); ?>assets/img/main/logo-plain-dp2kbp3a-kab-kediri.png" style="width: 100%; height:auto" />
            </div>
            <div class="col-10 text-start p-4">
                <p>DINAS P2KBP3A<br>KABUPATEN KEDIRI</p>
            </div>
        </div>
    </div>
    <ul class="sidebar-nav">

        <li <?php if ($active_sidebar == 'In Kelud') echo "class='active'"; ?>>
            <a href="<?php echo base_url(); ?>inkelud"><img src="<?php echo base_url(); ?>assets/img/icon/sidebar-icon-home.svg" /> In Kelud</a>
        </li>
        <li <?php if ($active_sidebar == 'Rekapitulasi KS/KPS') echo "class='active'"; ?>>
            <a href="<?php echo base_url(); ?>rekapitulasi_kskps"><img src="<?php echo base_url(); ?>assets/img/icon/sidebar-icon-paper-folder.svg" /> Rekapitulasi KS/KPS</a>
        </li>
        <li <?php if ($active_sidebar == 'Tabel Jawaban') echo "class='active'"; ?>>
            <a href="<?php echo base_url(); ?>rekapitulasi_jawaban"><img src="<?php echo base_url(); ?>assets/img/icon/sidebar-icon-paper-folder.svg" /> Rekapitulasi Jawaban</a>
        </li>
        <li <?php if ($active_sidebar == 'Tabel KS/KPS') echo "class='active'"; ?>>
            <a href="<?php echo base_url(); ?>table_kskps"><img src="<?php echo base_url(); ?>assets/img/icon/sidebar-icon-table.svg" /> Tabel KS/KPS</a>
        </li>
        <li>
            <a href="#" id="logoutConfirmation"><img src="<?php echo base_url(); ?>assets/img/icon/sidebar-icon-exit.svg" style="transform: scaleX(-1);" /> Keluar</a>
        </li>
    </ul>
</aside>