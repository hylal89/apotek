<?php ob_start();
    //APAKAH COOKIE ADMIN ADA ? JIKA TRUE JALANKAN ELSE, JIKA FALSE REDIRECT KE HALAMAN login.php
    if (isset($_COOKIE["admin"]) == false) {
        header("location: login.php");
        die();
    }

    //SCRIPT GENERATE KODE PEMBELI OTOMATIS
    else {
        include("koneksi_database.php");
        $query = "SELECT MAX(kode) FROM pembeli";
        $result = mysqli_query($connect,$query);
        $hasil_query = mysqli_fetch_row($result);
        $hasil_query[0] += 1;
        $kode_pembeli = $hasil_query[0];
    }

    //VALIDASI FORM TRANSAKSI
    if (isset($_GET["transaksi"])) {

        $text = "";

        //VALIDASI NAMA
        if (empty(strip_tags(trim($_GET["nama"])))) {
            $nama = "";
            $alert = "alert-danger show";
            $text .= "Nama pembeli kosong, harap input nama pembeli ";
            $border_nama = "border-danger";
        }

        elseif (strlen(strip_tags(trim($_GET["nama"]))) > 30) {
            $nama = "";
            $alert = "alert-danger show";
            $text .= "Nama pembeli tidak boleh lebih dari 30 karakter ";
            $border_nama = "border-danger";
        }

        elseif (preg_match("/\d/", strip_tags(trim($_GET["nama"])))) {
            $nama = "";
            $alert = "alert-danger show";
            $text .= "Nama pembeli tidak boleh mengandung angka [0-9] ";
            $border_nama = "border-danger";
        }

        else{
            $nama = htmlentities(strip_tags(trim($_GET["nama"])));
            $border_nama = "border-success";
        }

        //VALIDASI ALAMAT
        if (strlen(strip_tags(trim($_GET["alamat"]))) > 50) {
            $alamat = "";
            $alert = "alert-danger show";
            $text .= "Alamat tidak boleh lebih dari 50 karakter ";
            $border_alamat = "border-danger";
        }

        elseif (empty(strip_tags(trim($_GET["alamat"])))) {
            $alamat = "-";
            $border_alamat = "border-success";
        }

        else {
            $alamat = htmlentities(strip_tags(trim($_GET["alamat"])));
            $border_alamat = "border-success";
        }

        //VALIDASI JENIS KELAMIN
        if (isset($_GET["jenis_kelamin"])) {
            $border_jenis_kelamin = "border-success";
        }

        //VALIDASI JUMLAH OBAT
        if (empty($_GET["jumlah"])) {
            $jumlah = "";
            $alert = "alert-danger show";
            $text .= "Jumlah jenis obat kosong, harap input ";
            $border_jumlah = "border-danger";
        }

        else {
            $jumlah = (int) abs($_GET["jumlah"]);
            $border_jumlah = "border-success";
        }

        if (($border_nama === "border-success") && ($border_alamat === "border-success") && ($border_jenis_kelamin === "border-success") && ($border_jumlah === "border-success")) {
            $nama_pembeli = urlencode($nama);
            $alamat_pembeli = urlencode($alamat);
            $jenis_kelamin = $_GET["jenis_kelamin"];
            $jumlah_obat = $jumlah;
            $string = "kode={$kode_pembeli}&nama={$nama_pembeli}&alamat={$alamat_pembeli}&jenis_kelamin={$jenis_kelamin}&counter={$jumlah_obat}&key=foo";
            header("location: detail_transaksi.php?{$string}");
            die();
        }
    }

    else {
        $alert = "";
        $text = "";
        $border_nama = "";
        $border_alamat = "";
        $border_jenis_kelamin = "";
        $border_jumlah = "";
        $nama = "";
        $alamat = "";
        $jumlah = "";
    }

    //PESAN DARI DETAIL TRANSAKSI
    if (isset($_GET["pesan_detail_transaksi"])) {
        $alert = "alert-success show";
        $text = "Transaksi berhasil";
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaksi</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
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
                <div class="alert alert-dismissible fade w-75 ml-auto m-0 text-truncate rounded-0 <?= $alert ?>" style="font-size: 13px;">
                    <strong><?= $text ?></strong>
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
                        <a href="transaksi.php" class="list-group-item list-group-item-action rounded-0 border-right-0 active"><i class="fa fa-shopping-cart fa-fw"></i>&nbsp;&nbsp;&nbsp;Transaksi</a>
                        <a href="obat.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-pills fa-fw"></i>&nbsp;&nbsp;&nbsp;Obat</a>
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
                    <h4 class="text-muted font-weight-bold mb-0">Transaksi</h4>
                </section>
                <!-- FORM PENDAFTARAN KONSUMEN -->
                <section class="p-4 border">
                    <form action="transaksi.php" method="get">
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="kode">Kode</label>
                            <div class="col-2">
                                <input type="text" name="kode" id="kode" class="form-control" disabled value="<?= $kode_pembeli; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="nama">Nama</label>
                            <div class="col-4">
                                <input type="text" name="nama" placeholder="jane doe" id="nama" class="form-control <?= $border_nama; ?>" autocomplete="off" value="<?= $nama; ?>">
                                <small class="text-muted form-text">nama tidak boleh lebih dari 30 karakter</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="alamat">Alamat</label>
                            <div class="col-4">
                                <textarea name="alamat" class="form-control <?= $border_alamat; ?>" id="alamat" rows="5"><?= $alamat ?></textarea>
                                <small class="text-muted form-text">alamat tidak boleh lebih dari 50 karakter</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="jenis_kelamin">Jenis kelamin</label>
                            <div class="col-4">
                                <select name="jenis_kelamin" id="jenis_kelamin" class="custom-select <?= $border_jenis_kelamin; ?>">
                                    <option value="1">Laki-Laki</option>
                                    <option value="2">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label ml-1" for="jumlah">Berapa jenis obat yang akan dibeli ?</label>
                            <div class="col-2">
                                <input type="number" name="jumlah" placeholder="jumlah" id="jumlah" class="form-control <?= $border_jumlah; ?>" min="1" value="<?= $jumlah ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" name="transaksi">Proses</button>
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