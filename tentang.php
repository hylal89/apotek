<?php ob_start();
    //APAKAH COOKIE ADMIN ADA ? JIKA TRUE JALANKAN ELSE, JIKA FALSE REDIRECT KE HALAMAN login.php
    if (isset($_COOKIE["admin"]) == false) {
        header("location: login.php");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tentang</title>
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
                        <a href="laporan.php" class="list-group-item list-group-item-action rounded-0 border-right-0"><i class="fa fa-book fa-fw"></i>&nbsp;&nbsp;&nbsp;Laporan</a>
                        <a href="tentang.php" class="list-group-item list-group-item-action rounded-0 border-right-0 active"><i class="fa fa-question fa-fw"></i>&nbsp;&nbsp;&nbsp;Tentang</a>
                        <a href="keluar.php" class="list-group-item list-group-item-action rounded-0 border-right-0 text-danger"><i class="fa fa-window-close fa-fw"></i>&nbsp;&nbsp;&nbsp;Keluar</a>
                    </div>
                </section>
            </div>
            <!-- KOLOM SEBELAH KANAN UNTUK ISI KONTEN -->
            <div class="col bg-white px-5 pt-1 border-left border-bottom" style="height: 100%; overflow-x: auto;" id="body">
                <!-- JUDUL KONTEN -->
                <section class="mb-3 d-flex align-items-center border-bottom pb-1 d-print-none" style="height: 10%;">
                    <h4 class="text-muted font-weight-bold mb-0">Tentang</h4>
                </section>
                <!-- PENJELASAN MENU -->
                <section class="p-4 mb-3 border">
                    <p class="text-justify">
                        Aplikasi berjenis Sistem Informasi ini adalah sebuah aplikasi berbasiskan Web, dimana untuk menjalankannya dibutuhkan Web server, Database server dan Web Browser. Banyak sekali keuntungan yang didapat dari Aplikasi berbasiskan web salah satu-nya rendah resource, tidak membutuhkan Spesifikasi Hardware yang tinggi untuk dapat berjalan, dan Cross-X Platform yang berarti selama perangkat tersebut mempunyai Web Browser Aplikasi ini sudah bisa berjalan. Menggunakan arsitektur Client-Server dan ada pemisahan antara Aplikasi dengan Database dimana jika terdapat kerusakan pada sisi program, data yang sudah masuk pada Database tidak akan rusak.
                    </p>
                    <p>
                        Penulis mengambil kasus untuk pembuatan Sistem Informasi ini dari apa yang penulis kerjakan selama PKL. Penulis menyadari masih banyak kekurangan dari Sistem Informasi ini, Walaupun begitu penulis berharap Sistem Informasi yang sederhana ini bisa sedikit membantu pekerjaan khusunya untuk petugas yang bertugas di Apotek dalam pencatatan Transaksi harian.
                    </p>
                    <p>
                        Adapun fitur-fitur yang tersedia dalam Sistem Informasi ini :
                    </p>
                    <ul>
                        <li>Dashboard</li>
                        Berguna untuk melihat daftar pembeli, obat kadaluarsa, obat kurang dan habis, jumlah obat yang sudah terdaftar.
                    </ul>
                    <ul>
                        <li>Transaksi</li>
                        Memulai transaksi antara pembeli dan petugas apotek.
                    </ul>
                    <ul>
                        <li>Obat</li>
                        Menginput data obat baru, mengedit, informasi obat dan menghapus.
                    </ul>
                    <ul>
                        <li>Laporan</li>
                        Daftar laporan pembeli yang sudah melakukan transaksi dengan petugas apotek beserta total harga seluruh pembeli yang mendaftar, informasi pembeli dan menghapus data pembeli.
                    </ul>
                    <p class="text-right mt-5" style="text-decoration: underline;"><small>Arief Afrilian, 23-Februari-2019</small></p>
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
<?php ob_end_flush(); ?>