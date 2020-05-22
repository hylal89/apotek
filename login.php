<?php ob_start();
    if (isset($_POST["login"])) {
        //JIKA USERNAME DAN PASSWORD KOSONG TAMPILKAN TEXT
        if (empty(strip_tags(trim($_POST["user"]))) && empty(strip_tags(trim($_POST["password"])))) {
            $alert = "alert-danger show";
            $text = "Isi username & password untuk masuk ke sistem";
            $nama_pengguna = "";
            $kata_sandi = "";
        }

        else {
            $nama_pengguna = htmlentities(strip_tags(trim($_POST["user"])));
            $kata_sandi = htmlentities(strip_tags(trim($_POST["password"])));

            include("koneksi_database.php");
            $query = "SELECT * FROM user WHERE name = '{$nama_pengguna}' AND password = password('{$kata_sandi}')";
            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) == 0) {
                $alert = "alert-danger show";
                $text = "Akses ke sistem ditolak, periksa username & password anda";
            }

            else {
                header("location: dashboard.php");
                setcookie($nama_pengguna, $kata_sandi);
                die();
            }
            mysqli_close($connect);
        }
    }

    else{
        $alert = "";
        $text = "";
        $nama_pengguna = "";
        $kata_sandi = "";
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body{
            background-image: url("img/icon_medicine.png");
            background-repeat: no-repeat;
            background-size: 37%;
            background-position: 10% 73%;
        }
    </style>
</head>
<body class="" style="height: 100%;">
   <div class="container-fluid p-0" style="height: 100%;">
        <!-- BAGIAN KEPALA APLIKASI -->
        <header style="height: 10%;">
            <nav class="navbar navbar-light bg-light border-bottom" style="height: 100%;">
                <span class="navbar-brand">Apotek</span>
                <!-- NOTIFIKASI -->
                <div class="alert alert-dismissible fade w-75 ml-auto m-0 text-truncate rounded-0 <?php echo $alert; ?> " style="font-size: 13px;">
                    <strong><?php echo $text; ?></strong>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            </nav>
        </header>
        <!-- BADAN APLIKASI -->
        <div class="row mx-0" style="height: 80%;">
            <div class="col-3 m-auto">
                <!-- FORM LOGIN -->
                <form action="login.php" method="post" class="p-4 border text-center rounded shadow-sm">
                    <h6>Admin</h6>
                    <img src="img/icon_admin.png" class="w-25 mb-3">
                    <input type="text" name="user" placeholder="username" class="form-control mb-2 bg-transparent" autofocus autocomplete="off" value="<?= $nama_pengguna; ?>">
                    <input type="password" name="password" placeholder="password" class="form-control mb-2 bg-transparent" value="<?= $kata_sandi; ?>">
                    <button type="submit" class="btn btn-primary btn-block" name="login">Masuk</button>
                    <a href="login.php" class="btn btn-danger btn-block">Batal</a>
                </form>
            </div>
        </div>
        <!-- KAKI PENANDA HAK CIPTA -->
        <footer class="text-center p-3" style="height: 10%;">
            <small>CopyRight &copy; 2019 Klinik Azka || All Rights Reserved</small>
        </footer>
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