<?php 
    session_start();

    /* dummy tester */
    // $username = "ayaici";
    // $rowid = 2;

    // check if has logged in
    if (!isset($_SESSION["login"])){
        header("Location: login.php");
        exit;
    }

    // get session data
    $username = $_SESSION["username"];
    $isAdmin = $_SESSION["is_admin"];

    if ($isAdmin){
        header("Location: perubahan.php");
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
        header("Location: pencarianUser.php");
        exit();
  
    }

    // get which dorayaki was selected
    $rowid = $_GET["id"];

    // open database
    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('database_dorayaki.db');
        }
    }

    $db = new MyDB();

    // select dorayaki information

    $q = $db->query("SELECT rowid, nama, deskripsi, harga, stok, gambar FROM dorayaki WHERE rowid = '{$rowid}'");
    $row = $q->fetchArray();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>AnakAyam - Pembelian</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/template.css">
        <link rel="stylesheet" href="css/pembelian.css">
        <link rel="stylesheet" href="css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
        <script src="script/pembelian.js" defer></script>
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

            <h1>Pembelian</h1>
            <div class="contain">
                <div class="col">
                    <img id="gambardorayaki" src=<?= "img/flavor/" . $row["gambar"] ?> alt= <?= $row["nama"] ?>>
                </div>
                <div class="col container">
                    <h2 id="namavarian"><?= $row["nama"] ?></h2>
                    <div class="descriptions">
                            <p class="desc-item">Stok : <span id="valstok"><?= $row["stok"] ?></span></p>
                            <p class="desc-item">Harga : <span><?= "Rp".$row["harga"] ?></span></p>
                            <div class="desc-item" id="jumlahcontrol">
                                <form>
                                    <input type="button" value="-" onclick="sub()">
                                    <input type="text" name="jumlah" id="val" value=0 readonly>
                                    <input type="hidden" id="harga" value=<?= $row["harga"] ?>>
                                    <input type="button" value="+" onclick="add()">
                                </form>
                            </div>
                            <p class="desc-item">
                                Total : <span>Rp</span><span id="total">0</span>
                            </p>
                    </div>
                    <form>
                        <input id="buybtn" type="button" value="BELI" onclick="ajaxpembelian()">
                    </form>
                </div>
            </div>
            <div id="success"></div>

            <div class="push"></div>
        </div>
        <footer>
            <p>AnakAyam&trade;</p>
        </footer>
    </body>
</html>