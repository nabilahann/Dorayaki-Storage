<?php
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

    function rowResult($q){
        $output = '';
        while($row = $q->fetchArray()){
            $id = $row["rowid"];
            $nama = $row["nama"];
            $harga = $row["harga"];
            $gambar = $row["gambar"];
            $desc = $row["deskripsi"];
            $terjual = $row["terjual"];

            $output .= $id.'|'.$nama.'|'.$harga.'|'.$gambar.'|'.$desc.'|'.$terjual.'||';
        }
        return $output;
    }

    // Make the script run only if there is a page number posted to this script
    if(isset($_POST['pn'])){
        $rpp = preg_replace('#[^0-9]#', '', $_POST['rpp']);
        $page = preg_replace('#[^0-9]#', '', $_POST['page']);
        $pn = preg_replace('#[^0-9]#', '', $_POST['pn']);

        // pastikan nomor halam yang dibuka tidak lebih kecil dari 1 atau diatas total page
        if ($pn < 1) {
            $pn = 1;
        } else if ($pn > $page) {
            $pn = $page;
        }

        // Connect ke database
        class MyDB extends SQLite3
        {
            function __construct()
            {
                $this->open('../database_dorayaki.db');
            }
        }

        $db = new MyDB();

        $key = $_COOKIE["searchKey"];


        $clause = getWhereClause("nama", $key);
        $result = $db->query("SELECT rowid, * FROM dorayaki WHERE {$clause} ORDER BY terjual DESC, rowid ASC");


        $dataString = rowResult($result);

        // Tutup database connection
        $db->close();

        // Echo the results back to Ajax
        echo $dataString;
        exit();
    }
?>
