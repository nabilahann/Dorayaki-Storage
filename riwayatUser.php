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

    if ($isAdmin){
        header("Location: riwayatAdmin.php");
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

    // function
    function getRowid($name,$db){
        $a = $db->querySingle("SELECT rowid FROM dorayaki WHERE nama='{$name}'");
        return $a;
    }

    function numRow($q){
        $count = 0;
        while($row = $q->fetchArray()){
            $count = $count+1;
        }
        return $count;
    }

    // open database
    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('database_dorayaki.db');
        }
    }

    $db = new MyDB();

    $q = $db->query("SELECT STRFTIME('%d/%m/%Y', tanggalwaktu)  as tanggal, STRFTIME('%H:%M:%S', tanggalwaktu)  as waktu, username, namavarian, jumlah, totalharga FROM riwayat WHERE username = '{$username}'");
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
    </head>
    <body>
        <div class="wrapper">
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn"><i class="fas fa-bars"></i></label>
                <span><a href="">AnakAyam</a></span>
                <ul>
                    <li><a href="dashboardUser.php">Home<i class="fas fa-home"></i></a></li>
                    <li><a href="">Riwayat<i class="fas fa-history"></i></i></a></li>
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

            <h1>Riwayat Pembelian</h1>
            <div class="container" id="user">
                <?php if (numRow($q) == 0){
                    echo("<h3>Anda belum melakukan perubahan apapun</h3>");
                } else {?>
                    <table>
                        <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Nama varial</th>
                                <th>Jumlah perubahan</th>
                                <th>Total harga</th>
                        </tr>
                    <?php 
                        while($row = $q->fetchArray()){   //Creates a loop to loop through results
                            echo ("<tr>");
                            echo "<td>" . $row['tanggal'] . "</td>
                            <td>" . $row['waktu'] . "</td>
                            <td><a href=detailUser.php?id=".getRowid($row['namavarian'], $db).">".$row['namavarian']."</a></td>
                            <td>" . (-1)* $row['jumlah'] . "</td>
                            <td>" . $row['totalharga'] . "</td>";  
                            echo "</tr>"; 
                        }
                    ?>   
                    </table>      
                <?php } ?>      
            </div>
            <div class="push"></div>
        </div>
        <footer>
            <p>AnakAyam&trade;</p>
        </footer>
    </body>
</html>