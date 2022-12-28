<?php
    session_start();
    $username = $_SESSION["username"];
    $is_admin = $_SESSION["is_admin"];

    if (!isset($_SESSION["login"])) {
        header("Location: login.php");
        exit;
    }
    
    if ($is_admin) {
        header("Location: dashboardAdmin.php");
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

    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('database_dorayaki.db');
        }
    }

    $db = new MyDB();

    $id = $_GET["id"];

    // echo $id;

    $statement = $db->prepare('SELECT rowid, * FROM dorayaki WHERE rowid = :id;');
    $statement->bindValue(':id', $id);

    $result = $statement->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    $src = "img/flavor/";
    $src .= $row['gambar'];

    
    $db->close();

    //kalo ditekan tombol ubah stok akan diarahkan ke halaman ubah stok
    if(isset($_POST["beli"])){
        $lokasi = "Location: pembelian.php?id=".$id;
        //buka halaman pencarian
        header($lokasi);
        exit();
  
    }

    // Kalo ditekan tombol serach akan dibuka halaman pencarian
    if(isset($_POST["search"])){
        $key = $_POST["search"];

        // menyimpan serach key pada cookie
        setcookie("searchKey", $key);

        // $angka = 1;
        // $header = "Location: pencarianUser.php?page=".$angka;

        //buka halaman pencarian
        header("Location: pencarianUser.php");
        exit();
  
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>AnakAyam - Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/detail.css">
        <link rel="stylesheet" href="css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn"><i class="fas fa-bars"></i></label>
                <span><a href="">AnakAyam</a></span>
                <ul>
                    <li><a href="dashboardUser.php">Home<i class="fas fa-home"></i></a></li>
                    <li><a href="riwayatUser.php">Riwayat<i class="fas fa-history"></i></i></a></li>
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

            <!-- <h1>Judul</h1> -->
            <div class="container">
                <div class="description-detail">
                    <div class="center">
                        <h1> <?php echo $row['nama'];?> </h1>
                    </div>
                    <div class="desc-detail">
                        Terjual : <?php echo $row['terjual'];?> buah <br>
                        <?php echo $row['deskripsi'];?> <br> 
                        Harga : Rp <?php echo $row['harga'];?> <br> 
                        Stok : <?php echo $row['stok'];?> buah
                    </div>

                    <form action="" method="POST">
                        <div class="btn-detail">
                            <button type="submit" name="beli" class="btn-detail-click">BELI</button>
                        </div>
                    </form>

                </div>
                <div class="picture-detail">
                    <div class="center">
                        <img src=<?php echo $src;?> alt="Gambar Dorayaki">
                    </div>
                </div>
            </div>

            <div class="push"></div>
        </div>
        <footer>
            <p>AnakAyam&trade;</p>
        </footer>
    </body>
</html>