<?php 
    session_start();

    // function  
    function numRow($q){
        $count = 0;
        while($row = $q->fetchArray()){
            $count = $count+1;
        }
        return $count;
    }

    function getRowid($name,$db){
        $a = $db->querySingle("SELECT rowid FROM dorayaki WHERE nama='{$name}'");
        return $a;
    }

    function getWhereClause($attr, $varian){
        // get keywords
        $v = strtolower($varian);
        $v = trim($v,"dorayaki");
        $va = preg_split("~\s+~",$v);

        // build where clause
        $whereClause = '';
        foreach( $va as $word) {
            $whereClause .= $attr.' LIKE "%' . $word . '%" AND ';
        }
        // Remove last 'AND', add COLLATE NOCASE
        $whereClause = substr($whereClause, 0, -4);
        $whereClause .="COLLATE NOCASE";

        return $whereClause;
    }

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

    // get
    if (isset($_GET["variant"])){
        $variant = $_GET["variant"];

        $clause = getWhereClause("namavarian",$variant);

        $q = $db->query("SELECT STRFTIME('%d/%m/%Y', tanggalwaktu)  as tanggal, STRFTIME('%H:%M:%S', tanggalwaktu)  as waktu, username, namavarian, jumlah, totalharga 
        FROM riwayat WHERE {$clause}");

        if (numRow($q) == 0){
            // cek dulu apakah ada varian itu
            $que = getWhereClause("nama",$variant);
            $s = $db->querySingle("SELECT COUNT(nama) FROM dorayaki WHERE {$que};");
            if ($s > 0){
                $display_string .= "<h3>Belum pernah dilakukan perubahan terhadap dorayaki yang dimaksud</h3>";
            }
            else{
                $display_string .= "<h3>Masukan anda belum tepat. Silahkan ulang memasukkan</h3>";
            }
        }
        else{
            // build result string
            $display_string .= "<hr><br><h2>Hasil pencarian untuk ".$variant."</h2>";
            $display_string .= "<br>";

            $display_string .= "<table>";
            $display_string .= "<tr>";
            $display_string .= "<th>Tanggal</th>";
            $display_string .= "<th>Waktu</th>";
            $display_string .= "<th>Username</th>";
            $display_string .= "<th>Nama varian</th>";
            $display_string .= "<th>Jumlah perubahan</th>";
            $display_string .= "</tr>";

            while($row = $q->fetchArray()){   //Creates a loop to loop through results
                $display_string .= "<tr>";
                $display_string .= "<td>".$row['tanggal']."</td>";
                $display_string .= "<td>".$row['waktu']."</td>";
                $display_string .= "<td>".$row['username']."</td>";
                $display_string .= "<td><a href=detailAdmin.php?id=".getRowid($row['namavarian'], $db).">".$row['namavarian']."</a></td>";
                $display_string .= "<td>".$row['jumlah']."</td>";
                $display_string .= "</tr>"; 
            }

            $display_string .= "</table>";
            }
    }
    else{
        $username = $_SESSION["username"];

        $q = $db->query("SELECT STRFTIME('%d/%m/%Y', tanggalwaktu)  as tanggal, STRFTIME('%H:%M:%S', tanggalwaktu)  as waktu, username, namavarian, jumlah, totalharga 
        FROM riwayat WHERE username = '{$username}'");

        if (numRow($q) == 0){
            $display_string .= "<h3>Anda belum melakukan perubahan apapun</h3>";
        }
        else{
            // build result string
            $display_string .= "<table>";
            $display_string .= "<tr>";
            $display_string .= "<th>Tanggal</th>";
            $display_string .= "<th>Waktu</th>";
            $display_string .= "<th>Nama varian</th>";
            $display_string .= "<th>Jumlah perubahan</th>";
            $display_string .= "</tr>";

            while($row = $q->fetchArray()){   //Creates a loop to loop through results
                $display_string .= "<tr>";
                $display_string .= "<td>".$row['tanggal']."</td>";
                $display_string .= "<td>".$row['waktu']."</td>";
                $display_string .= "<td><a href=detailAdmin.php?id=".getRowid($row['namavarian'], $db).">".$row['namavarian']."</a></td>";
                $display_string .= "<td>".$row['jumlah']."</td>";
                $display_string .= "</tr>"; 
            }
            $display_string .= "</table>";
        }
    }
    
    echo $display_string;
?>