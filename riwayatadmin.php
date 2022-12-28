<?php
    session_start();

    // check if has logged in
    if (!isset($_SESSION["login"])){
        header("Location: login.php");
        exit;
    }

    // get session data
    $username = $_SESSION["username"];
    $isAdmin = $_SESSION["is_admin"];

    if (!$isAdmin){
        header("Location: riwayatUser.php");
        exit;
    }

    // Login expired jika tidak aktif selama 30 menit
    if (isset($_SESSION["last_active"])) {
        $seconds_inactive = time() - $_SESSION["last_active"];

        if ($seconds_inactive >= 1800) {
            header("Location: logout.php");
        }
    }
    $_SESSION["last_active"] = time();

    // Kalo ditekan tombol serach akan dibuka halaman pencarian
    if(isset($_POST["search"])){
        $key = $_POST["search"];

        // menyimpan serach key pada cookie
        setcookie("searchKey", $key);

    
        //buka halaman pencarian
        header("Location: pencarianAdmin.php");
        exit();
  
    }


?>

<!DOCTYPE html>
<html>
    <head>
        <title>AnakAyam - Riwayat</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/template.css">
        <link rel="stylesheet" href="css/all.min.css">
        <link rel="stylesheet" href="css/riwayat.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
        <script src="script/riwayat.js"> defer</script>
    </head>
    <body>
        <div class="wrapper">
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn"><i class="fas fa-bars"></i></label>
                <span><a href="">AnakAyam</a></span>
                <ul>
                    <li><a href="dashboardAdmin.php">Home<i class="fas fa-home"></i></a></li>
                    <li><a href="riwayatadmin.php">Riwayat<i class="fas fa-history"></i></i></a></li>
                    <li><a href="tambah_varian.php">Tambah Varian<i class="fas fa-plus"></i></a></li>
                    <li><a href="logout.php">Logout<i class="fas fa-sign-out-alt"></i></a></li>
                    <li><a href=""><?php echo $username;?><i class="fas fa-user-alt"></i></a></li>
                </ul>
            </nav>
            <div class="search-container">
                <div class="search-bar">
                    <form action="" method="POST">
                        <input type="text" name="search" placeholder="Cari varian...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <h1>Riwayat Perubahan - Admin</h1>
            <div class="container" id="big">
                <form id="menu">
                    <input class="btnval" type="button" value="Perubahan stok yang saya lakukan" onclick="ajaxriwayatself()">
                    <input class="btnval" type="button" value="Perubahan stok pada sebuah varian" onclick="showpervariant()">
                </form>
                <div id="search-variant">
                    <h2>Perubahan stok pada sebuah varian</h2>
                    <br>
                    <form>
                        <input type="text" name="search" id="nama" placeholder="Cari varian...">
                        <button type="button" id="searchbtn" onclick="ajaxriwayat()"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div id="isi"></div>
                <form id="menukembali" method="POST" action="">
                    <br><br>
                    <input class="btnval" type="submit" id="backbtn" value="Kembali" onclick="back()">
                </form>
            </div>
            <div class="push"></div>
        </div>
        <footer>
            <p>AnakAyam&trade;</p>
        </footer>
    </body>
</html>