<?php
    session_start();
    $username = $_SESSION["username"];
    $is_admin = $_SESSION["is_admin"];

    if (!isset($_SESSION["login"])) {
        header("Location: login.php");
        exit;
    }
    if (!$is_admin) {
        header("Location: dashboardUser.php");
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

    // diambil 8 dorayaki dengan jumlah terjual terbanyak dan terurut berdasarkan primary key

    $q =<<<EOF
            SELECT rowid, nama, harga, gambar, terjual 
            FROM dorayaki 
            ORDER BY terjual DESC, rowid ASC
            LIMIT 8;
        EOF;

    $ret = $db->query($q);

    // Kalo ditekan tombol serach akan dibuka halaman pencarian
    if(isset($_POST["search"])){
        $key = $_POST["search"];

        // menyimpan serach key pada cookie
        setcookie("searchKey", $key);

        // $angka = 1;
        // $header = "Location: pencarianAdmin.php?page=".$angka;

        //buka halaman pencarian
        header("Location: pencarianAdmin.php");
        exit();
  
    }

    // $db->close();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>AnakAyam - Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/dashboard.css">
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

            <h1>Dashboard</h1>
            <div class="container">
                <div class="center">
                    <div class="grid-container">
                    <?php
                        while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
                            $src = "img/flavor/";
                            $src .= $row['gambar']; 
                            // echo $row['rowid'];
                    ?>
                        <div class="grid-item">
                            <div class="center">
                                <div class="picture">
                                    <a href='detailAdmin.php?id= <?php echo $row['rowid'];?>'><img src= <?php echo $src;?> 
                                        alt="Gambar Dorayaki" style="height: 200px;"></a> 
                                </div>
                            </div>

                            <div class="desc">
                                <a href='detailAdmin.php?id= <?php echo $row['rowid'];?>'><?php echo $row['nama']; ?> </a><br><br>
                                <?php
                                echo "Rp ".$row['harga']."<br><br>";
                                echo "Terjual : ".$row["terjual"]." buah";
                                ?>
                            </div>
                        </div>

                    <?php
                        }
                    ?>

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