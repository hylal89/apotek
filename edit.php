<?php ob_start();
    //APAKAH COOKIE ADMIN ADA ? JIKA TRUE JALANKAN ELSE, JIKA FALSE REDIRECT KE HALAMAN login.php
    if (isset($_COOKIE["admin"]) == false) {
        header("location: login.php");
        die();
    }

    //INCLUDE FILE KONEKSI DATABASE
    else {
        include("koneksi_database.php");
    }

    //SCRIPT AMBIL DATA OBAT BERDASARKAN KODE
    if (isset($_GET["tbl_edit_obat"])) {
        $kode_obat = $_GET["tbl_edit_obat"];
        $query = "SELECT * FROM obat WHERE kode = {$kode_obat}";
        $result = mysqli_query($connect, $query);
        $hasil_query = mysqli_fetch_row($result);

        $kode = $hasil_query[0];
        $nama = $hasil_query[1];
        $harga = $hasil_query[2];
        $stok = $hasil_query[3];
        $tanggal = $hasil_query[4];
        $catatan = $hasil_query[5];
    }

    //ISI VARIABEL TANPA KLIK EDIT
    else {
        $alert = "";
        $text = "";
        $kode = "";
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
    }

    if (isset($_GET["tbl_form_obat"])) {

        $text = "";
        
        //KODE OBAT
        if (isset($_GET["kode"])) {
            $kode = $_GET["kode"];
        }

        //VALIDASI NAMA OBAT
        if (empty(strip_tags(trim($_GET["nama"])))) {
            $nama = "";
            $alert = "alert-danger show";
            $text .= "Nama obat kosong, harap input nama obat ";
            $border_nama = "border-danger";
        }

        elseif (strlen(strip_tags(trim($_GET["nama"]))) > 30) {
            $nama = "";
            $alert = "alert-danger show";
            $text .= "Nama obat tidak boleh lebih dari 30 karakter ";
            $border_nama = "border-danger";
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
        }

        elseif (abs($_GET["stok"]) > 255) {
            $stok = "";
            $alert = "alert-danger show";
            $text .= "Maksimal input stok 255 item ";
            $stok = "";
            $border_stok = "border-danger";
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
        }

        elseif (strip_tags(trim($_GET["harga"])) > 100000) {
            $harga = "";
            $alert = "alert-danger show";
            $text .= "Harga obat tidak valid ";
            $border_harga = "border-danger";
        }

        elseif (preg_match("/\D/", strip_tags(trim($_GET["harga"])))) {
            $harga = "";
            $alert = "alert-danger show";
            $text .= "Harga obat tidak boleh mengandung karakter khusus ";
            $border_harga = "border-danger";
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
        }

        elseif ($_GET["tgl_kadaluarsa"] <= date("Y-m-d",time())) {
            $tanggal = "";
            $alert = "alert-danger show";
            $text .= "Tanggal kadaluarsa tidak valid ";
            $border_tgl = "border-danger";
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
        }

        elseif (empty(strip_tags(trim($_GET["catatan"])))) {
            $catatan = "-";
            $border_catatan = "border-success";
        }

        else {
            $catatan = ucfirst(htmlentities(strip_tags(trim($_GET["catatan"]))));
            $border_catatan = "border-success";
        }

        //CEK APAKAH BORDER FORM BERWARNA HIJAU
        if (($border_nama == "border-success") && ($border_stok == "border-success") && ($border_harga == "border-success") && ($border_tgl == "border-success") && ($border_catatan == "border-success")) {
            $kode = $_GET["kode"];
            $nama_obat = mysqli_real_escape_string($connect, $nama);
            $stok_obat = mysqli_real_escape_string($connect, $stok);
            $harga_obat = mysqli_real_escape_string($connect, $harga);
            $tgl_kadaluarsa = mysqli_real_escape_string($connect, $tanggal);
            $catatan_obat = mysqli_real_escape_string($connect, $catatan);

            $query = "UPDATE obat SET nama = '{$nama_obat}', harga = {$harga_obat}, stok = {$stok_obat}, tgl_kadaluarsa = '{$tgl_kadaluarsa}', catatan = '{$catatan_obat}' WHERE kode = {$kode}";
            $result = mysqli_query($connect, $query);
            header("location: obat.php?pesan_edit_obat");
            die();
        }
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        a:hover{
            text-decoration: none;
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
                <section class="d-flex align-items-center border-bottom pb-1 d-print-none" style="height: 10%;">
                    <h4 class="text-muted font-weight-bold mb-0">Edit</h4>
                </section>
                <!-- PENANDA HALAMAN BREADCRUMB -->
                <section class="my-1 d-print-none">
                    <ol class="breadcrumb p-1 bg-transparent m-0" style="font-size: 12px;">
                        <li class="breadcrumb-item active"><a href="obat.php">Obat</a></li>
                        <li class="breadcrumb-item active">Edit</a></li>
                    </ol>
                </section>
                <!-- FORM TAMBAH DATA OBAT BARU -->
                <section class="p-4 mb-3 border">
                    <form action="edit.php" method="get">

                        <input type="hidden" name="kode" value="<?= $kode; ?>">

                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="kode">Kode</label>
                            <div class="col-2">
                                <input type="text" name="kode" id="kode" class="form-control" disabled value="<?= $kode; ?>">
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