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
    
    $key = $_COOKIE["searchKey"];

    //fungsi mencari total baris
    function numRow($q){
        $count = 0;
        while($row = $q->fetchArray()){
            $count = $count+1;
            // echo $row["rowid"];
        }
        return $count;
    }

    //fungsi mendapatkan hasil query
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


    $clause = getWhereClause("nama", $key);
    $result = $db->query("SELECT rowid, * FROM dorayaki WHERE {$clause}");

    //jumlah row yang dihasilkan
    $total_rows = numRow($result);

    //jumlah hasil per halaman
    $rpp = 4;

    // cari jumlah page
    $page = ceil($total_rows/$rpp); // menentukan tampilan pagination
    if ($page < 1){
        $page = 1;
    }
   

    $db->close();

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
        <title>AnakAyam - Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/pencarian.css">
        <link rel="stylesheet" href="css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
        <script>
                var rpp = <?php echo $rpp; ?>; // jumlah hasil per halaman
                var page = <?php echo $page; ?>; // cari jumlah page
                
                function request_page(pn){
                    var results = document.getElementById("hasil-pencarian");
                    var pagination = document.getElementById("paging");

                    results.innerHTML = "loading results ...";
                    var hr = new XMLHttpRequest();
                    hr.open("POST", "ajax/pagination_parser.php", true);
                    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    hr.onreadystatechange = function() {
                        if(hr.readyState == 4 && hr.status == 200) {
                            var dataArray = hr.responseText.split("||");
                            var html_output = "";

                            // atur range dari baris yang akan di tampilkan
                            // yaitu sebanyak $rpp dan dimulai dari ($pn - 1) * $rpp
                            var start = (pn-1)*rpp;
                            var end = start + rpp;
                            for(i = start; i < dataArray.length - 1 && i < end; i++){
                                    var itemArray = dataArray[i].split("|");
                                    var href = "detailAdmin.php?id=" +  itemArray[0];
                                    html_output += "<div class='grid-item'>" + "<div class='center'>" + "<div class='picture'>";
                                    html_output += "<a href="+ href +">"+"<img src="+ "img/flavor/" + itemArray[3] + " alt=" +  itemArray[1] + "></a>";
                                    html_output += "</div>" + "</div>" + "<div class='desc'>";
                                    html_output += "<a href="+ href +"><h2>" + itemArray[1] + "</h2></a><br>" 
                                                    + itemArray[4] + "<br><br>" + "Terjual : " + itemArray[5] + " buah";
                                    html_output += "</div>" + "</div>";
                            }
                            results.innerHTML = html_output;
                        }
                    }
                    hr.send("rpp="+rpp+"&page="+page+"&pn="+pn);

                    var paging_output = "";
                    for(x = 0; x < page; x++){
                        if (x == (pn - 1)){
                            paging_output += '<a class="active" onclick="request_page('+(x+1)+')">'+(x+1)+'</a>';
                        }
                        else {
                            paging_output += '<a onclick="request_page('+(x+1)+')">'+(x+1)+'</a>';
                        }
                    }
                    pagination.innerHTML = paging_output;

                }
                
        </script>
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

            <h1>Pencarian</h1>
            <div class="container">
                Hasil Pencarian Untuk '<?php echo $key;?>' : <br> <br>
                
                <div class="center">
                    <div class="grid-container" id="hasil-pencarian">
                    </div>
                </div> 

                <!-- pagination -->
                <div class="center">
                    <div class="pagination" id="paging">
                    </div>
                </div>
                <script> request_page(1); </script>

             </div>
        </div>

        <footer>
            <p>AnakAyam&trade;</p>
        </footer>
    </body>
</html>