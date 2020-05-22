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
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
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
                        <a href="dashboard.php" class="list-group-item list-group-item-action rounded-0 border-right-0 active"><i class="fas fa-tachometer-alt fa-fw"></i>&nbsp;&nbsp;&nbsp;Dashboard</a>
                        <a href="transaksi.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-shopping-cart fa-fw"></i>&nbsp;&nbsp;&nbsp;Transaksi</a>
                        <a href="obat.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-pills fa-fw"></i>&nbsp;&nbsp;&nbsp;Obat</a>
                        <a href="laporan.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-book fa-fw"></i>&nbsp;&nbsp;&nbsp;Laporan</a>
                        <a href="tentang.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-question fa-fw"></i>&nbsp;&nbsp;&nbsp;Tentang</a>
                        <a href="keluar.php" class="list-group-item list-group-item-action rounded-0 border-right-0 text-danger"><i class="fa fa-window-close fa-fw"></i>&nbsp;&nbsp;&nbsp;Keluar</a>
                    </div>
                </section>
            </div>
            <!-- KOLOM SEBELAH KANAN UNTUK ISI KONTEN -->
            <div class="col bg-white px-5 pt-3 border-left border-bottom" style="height: 100%; overflow-x: auto;" id="body">
                <div class="row">
                    <?php
                        $time = date("Y-m-d",time());
                        $query = "SELECT COUNT(kode) FROM pembeli INNER JOIN transaksi ON pembeli.kode = transaksi.kode_pembeli WHERE transaksi.tgl_transaksi LIKE '{$time}%'";
                        $result = mysqli_query($connect, $query);
                        $hasil_query = mysqli_fetch_row($result);
                        $pembeli = $hasil_query[0];
                    ?>
                    <div class="col-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <h1 class="mr-auto mb-0"><?= $pembeli ?></h1>
                                    <i class="fa fa-address-card fa-fw text-muted" style="font-size: 50px;"></i>
                                </div>
                                <div>Pembeli hari ini</div>
                            </div>
                            <a href="laporan.php" class="card-footer text-center">Lihat informasi <i class="fa fa-arrow-circle-right fa-fw"></i></a>
                        </div>
                    </div>
                    <?php
                        $time = date("Y-m-d",time());
                        $query = "SELECT COUNT(kode) FROM obat WHERE tgl_kadaluarsa <= '{$time}'";
                        $result = mysqli_query($connect, $query);
                        $hasil_query = mysqli_fetch_row($result);
                        $obat_kadaluarsa = $hasil_query[0];
                    ?>
                    <div class="col-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <h1 class="mr-auto mb-0"><?= $obat_kadaluarsa ?></h1>
                                    <i class="fas fa-ban fa-fw text-muted" style="font-size: 50px;"></i>
                                </div>
                                <div>Obat kadaluarsa</div>
                            </div>
                            <a href="obat_kadaluarsa.php" class="card-footer text-center">Lihat informasi <i class="fa fa-arrow-circle-right fa-fw"></i></a>
                        </div>
                    </div>
                    <?php
                        $query = "SELECT COUNT(kode) FROM obat WHERE stok <= 5";
                        $result = mysqli_query($connect, $query);
                        $hasil_query = mysqli_fetch_row($result);
                        $obat_kurang = $hasil_query[0];
                    ?>
                    <div class="col-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <h1 class="mr-auto mb-0"><?= $obat_kurang ?></h1>
                                    <i class="fas fa-exclamation fa-fw text-muted" style="font-size: 50px;"></i>
                                </div>
                                <div>Obat kurang & Habis</div>
                            </div>
                            <a href="obat_kurang.php" class="card-footer text-center">Lihat informasi <i class="fa fa-arrow-circle-right fa-fw"></i></a>
                        </div>
                    </div>
                </div> <!-- ROW 2 -->
                <div class="row mt-3">
                    <?php
                        $query = "SELECT COUNT(kode) FROM obat";
                        $result = mysqli_query($connect, $query);
                        $hasil_query = mysqli_fetch_row($result);
                        $jumlah_obat = $hasil_query[0];
                    ?>
                    <div class="col-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <h1 class="mr-auto mb-0"><?= $jumlah_obat; ?></h1>
                                    <i class="fa fa-database fa-fw text-muted" style="font-size: 50px;"></i>
                                </div>
                                <div>Obat yang sudah terdaftar</div>
                            </div>
                            <a href="obat.php" class="card-footer text-center">Lihat informasi <i class="fa fa-arrow-circle-right fa-fw"></i></a>
                        </div>
                    </div>
                </div> <!-- ROW 1 -->
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