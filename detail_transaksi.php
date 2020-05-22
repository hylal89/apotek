<?php ob_start();
    //APAKAH COOKIE ADMIN ADA ? JIKA TRUE JALANKAN ELSE, JIKA FALSE REDIRECT KE HALAMAN login.php
    if (isset($_COOKIE["admin"]) == false) {
        header("location: login.php");
        die();
    }

    //SCRIPT GENERATE NO NOTA OTOMATIS
    else {
        include("koneksi_database.php");
        $query = "SELECT MAX(nota) FROM transaksi";
        $result = mysqli_query($connect,$query);
        $hasil_query = mysqli_fetch_row($result);
        $hasil_query[0] += 1;
        $kode_nota = $hasil_query[0];
    }

    //AMBIL DATA-DATA PEMBELI
    if (isset($_GET["key"])) {
        $kode_pembeli = $_GET["kode"];
        $nama_pembeli = ucfirst($_GET["nama"]);
        $alamat_pembeli = ucfirst($_GET["alamat"]);
        $jenis_kelamin = $_GET["jenis_kelamin"];
        $counter = $_GET["counter"];
        $alert = "";
        $text = "";
        $border_jumlah = "";
        $data_lengkap_pembeli = "kode={$kode_pembeli}&nama={$nama_pembeli}&alamat={$alamat_pembeli}&jenis_kelamin={$jenis_kelamin}&counter={$counter}&key=foo";
    }

    //TAMPILAN JIKA TIDAK DIAKSES DARI TRANSAKSI
    else {
        $kode_pembeli = "";
        $nama_pembeli = "";
        $alamat_pembeli = "";
        $jenis_kelamin = "";
        $counter = 0;
        $alert = "";
        $text = "";
        $border_jumlah = "";
    }

    //VALIDASI FORM DETAIL TRANSAKSI
    if (isset($_GET["tbl_detail_transaksi"])) {
        $tbl_detail_transaksi = $_GET["tbl_detail_transaksi"];
        $pesan_error = "";
        for ($i=1; $i < $tbl_detail_transaksi; $i++) {

            $kode_obat = $_GET["obat_{$i}"];
            $query = "SELECT nama, stok FROM obat WHERE kode = {$kode_obat}";
            $result = mysqli_query($connect,$query);
            $hasil_query = mysqli_fetch_row($result);

            if (empty($_GET["jumlah_{$i}"])) {
                $alert = "alert-danger show";
                $text = "Jumlah obat yang dibeli ada yang kosong, harap input semua ";
                $border_jumlah = "border-danger";
                $pesan_error = "&alert={$alert}&text={$text}&border={$border_jumlah}&error=error";
                header("location: detail_transaksi.php?{$data_lengkap_pembeli}{$pesan_error}");
                die();
            }

            elseif ($hasil_query[1] < $_GET["jumlah_{$i}"]) {
                $alert = "alert-danger show";
                $text = "Obat {$hasil_query[0]} tinggal {$hasil_query[1]}, harap input jumlah kurang atau = {$hasil_query[1]} ";
                $border_jumlah = "border-danger";
                $pesan_error = "&alert={$alert}&text={$text}&border={$border_jumlah}&error=error";
                header("location: detail_transaksi.php?{$data_lengkap_pembeli}{$pesan_error}");
                die();
            }

            else {
                $container_obat[] = $_GET["obat_{$i}"];
                $container_jumlah[] = $_GET["jumlah_{$i}"];
                $pesan_error = "";
                $text = "";
                $alert = "";
            }
        }

        //JIKA PESAN ERROR == "" INSERT DATA LALU REDIRECT KE HALAMAN TRANSAKSI
        if ($pesan_error == "") {

            $query = "INSERT INTO pembeli VALUES ({$kode_pembeli},'{$nama_pembeli}','{$alamat_pembeli}',{$jenis_kelamin})";
            $result = mysqli_query($connect,$query);
            $query = "INSERT INTO transaksi VALUES ({$kode_nota},{$kode_pembeli},DEFAULT)";
            $result = mysqli_query($connect,$query);
            for ($i=0; $i < count($container_obat); $i++) {
                $query = "INSERT INTO detail_transaksi VALUES ({$kode_nota},{$container_obat[$i]},{$container_jumlah[$i]})";
                $result = mysqli_query($connect, $query);
            }
            header("location: transaksi.php?pesan_detail_transaksi");
            die();
        }
    }

    //JIKA TERDAPAT SATU DATA ERROR TAMPILKAN ERROR DENGAN BORDER MERAH
    if (isset($_GET["error"])) {
        $alert = $_GET["alert"];
        $text = $_GET["text"];
        $border_jumlah = $_GET["border"];
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Transaksi</title>
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
                <section class="d-flex align-items-center border-bottom pb-1 d-print-none" style="height: 10%;">
                    <h4 class="text-muted font-weight-bold mb-0">Detail Transaksi</h4>
                </section>
                <!-- PENANDA HALAMAN BREADCRUMB -->
                <section class="my-1 d-print-none">
                    <ol class="breadcrumb p-1 bg-transparent m-0" style="font-size: 12px;">
                        <li class="breadcrumb-item active"><a href="transaksi.php">1</a></li>
                        <li class="breadcrumb-item active">2</a></li>
                    </ol>
                </section>
                <!-- FORM PENDAFTARAN KONSUMEN -->
                <section class="p-4 border">
                    <form action="detail_transaksi.php" method="get">

                        <input type="hidden" name="key" value="foo">
                        <input type="hidden" name="kode" value="<?= $kode_pembeli; ?>">
                        <input type="hidden" name="nama" value="<?= $nama_pembeli; ?>">
                        <input type="hidden" name="alamat" value="<?= $alamat_pembeli; ?>">
                        <input type="hidden" name="jenis_kelamin" value="<?= $jenis_kelamin; ?>">
                        <input type="hidden" name="counter" value="<?= $counter; ?>">

                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="no_nota">No nota</label>
                            <div class="col-2">
                                <input type="text" name="no_nota" id="no_nota" class="form-control" disabled value="<?= $kode_nota; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ml-1" for="nama_pembeli">Nama</label>
                            <div class="col-4">
                                <input type="text" name="nama_pembeli" placeholder="jane doe" id="nama_pembeli" class="form-control" disabled value="<?= $nama_pembeli ?>">
                            </div>
                        </div>
                        <?php
                            $time = date("Y-m-d",time());
                            $query = "SELECT kode, nama FROM obat WHERE stok != 0 AND tgl_kadaluarsa != '{$time}' ";

                            for ($i=1; $i <= $counter; $i++) { 
                                echo "<div class=\"form-group row\">";
                                echo "<label class=\"col-2 col-form-label ml-1\">{$i}</label>";
                                echo "<div class=\"col-4\">";
                                echo "<select name=\"obat_{$i}\" class=\"custom-select\">";
                                    $result = mysqli_query($connect, $query);
                                    while ($hasil_query = mysqli_fetch_row($result)) {
                                        echo "<option value=\"{$hasil_query[0]}\">{$hasil_query[1]}</option>";
                                    }
                                echo "</select>";
                                echo "</div>";
                                echo "<div class=\"col-2\">";
                                echo "<input type=\"number\" name=\"jumlah_{$i}\" placeholder=\"jumlah\" class=\"form-control {$border_jumlah}\" min=\"1\" max=\"255\">";
                                echo "</div>";
                                echo "</div>";
                            }
                        ?>
                        <button type="submit" class="btn btn-primary btn-sm" name="tbl_detail_transaksi" value="<?= $i; ?>">Selesai</button>
                        <a href="transaksi.php" class="btn btn-danger btn-sm">Batal</a>
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