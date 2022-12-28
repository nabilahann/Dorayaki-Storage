<?php 
    session_start();

    if (!isset($_SESSION["login"])){
        header("Location: login.php");
        exit;
    }

    // ambil data session
    $username = $_SESSION["username"];
    // $username = "ayaici"; //cuma buat ngetes

    // ambil data
    $variant = $_POST["varian"];
    $num = $_POST["num"];

    // open database
    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('../database_dorayaki.db');
        }
    }

    $db = new MyDB();

    $display_string = "";

    // update database dorayaki  :  dorayaki (nama TEXT, deskripsi TEXT, harga	INTEGER, stok INTEGER, gambar TEXT, terjual INTEGER)
    $query = "UPDATE dorayaki SET stok = stok - {$num}, terjual = terjual + {$num} WHERE nama = '{$variant}';";
    $q = $db->exec($query);

    // get from database
    $q = $db->query("SELECT nama, stok, harga FROM dorayaki WHERE nama= '{$variant}'");

    $row = $q->fetchArray();
    $buy = -1 * $num;
    $total = $row["harga"]*$num;

    // insert into database riwayat
    $db->exec("INSERT INTO riwayat (tanggalwaktu, username, namavarian, jumlah, totalharga) VALUES
    (DATETIME(CURRENT_TIMESTAMP, '+7 hours'), '{$username}', '{$variant}', '{$buy}', '{$total}');");

    // kembalikan hasil ????????
    $display_string .= "Pembelian atas nama ".$username." : ".$variant." sebanyak ".$num." sudah berhasil. Sekarang sisa stok = ".$row["stok"];

    echo $display_string;

?>