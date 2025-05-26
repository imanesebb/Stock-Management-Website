<?php
session_start();

$lockout_time = 60; 
$max_attempts = 3;

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_time = time();
    
    if ($_SESSION['login_attempts'] >= $max_attempts && $current_time - $_SESSION['last_attempt_time'] < $lockout_time) {
        $error_message = "Vous avez été bloqué en raison de plusieur tentatives de connexion infructueuses. Veuillez réessayer après 1 minute";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

       
        $conn = new mysqli("localhost", "root", '', "projecttst");
        if ($conn->connect_error) {
            die("could not connect: " . $conn->connect_error);
        }

        
        $query = "SELECT * FROM login INNER JOIN admin ON login.idlogin = admin.idlogin WHERE loginusername='$username' AND loginmdp = '$password'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
           
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;
            
          
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            
            header("Location: admin.php");
            exit();
        } else {
           
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = $current_time;

            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $error_message = "Vous avez été bloqué en raison de plusieur tentatives de connexion infructueuses. Veuillez réessayer après 1 minute";
            } else {
                $error_message = "nom d'utilisateur ou mot de passe incorrecte";
            }
        }

       
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper" style="width: 35%;">
        <form action="adminlog.php" method="POST">
            <br> <br>
            <h1>Connexion Admin</h1>
            <br>
          <CENTER> <div  class="input-box">
                <input style="width: 85%;" type="text" name="username" placeholder="Nom d'Utilisateur" required>
            </div>
            <div  class="input-box">
                <input style="width: 85%;" type="password"  name="password" placeholder="Mots de Passe" required>
            </div></CENTER>
            <center><button style="width: 85%;" type="submit" class="btn">Connexion</button></center>
            <br>
            <?php if (isset($error_message)) { echo " <center><p style='color: red;'>$error_message</p></center>"; } ?>
        </form> <center><a href="siteweb.html"><button class="btn" style="width: 30%; background-color: black; color:white; " > Retour </button></a></center> <br> <br>    </div>
</body>
</html>
