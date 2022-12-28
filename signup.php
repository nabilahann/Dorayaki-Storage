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
    if (isset($_POST['submit'])) {
        if ($_COOKIE["emailValid"] != "True" || $_COOKIE["usernameValid"] != "True" || $_COOKIE["passwordValid"] != "True") {
            $error ="Pastikan semua data valid";
        }
        else {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $db->exec("INSERT INTO pengguna (username, email, pass, is_admin) VALUES ('$username','$email','$password', 0)");

            setcookie("emailValid", "False");
            setcookie("usernameValid", "False");
            setcookie("passwordValid", "False");

            $_SESSION["login"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["is_admin"] = 0;
            header("Location: dashboardUser.php");
            exit;
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>AnakAyam - Register</title>
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
                        <h1>Daftar</h1>
                        <form action="" method="POST">
                            <label for="email">Email</label>
                            <br>
                            <input type="text" name="email" id="email" required onkeyup="validateEmail(this.value)">
                            <span id="email_error"></span>
                            <label for="username">Username</label>
                            <br>
                            <input type="text" name="username" id="username" required onkeyup="validateUsername(this.value)">
                            <span id="username_error"></span>
                            <label for="password">Password</label>
                            <br>
                            <input type="password" name="password" id="password" required onkeyup="validatePassword(this.value)">
                            <span id="password_error"></span>
                            <span><?= $error; ?></span>
                            <br>
                            <a href="login.php">Kembali ke login</a>
                            <input type="submit" name="submit" value="Daftar">
                        </form>
                    </div>
                </div>
            </div>

            <div class="push"></div>
        </div>
        <footer>
            <p>AnakAyam&trade;</p>
        </footer>
        <script>
            function validateEmail(str) {
                const regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (str.length == 0) {
                    document.getElementById("email").style.border = "1px solid #b8874f";
                    document.getElementById("email_error").innerHTML = "";
                    document.cookie = "emailValid=False";
                    return;
                }
                else {
                    if (regex.test(str)) {
                        document.getElementById("email").style.border = "1px solid green";
                        document.getElementById("email_error").innerHTML = "";
                        document.cookie = "emailValid=True";
                    }
                    else {
                        document.getElementById("email").style.border = "1px solid red";
                        document.getElementById("email_error").innerHTML = "Email tidak valid";
                        document.cookie = "emailValid=False";
                    }
                }
            }
            function validateUsername(str) {
                if (str.length == 0) {
                    document.getElementById("username").style.border = "1px solid #b8874f";
                    document.getElementById("username_error").innerHTML = "";
                    document.cookie = "usernameValid=False";
                    return;
                }
                else {
                    const xmlhttp = new XMLHttpRequest();
                    xmlhttp.onload = function() {
                        if ("Username valid" == this.responseText) {
                            document.getElementById("username").style.border = "1px solid green";
                            document.getElementById("username_error").innerHTML = "";
                            document.cookie = "usernameValid=True";
                        }
                        else {
                            document.getElementById("username").style.border = "1px solid red";
                            document.getElementById("username_error").innerHTML = this.responseText;
                            document.cookie = "usernameValid=False";
                        }
                    }
                    xmlhttp.open("GET", "ajax/validation.php?username=" + str);
                    xmlhttp.send();
                }
            }
            function validatePassword(str) {
                const regex = /^[a-zA-Z0-9_]*$/;
                if (str.length == 0) {
                    document.getElementById("password").style.border = "1px solid #b8874f";
                    document.getElementById("password_error").innerHTML = "";
                    document.cookie = "passwordValid=False";
                    return;
                }
                else {
                    if (regex.test(str)) {
                        document.getElementById("password").style.border = "1px solid green";
                        document.getElementById("password_error").innerHTML = "";
                        document.cookie = "passwordValid=True";
                    }
                    else {
                        document.getElementById("password").style.border = "1px solid red";
                        document.getElementById("password_error").innerHTML = "Password tidak valid";
                        document.cookie = "passwordValid=False";
                    }
                }
            }
        </script>
    </body>
</html>