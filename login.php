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
            $this->open('database_dorayaki.db');
        }
    }

    $db = new MyDB();

    if (isset($_SESSION["login"])) {
        if ($_SESSION["is_admin"]) {
            header("Location: dashboardAdmin.php");
            exit;
        }
        else {
            header("Location: dashboardUser.php");
            exit;
        }
    }

    $error = NULL;
    if (isset($_POST['submit'])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $statement = $db->prepare("SELECT * FROM pengguna WHERE username = :username;");
        $statement->bindValue(":username", $username);
        $result = $statement->execute();

        if($row = $result->fetchArray(SQLITE3_ASSOC)) {
            if (password_verify($password, $row["pass"])) {
                $_SESSION["login"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["is_admin"] = $row["is_admin"];
                if ($_SESSION["is_admin"]) {
                    header("Location: dashboardAdmin.php");
                    exit;
                }
                else {
                    header("Location: dashboardUser.php");
                    exit;
                }
            }
            else {
                $error ="Password salah";
            }
        }
        else {
            $error ="Username belum terdaftar";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>AnakAyam - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <nav>
                <span><a href="">AnakAyam</a></span>
            </nav>
            <div class="search-container">
                <p>Welcome to AnakAyam Dorayaki Shop!</p>
            </div>

            <div class="content-container">
                <div class="container">
                    <div class="img-container">
                        <img src="img/resource/logo.png" alt="Logo AnakAyam">
                    </div>
                </div>
                <div class="container">
                    <div class="login">
                        <h1>Login</h1>
                        <form action="" method="POST">
                            <label for="username">Username</label>
                            <br>
                            <input type="text" name="username" required>
                            <br>
                            <label for="password">Password</label>
                            <br>
                            <input type="password" name="password" required>
                            <br>
                            <span><?= $error; ?></span>
                            <br><br>
                            <p>Belum punya akun?</p><a href="signup.php">Daftar</a>
                            <input type="submit" name="submit" value="Login">
                        </form>
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