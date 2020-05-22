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
    if (isset($_GET["tbl_info_obat"])) {
        $kode_obat = $_GET["tbl_info_obat"];
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

    //TAMPILAN AWAL
    else {
        $kode = "";
        $nama = "";
        $stok = "";
        $harga = "";
        $tanggal = "";
        $catatan = "";
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informasi Obat</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        a:hover{
            text-decoration: none;
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
                <!-- JUDUL KONTEN -->
                <section class="d-flex align-items-center border-bottom pb-1 d-print-none" style="height: 10%;">
                    <h4 class="text-muted font-weight-bold mb-0">Informasi obat</h4>
                </section>
                <!-- PENANDA HALAMAN BREADCRUMB -->
                <section class="my-1 d-print-none">
                    <ol class="breadcrumb p-1 bg-transparent m-0" style="font-size: 12px;">
                        <li class="breadcrumb-item active"><a href="obat.php">Obat</a></li>
                        <li class="breadcrumb-item active">Informasi Obat</a></li>
                    </ol>
                </section>
                <!-- //JUDUL KETIKA DI PRINT -->
                <section id="judul" class="border-bottom mb-4">
                    <p class="mb-0"><small>KLINIK AZKA</small></>
                    <p class="mb-0"><small>Kp. Bangbayang Rt/Rw. 03/02 Cicurug-Sukabumi</small></>
                    <p class="mb-2"><small>Telp. (0266) 6725182</small></p>
                </section>
                <!-- TAMPILAN ISI DATA OBAT -->
                <section class="p-4 mb-3 border">
                    <div class="row mb-3">
                        <div class="col-2">Kode</div>
                        <div class="col"><?= $kode; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">Nama obat</div>
                        <div class="col"><?= $nama; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-2">Catatan</div>
                        <div class="col"><?= $catatan; ?></div>
                    </div>
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