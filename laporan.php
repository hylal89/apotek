<?php ob_start();
    //APAKAH COOKIE ADMIN ADA ? JIKA TRUE JALANKAN ELSE, JIKA FALSE REDIRECT KE HALAMAN login.php
    if (isset($_COOKIE["admin"]) == false) {
        header("location: login.php");
        die();
    }

    //INCLUDE FILE KONEKSI DATABASE
    else {
        include("koneksi_database.php");
        $alert = "";
        $text = "";
    }

    //SCRIPT HAPUS DATA PEMBELI
    if (isset($_GET["tbl_hapus_data_pembeli"])) {
        $tbl_hapus_data_pembeli = $_GET["tbl_hapus_data_pembeli"];
        $query = "DELETE FROM pembeli WHERE kode = {$tbl_hapus_data_pembeli}";
        $result = mysqli_query($connect,$query);
        header("location: laporan.php?pesan_hapus_pembeli");
        die();
    }

    //SCRIPT PESAN DATA PEMBELI BERHASIL DIHAPUS
    if (isset($_GET["pesan_hapus_pembeli"])) {
         $text = "Data pembeli berhasil dihapus";
         $alert = "alert-success show";
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan</title>
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
                        <a href="obat.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-pills fa-fw"></i>&nbsp;&nbsp;&nbsp;Obat</a>
                        <a href="laporan.php" class="list-group-item list-group-item-action rounded-0 border-right-0 active"><i class="fa fa-book fa-fw"></i>&nbsp;&nbsp;&nbsp;Laporan</a>
                        <a href="tentang.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-question fa-fw"></i>&nbsp;&nbsp;&nbsp;Tentang</a>
                        <a href="keluar.php" class="list-group-item list-group-item-action rounded-0 border-right-0 text-danger"><i class="fa fa-window-close fa-fw"></i>&nbsp;&nbsp;&nbsp;Keluar</a>
                    </div>
                </section>
            </div>
            <!-- KOLOM SEBELAH KANAN UNTUK ISI KONTEN -->
            <div class="col bg-white px-5 pt-1 border-left border-bottom" style="height: 100%; overflow-x: auto;" id="body">
                <!-- JUDUL KONTEN DAN UNTUK NOTIFIKASI -->
                <section class="mb-3 d-flex align-items-center border-bottom pb-1 d-print-none" style="height: 10%;">
                    <h4 class="text-muted font-weight-bold mb-0">Laporan</h4>
                    <h6 class="text-muted ml-auto align-self-end"><?= date("d-m-Y", time()); ?></h6>
                </section>
                <!-- //JUDUL KETIKA DI PRINT -->
                <section id="judul" class="border-bottom mb-4">
                    <p class="mb-0"><small>KLINIK AZKA</small></>
                    <p class="mb-0"><small>Kp. Bangbayang Rt/Rw. 03/02 Cicurug-Sukabumi</small></>
                    <p class="mb-2"><small>Telp. (0266) 6725182</small></p>
                </section>
                <!-- //SCRIPT TAMPILKAN TABEL PEMBELI -->
                <?php
                    $query = "SELECT DATE_FORMAT(tgl_transaksi,'%d-%m-%Y') FROM transaksi";
                    $result = mysqli_query($connect,$query);
                    $container = mysqli_fetch_row($result);
                    $date = date("d-m-Y",strtotime($container[0]));
                    $no = mysqli_num_rows($result);

                    if ($date < date("d-m-Y",time())) {
                        $query = "SELECT pembeli.kode, pembeli.nama, pembeli.alamat, pembeli.jenis_kelamin, transaksi.tgl_transaksi, SUM(detail_transaksi.jumlah_obat * obat.harga) ";
                        $query .= "INTO OUTFILE '/var/www/html/{$date}.csv' ";
                        $query .= "FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '\"' ";
                        $query .= "LINES TERMINATED BY '\n' ";
                        $query .= "FROM pembeli INNER JOIN transaksi ON pembeli.kode = transaksi.kode_pembeli ";
                        $query .= "INNER JOIN detail_transaksi ON transaksi.nota = detail_transaksi.no_nota ";
                        $query .= "INNER JOIN obat ON detail_transaksi.kode_obat = obat.kode ";
                        $query .= "GROUP BY detail_transaksi.no_nota";
                        $result = mysqli_query($connect,$query);

                        for ($i=1; $i <= $no ; $i++) {
                            $query = "DELETE FROM pembeli WHERE kode = {$i}";
                            $result = mysqli_query($connect,$query);
                        }

                        $query = "SELECT pembeli.kode, pembeli.nama, pembeli.jenis_kelamin, transaksi.tgl_transaksi, SUM(detail_transaksi.jumlah_obat * obat.harga) ";
                        $query .= "FROM pembeli INNER JOIN transaksi ON pembeli.kode = transaksi.kode_pembeli ";
                        $query .= "INNER JOIN detail_transaksi ON transaksi.nota = detail_transaksi.no_nota ";
                        $query .= "INNER JOIN obat ON detail_transaksi.kode_obat = obat.kode ";
                        $query .= "GROUP BY detail_transaksi.no_nota";
                        $result = mysqli_query($connect,$query);

                        if (mysqli_num_rows($result) > 0) {

                            $table = "show";
                        }

                        else {
                            $table = "collapse";
                        }
                    }
                    
                    else {
                        $query = "SELECT pembeli.kode, pembeli.nama, pembeli.jenis_kelamin, transaksi.tgl_transaksi, SUM(detail_transaksi.jumlah_obat * obat.harga) ";
                        $query .= "FROM pembeli INNER JOIN transaksi ON pembeli.kode = transaksi.kode_pembeli ";
                        $query .= "INNER JOIN detail_transaksi ON transaksi.nota = detail_transaksi.no_nota ";
                        $query .= "INNER JOIN obat ON detail_transaksi.kode_obat = obat.kode ";
                        $query .= "GROUP BY detail_transaksi.no_nota";
                        $result = mysqli_query($connect,$query);

                        if (mysqli_num_rows($result) > 0) {

                            $table = "show";
                        }

                        else {
                            $table = "collapse";
                        }
                    }
                ?>
                <!-- TABLE DATA OBAT -->
                <section class="table-responsive <?= $table; ?>">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light text-center">
                            <tr><th>No</th><th>Nama</th><th>Jenis Kelamain</th><th>Waktu</th><th>Total Harga</th><th class="d-print-none">Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 0;
                                while ($transaksi = mysqli_fetch_row($result)) {
                                        $no += 1;
                                        echo "<tr>";
                                        echo "<td class=\"text-center\">{$no}</td>";
                                        echo "<td>{$transaksi[1]}</td>";
                                        echo "<td class=\"text-center\">{$transaksi[2]}</td>";
                                        echo "<td class=\"text-center\">".date("H:i:s",strtotime($transaksi[3]))."</td>";
                                        echo "<td class=\"text-center\">"."Rp. ".number_format($transaksi[4],0,',','.').",-"."</td>";
                                        echo "<td class=\"d-print-none text-center\">";
                                        echo "<a href=\"info_pembeli.php?tbl_info_pembeli={$transaksi[0]}\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-info fa-fw\"></i></a>";
                                        echo "<a href=\"laporan.php?tbl_hapus_data_pembeli={$transaksi[0]}\" class=\"btn btn-danger btn-sm ml-1\"><i class=\"fa fa-trash-alt fa-fw\"></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
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