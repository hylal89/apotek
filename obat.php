<?php ob_start();
    //APAKAH COOKIE ADMIN ADA ? JIKA TRUE JALANKAN ELSE, JIKA FALSE REDIRECT KE HALAMAN login.php
    if (isset($_COOKIE["admin"]) == false) {
        header("location: login.php");
        die();
    }

    //SCRIPT GENERATE KODE OBAT OTOMATIS
    else {
        include("koneksi_database.php");
        $query = "SELECT MAX(kode) FROM obat";
        $result = mysqli_query($connect,$query);
        $hasil_query = mysqli_fetch_row($result);
        $hasil_query[0] += 1;
        $kode_obat = $hasil_query[0];
    }

    //SCRIPT VALIDASI FORM
    if (isset($_GET["tbl_form_obat"])) {

        $text = "";

        //VALIDASI NAMA OBAT
        if (empty(strip_tags(trim($_GET["nama"])))) {
            $nama = "";
            $alert = "alert-danger show";
            $text .= "Nama obat kosong, harap input nama obat ";
            $border_nama = "border-danger";
            $collapse = "show";
        }

        elseif (strlen(strip_tags(trim($_GET["nama"]))) > 30) {
            $nama = "";
            $alert = "alert-danger show";
            $text .= "Nama obat tidak boleh lebih dari 30 karakter ";
            $border_nama = "border-danger";
            $collapse = "show";
        }

        else {
            $nama = ucfirst(htmlentities(strip_tags(trim($_GET["nama"]))));
            $border_nama = "border-success";
        }

        //VALIDASI STOK OBAT
        if (empty(abs($_GET["stok"]))) {
            $stok = "";
            $alert = "alert-danger show";
            $text .= "Stok obat kosong, harap input stok obat ";
            $border_stok = "border-danger";
            $collapse = "show";
        }

        elseif (abs($_GET["stok"]) > 255) {
            $stok = "";
            $alert = "alert-danger show";
            $text .= "Maksimal input stok 255 item ";
            $stok = "";
            $border_stok = "border-danger";
            $collapse = "show";
        }

        else {
            $stok = (int) abs($_GET["stok"]);
            $border_stok = "border-success";
        }

         //VALIDASI HARGA OBAT
        if (empty(strip_tags(trim($_GET["harga"])))) {
            $harga = "";
            $alert = "alert-danger show";
            $text .= "Harga obat kosong, harap input harga obat ";
            $border_harga = "border-danger";
            $collapse = "show";
        }

        elseif (strip_tags(trim($_GET["harga"])) > 100000) {
            $harga = "";
            $alert = "alert-danger show";
            $text .= "Harga obat tidak valid ";
            $border_harga = "border-danger";
            $collapse = "show";
        }

        elseif (preg_match("/\D/", strip_tags(trim($_GET["harga"])))) {
            $harga = "";
            $alert = "alert-danger show";
            $text .= "Harga obat tidak boleh mengandung karakter khusus ";
            $border_harga = "border-danger";
            $collapse = "show";
        }

        else {
            $harga = (int) strip_tags(trim($_GET["harga"]));
            $border_harga = "border-success";
        }

         //VALIDASI TANGGAL KADALAURSA
        if (empty($_GET["tgl_kadaluarsa"])) {
            $tanggal = "";
            $alert = "alert-danger show";
            $text .= "Tanggal kadalaursa kosong, harap input atau pilih tanggal ";
            $border_tgl = "border-danger";
            $collapse = "show";
        }

        elseif ($_GET["tgl_kadaluarsa"] <= date("Y-m-d",time())) {
            $tanggal = "";
            $alert = "alert-danger show";
            $text .= "Tanggal kadaluarsa tidak valid ";
            $border_tgl = "border-danger";
            $collapse = "show";
        }

        else {
            $tanggal = $_GET["tgl_kadaluarsa"];
            $border_tgl = "border-success";
        }

        //VALIDASI ALAMAT
        if (strlen(strip_tags(trim($_GET["catatan"]))) > 65535) {
            $catatan = "-";
            $alert = "alert-danger show";
            $text .= "Catatan terlalu panjang ";
            $border_catatan = "border-danger";
            $collapse = "show";
        }

        elseif (empty(strip_tags(trim($_GET["catatan"])))) {
            $catatan = "-";
            $border_catatan = "border-success";
        }

        else {
            $catatan = ucfirst(htmlentities(strip_tags(trim($_GET["catatan"]))));
            $border_catatan = "border-success";
        }

        if (($border_nama === "border-success") && ($border_stok === "border-success") && ($border_harga === "border-success") && ($border_tgl === "border-success") && ($border_catatan === "border-success")) {
            $kode = $kode_obat;
            $nama_obat = mysqli_real_escape_string($connect, $nama);
            $stok_obat = mysqli_real_escape_string($connect, $stok);
            $harga_obat = mysqli_real_escape_string($connect, $harga);
            $tgl_kadaluarsa = mysqli_real_escape_string($connect, $tanggal);
            $catatan_obat = mysqli_real_escape_string($connect, $catatan);

            $query = "INSERT INTO obat VALUES({$kode},'{$nama_obat}',{$harga_obat},{$stok_obat},'{$tgl_kadaluarsa}','{$catatan_obat}')";
            $result = mysqli_query($connect, $query);
            header("location: obat.php?pesan_form_obat");
            die();
        }
    }

    //TAMPILAN AWAL
    else {
        $alert = "";
        $text = "";
        $nama = "";
        $stok = "";
        $harga = "";
        $tanggal = "";
        $catatan = "";
        $border_nama = "";
        $border_stok = "";
        $border_harga = "";
        $border_tgl = "";
        $border_catatan = "";
        $collapse = "collapse";
    }

    //SCRIPT TAMPILKAN TABEL OBAT
    $query = "SELECT * FROM obat";
    $result = mysqli_query($connect,$query);

    if (mysqli_num_rows($result) > 0) {
        $table = "show";
    }

    else {
        $table = "collapse";
    }

    //SCRIPT PESAN OBAT BERHASIL DITAMBAHKAN
    if (isset($_GET["pesan_form_obat"])) {
        $alert = "alert-success show";
        $text = "Data obat baru berhasil ditambahkan";
    }

    //SCRIPT HAPUS DATA OBAT
    if (isset($_GET["tbl_hapus_obat"])) {
        $tbl_hapus_obat = $_GET["tbl_hapus_obat"];
        $query = "DELETE FROM obat WHERE kode = {$tbl_hapus_obat}";
        $result = mysqli_query($connect,$query);

        if ($result == false) {
            header("location: obat.php?pesan_hapus_obat_gagal");
            die();
        }

        else {
            header("location: obat.php?pesan_hapus_obat_berhasil");
            die();
        }
    }

    //SCRIPT PESAN DATA OBAT BERHASIL DIHAPUS
    if (isset($_GET["pesan_hapus_obat_berhasil"])) {
         $text = "Data obat berhasil dihapus";
         $alert = "alert-success show";
    }

    //SCRIPT PESAN DATA OBAT GAGAL DIHAPUS
    if (isset($_GET["pesan_hapus_obat_gagal"])) {
         $text = "Data obat gagal dihapus, pastikan pembeli yang membeli obat ini dihapus terlebih dahulu";
         $alert = "alert-danger show";
    }

    //SCRIPT PESAN EDIT DATA OBAT
    if (isset($_GET["pesan_edit_obat"])) {
         $text = "Data obat berhasil di update";
         $alert = "alert-success show";
    }

    //SCRIPT PESAN DATA OBAT TIDAK DITEMUKAN
    if (isset($_GET["pesan_obat_tidak_ada"])) {
        $alert = "alert-danger show";
        $text = "Data obat tidak ditemukan";
        $table ="collapse";
    }

    //SCRIPT PESAN DATA OBAT DITEMUKAN
    if (isset($_GET["pesan_obat_ditemukan"])) {
        $alert = "alert-success show";
        $text = "Data obat ditemukan";
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Obat</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        th{
            border-bottom: none !important;
        }

        @media not print{
            #judul{
                display: none;
            }
        }

        @media only print{
            #body{
                border: none !important;
            }
        }
    </style>
</head>
<body class="bg-light" style="height: 100%;">
    <div class="container-fluid p-0" style="height: 100%;">
        <!-- BAGIAN KEPALA APLIKASI -->
        <header style="height: 10%;" class="d-print-none">
            <nav class="navbar navbar-light bg-light border-bottom" style="height: 100%;">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navigasi">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <span class="navbar-brand ml-4 mr-auto">Apotek</span>
                <!-- NOTIFIKASI -->
                <div class="alert alert-dismissible fade w-75 ml-auto m-0 text-truncate rounded-0 <?= $alert; ?>" style="font-size: 13px;">
                    <strong><?= $text; ?></strong>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            </nav>
        </header>
        <!-- BADAN APLIKASI -->
        <div class="row no-gutters" style="height: 90%;">
            <!-- KOLOM SEBELAH KIRI UNTUK NAVIGASI DAN PENCARIAN DATA -->
            <div class="col-2 show d-print-none" id="navigasi" style="height: 100%;">
                <!-- PENCARIAN DATA OBAT -->
                <section class="p-2">
                    <form action="obat.php" method="get" class="input-group">
                        <input type="text" name="cari_obat" placeholder="cari obat..." class="form-control" autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text" name="tbl_cari_obat" title="Cari" data-toggle="tooltip" style="cursor: pointer;"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </section>
                <!-- NAVIGASI -->
                <section>
                    <div class="list-group">
                        <a href="dashboard.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fas fa-tachometer-alt fa-fw"></i>&nbsp;&nbsp;&nbsp;Dashboard</a>
                        <a href="transaksi.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-shopping-cart fa-fw"></i>&nbsp;&nbsp;&nbsp;Transaksi</a>
                        <a href="obat.php" class="list-group-item list-group-item-action rounded-0 border-right-0 active"><i class="fa fa-pills fa-fw"></i>&nbsp;&nbsp;&nbsp;Obat</a>
                        <a href="laporan.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-book fa-fw"></i>&nbsp;&nbsp;&nbsp;Laporan</a>
                        <a href="tentang.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-question fa-fw"></i>&nbsp;&nbsp;&nbsp;Tentang</a>
                        <a href="keluar.php" class="list-group-item list-group-item-action rounded-0 border-right-0 text-danger"><i class="fa fa-window-close fa-fw"></i>&nbsp;&nbsp;&nbsp;Keluar</a>
                    </div>
                </section>
            </div>
            <!-- KOLOM SEBELAH KANAN UNTUK ISI KONTEN -->
            <div class="col bg-white px-5 pt-1 border-left border-bottom" style="height: 100%; overflow-x: auto;" id="body">
                <!-- JUDUL KONTEN DAN UNTUK NOTIFIKASI -->
                <section class="mb-3 d-flex align-items-center border-bottom pb-1 d-print-none" style="height: 10%;">
                    <h4 class="text-muted font-weight-bold mb-0">Obat</h4>
                    <!-- TBL TAMBAH DATA OBAT -->
                    <span class="ml-auto">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#form_obat">Tambah Data</button>
                    </span>
                </section>
                <!-- FORM TAMBAH DATA OBAT BARU -->
                <section class="p-4 mb-3 border <?= $collapse; ?>" id="form_obat">
                    <form action="obat.php" method="get">
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="kode">Kode</label>
                            <div class="col-2">
                                <input type="text" name="kode" id="kode" class="form-control" disabled value="<?= $kode_obat; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="nama">Nama</label>
                            <div class="col-4">
                                <input type="text" name="nama" placeholder="" id="nama" class="form-control <?= $border_nama; ?>" autocomplete="off" value="<?= $nama; ?>">
                                <small class="text-muted form-text">nama obat tidak boleh lebih dari 30 karakter</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="stok">Stok obat</label>
                            <div class="col-2">
                                <input type="number" name="stok" placeholder="" id="stok" class="form-control <?= $border_stok; ?>" autocomplete="off" value="<?= $stok; ?>" min="1" max="255">
                                <small class="text-muted form-text">maksimal input 255</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="harga">Harga satuan</label>
                            <div class="col-4">
                                <input type="text" name="harga" placeholder="" id="harga" class="form-control <?= $border_harga; ?>" autocomplete="off" value="<?= $harga; ?>">
                                <small class="text-muted form-text">format : 70000 = Rp.70.000,-</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="tgl_kadaluarsa">Tanggal kadaluarsa</label>
                            <div class="col-4">
                                <input type="date" name="tgl_kadaluarsa" placeholder="" id="tgl_kadaluarsa" class="form-control <?= $border_tgl; ?>" autocomplete="off" value="<?= $tanggal; ?>">
                                <small class="text-muted form-text">format : bulan/tanggal/tahun</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="catatan">Catatan obat</label>
                            <div class="col-4">
                                <textarea name="catatan" class="form-control <?= $border_catatan; ?>" id="catatan" rows="5"><?= $catatan; ?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" name="tbl_form_obat">Simpan</button>
                    </form>
                </section>
                <!-- //JUDUL KETIKA DI PRINT -->
                <section id="judul" class="border-bottom mb-4">
                    <p class="mb-0"><small>KLINIK AZKA</small></>
                    <p class="mb-0"><small>Kp. Bangbayang Rt/Rw. 03/02 Cicurug-Sukabumi</small></>
                    <p class="mb-2"><small>Telp. (0266) 6725182</small></p>
                </section>
                <!-- TABLE DATA OBAT -->
                <section class="table-responsive <?= $table; ?>">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light text-center">
                            <tr><th>No</th><th>Kode</th><th>Nama Obat</th><th>Harga</th><th>Stok</th><th>Kadaluarsa</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 0;

                                if (isset($_GET["tbl_cari_obat"])) {

                                    $cari_obat = htmlentities(strip_tags(trim($_GET["cari_obat"])));
                                    $query = "SELECT * FROM obat WHERE nama LIKE '{$cari_obat}%'";
                                    $result = mysqli_query($connect, $query);

                                    if (mysqli_num_rows($result) == 0) {
                                        header("location: obat.php?pesan_obat_tidak_ada");
                                        die();
                                    }

                                    else {
                                        header("location: obat.php?pesan_obat_ditemukan&pesan_cari_obat&nilai_obat={$cari_obat}");
                                        die();
                                    }
                                }

                                if (isset($_GET["pesan_cari_obat"])) {

                                    $nilai_obat = htmlentities(strip_tags(trim($_GET["nilai_obat"])));
                                    $query = "SELECT * FROM obat WHERE nama LIKE '{$nilai_obat}%'";
                                    $result = mysqli_query($connect, $query);

                                    while ($data_obat = mysqli_fetch_row($result)) {
                                        $no += 1;
                                        echo "<tr>";
                                        echo "<td class=\"text-center\">{$no}</td>";
                                        echo "<td class=\"text-center\">{$data_obat[0]}</td>";
                                        echo "<td>{$data_obat[1]}</td>";
                                        echo "<td class=\"text-center\">"."Rp. ".number_format($data_obat[2],0,',','.').",-"."</td>";
                                        echo "<td class=\"text-center\">{$data_obat[3]}</td>";
                                        echo "<td class=\"text-center\">".date("d-m-Y",strtotime($data_obat[4]))."</td>";
                                        echo "<td class=\"text-center\">";
                                        echo "<a href=\"info_obat.php?tbl_info_obat={$data_obat[0]}\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-info fa-fw\"></i></a>";
                                        echo "<a href=\"edit.php?tbl_edit_obat={$data_obat[0]}\" class=\"btn btn-warning btn-sm ml-1\"><i class=\"fa fa-edit fa-fw\"></i></a>";
                                        echo "<a href=\"obat.php?tbl_hapus_obat={$data_obat[0]}\" class=\"btn btn-danger btn-sm ml-1\"><i class=\"fa fa-trash-alt fa-fw\"></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }

                                else {
                                    while ($data_obat = mysqli_fetch_row($result)) {
                                        $no += 1;
                                        echo "<tr>";
                                        echo "<td class=\"text-center\">{$no}</td>";
                                        echo "<td class=\"text-center\">{$data_obat[0]}</td>";
                                        echo "<td>{$data_obat[1]}</td>";
                                        echo "<td class=\"text-center\">"."Rp. ".number_format($data_obat[2],0,',','.').",-"."</td>";
                                        echo "<td class=\"text-center\">{$data_obat[3]}</td>";
                                        echo "<td class=\"text-center\">".date("d-m-Y",strtotime($data_obat[4]))."</td>";
                                        echo "<td class=\"text-center\">";
                                        echo "<a href=\"info_obat.php?tbl_info_obat={$data_obat[0]}\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-info fa-fw\"></i></a>";
                                        echo "<a href=\"edit.php?tbl_edit_obat={$data_obat[0]}\" class=\"btn btn-warning btn-sm ml-1\"><i class=\"fa fa-edit fa-fw\"></i></a>";
                                        echo "<a href=\"obat.php?tbl_hapus_obat={$data_obat[0]}\" class=\"btn btn-danger btn-sm ml-1\"><i class=\"fa fa-trash-alt fa-fw\"></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
    <script src="jquery/jquery-3.3.1.js"></script>
    <script src="popper/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(function(){
            $('[data-toggle="popover"]').popover()
        })

        $(function(){
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</body>
</html>
<?php mysqli_close($connect); ?>
<?php ob_end_flush(); ?>