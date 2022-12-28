<?php 
    session_start();

    if (!isset($_SESSION["login"])){
        header("Location: login.php");
        exit;
    }

    // ambil data
    $variant = $_POST["variant"];
    $num = $_POST["num"];
    $menu = $_POST["menu"];
    if ($menu == "pengurangan stok"){
        $num = -1*$num;
    }

    if ($num < 1){
        echo("Masukan anda belum tepat");
        exit();
    }

    // ambil data session
    $username = $_SESSION["username"];

    // class head{
    //     public $nama_varian;
    //     public $jumlah_varian;
    //     public $ip_toko;

    //     public function __construct($nama_varian, $jumlah_varian, $ip_toko){
    //         $this->$nama_varian = $nama_varian;
    //         $this->jumlah_varian = $jumlah_varian;
    //         $this->$ip_toko = $ip_toko;
    //     }
    // }


    //lakukan request
    try{
        $soapclient = new SoapClient("http://localhost:9999/webservice/request?wsdl");
        // $parameters = new stdClass;
        // $parameters->nama_varian = $variant;
        // $parameters->jumlah_varian = $num;
        // $parameters->ip_toko = 10;
        // $parameters = new head($variant, $num, 10)

        $parameters=array('arg0'=>$variant, 'arg1'=>$num, 'arg2'=>10);

        //$result = $soapclient->addRequest(array($parameters));
        // $result = $soapclient->__soapCall("addRequest", $parameters);
        $result = $soapclient->addRequest($parameters);

        //var_dump($result);

        $arrr = get_object_vars($result);
        print($arrr["return"]);

        // $arr = json_decode(json_encode($response), true);
        // print_r($arr)
    }
    catch (Exception $e){
        echo $e->getMessage();
    }


?>