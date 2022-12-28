<?php
    session_start();
    /**
     * Simple example of extending the SQLite3 class and changing the __construct
     * parameters, then using the open method to initialize the DB.
     */
    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('../database_dorayaki.db');
        }
    }

    $db = new MyDB();

    $username = $_REQUEST["username"];

    $query = $db->prepare("SELECT * FROM pengguna WHERE username = :username;");
    $query->bindValue(":username", $username);
    $result = $query->execute();

    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $msg = "Username sudah digunakan";
    }
    else {
        $msg = "Username valid";
    }

    echo $msg;
?>