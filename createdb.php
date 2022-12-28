<?php
/**
 * Simple example of extending the SQLite3 class and changing the __construct
 * parameters, then using the open method to initialize the DB.
 */
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('database_dorayaki.db');
    }
}

$db = new MyDB();

/* buat tabel pengguna */

// $db->exec('CREATE TABLE pengguna (username TEXT PRIMARY KEY, email TEXT, pass TEXT, is_admin INTEGER)');

// $password1 = password_hash("12345", PASSWORD_DEFAULT);
// $password2 = password_hash("54321", PASSWORD_DEFAULT);
// $password3 = password_hash("doradora", PASSWORD_DEFAULT);
// $password4 = password_hash("12321", PASSWORD_DEFAULT);
// $password5 = password_hash("ayaya", PASSWORD_DEFAULT);

// $db->exec("INSERT INTO pengguna (username, email, pass, is_admin) VALUES 
//     ( 'alifahrb', 'alifahbasyasya@gmail.com',  '$password1', 1),
//     ('pipio', 'piopio@gmail.com', '$password2', 0), 
//     ('dorami', 'doralovers@gmail.com', '$password3', 0),
//     ('ruhiyahfw','faradishi@gmail.com','$password4',1),
//     ('ayaici','ayaichi@gmail.com', '$password5', 0);
// ");

// $q =<<<EOF
//       SELECT * from pengguna;
//     EOF;

// $ret = $db->query($q);
//    while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
//       echo "ID = ". $row['username'] . "\n";
//       echo "NAME = ". $row['email'] ."\n";
//       echo "PASS = ". $row['pass'] ."\n";
//       echo "IS_ADMIN = ".$row['is_admin'] ."<br>";
//    }

// echo ("<hr>");

// /* buat tabel dorayaki */

// $db->exec('CREATE TABLE dorayaki (nama TEXT, deskripsi TEXT, harga	INTEGER, stok INTEGER, gambar TEXT, terjual INTEGER)');
// $db->exec("INSERT INTO dorayaki (nama, deskripsi,harga,stok, gambar ,terjual) VALUES
//     ('Dorayaki Kacang Merah', 'Original taste', 8000, 50, 'redbean.png', 5),
//     ('Dorayaki Cokelat', 'Mmm cokelatnya mantap', 10000, 20, 'choco.png', 0),
//     ('Dorayaki Keju', 'Keju kambing autentik', 10000, 20, 'cheese.png', 0),
//     ('Dorayaki Kopi', 'Untuk kamu yang malas tidur', 10000, 20, 'coffee.png', 0),
//     ('Dorayaki Cokelat Keju', 'Chefs kiss', 12000, 10, 'choco_cheese.png', 0),
//     ('Dorayaki Pempek', 'Rasa kampung halaman', 15000, 5, 'pempek.png', 30)");


// $db->exec("INSERT INTO dorayaki (nama, deskripsi,harga,stok, gambar ,terjual) VALUES
//     ('Dorayaki Kopi Cokelat', 'Rasanya tak terlupakan',15000 ,50 , 'coffee_choco.png',13),
//     ('Dorayaki Kopi Keju', 'Mantep banget pokoknya',18000 ,40 , 'coffee_cheese.png',27),
//     ('Dorayaki Pempek Keju', 'Perpaduan rasa kampung halaman dan italia',18000 ,45 , 'pempek_cheese.png',0),
//     ('Dorayaki Pempek Cokelat', 'Rasanya sangat tidak terduga',17000 ,20 , 'pempek_choco.png',0),
//     ('Dorayaki Pempek Kopi', 'Untuk kamu yang sedang lapar dan mengantuk',19000 , 17, 'pempek_coffee.png',3),
//     ('Dorayaki Pempek Kacang Merah', 'Mengenyangkan, cocok untuk yang lagi lapar',13000 , 17, 'pempek_redbean.png',23)");

// echo "<br>";

// $q =<<<EOF
//       SELECT rowid, * from dorayaki;
//     EOF;

// $ret = $db->query($q);
//    while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
//       echo "ID = ". $row["rowid"] . "\n";
//       echo "NAME = ". $row['nama'] ."\n";
//       echo "DES = ". $row['deskripsi'] ."\n";
//       echo "HARGA = ".$row['harga'] ."\n";
//       echo "STOK = ". $row['stok'] ."\n";
//       echo "GAMBAR = ". $row['gambar'] ."\n";
//       echo "TERJUAL = ". $row['terjual'] ."<br>";
//    }

// echo ("<hr>");

// /* buat tabel riwayat */

// $db->exec('CREATE TABLE riwayat (tanggalwaktu TEXT, username TEXT, namavarian TEXT, jumlah INTEGER, totalharga INTEGER)');
// $db->exec("INSERT INTO riwayat (tanggalwaktu, username, namavarian, jumlah, totalharga) VALUES
//     (CURRENT_TIMESTAMP, 'alifahrb', 'Dorayaki Kacang Merah', 6, 0),
//     (CURRENT_TIMESTAMP, 'ruhiyahfw', 'Dorayaki Pempek', 30, 0),
//     (DATETIME(CURRENT_TIMESTAMP, '+7 hours'), 'ayaici','Dorayaki Kacang Merah',-1,8000);
// ");

// $q =<<<EOF
//       SELECT  * from riwayat;
//     EOF;

// $ret = $db->query($q);
//    while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
//       echo "TANGGALWAKTU = ". $row['tanggalwaktu'] ."\n";
//       echo "NAMA = ". $row['username'] ."\n";
//       echo "VARIAN = ".$row['namavarian'] ."\n";
//       echo "JUMLAH = ". $row['jumlah'] ."\n";
//       echo "TOTAL HARGA = ". $row['totalharga'] ."\n"."<br>";
//    }

    $db->close();

?>