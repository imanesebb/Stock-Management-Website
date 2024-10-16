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

        $query = "SELECT * FROM login WHERE loginusername='$username' AND loginmdp = '$password'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $idlogin = $row['idlogin'];

            $client_query = "SELECT Idclient FROM client WHERE idlogin = '$idlogin'";
            $client_result = $conn->query($client_query);

            if ($client_result->num_rows == 1) {
                $client_row = $client_result->fetch_assoc();
                $_SESSION['client_id'] = $client_row['Idclient']; 

                
                $_SESSION['login_attempts'] = 0;
                $_SESSION['last_attempt_time'] = 0;

                if ($username == "admin" && $password == "admin") {
                    header("Location: adminlog.php");
                    exit();
                } else {
                    header("Location: produit.php");
                    exit();
                }
            } else {
                header("Location: login.PHP");
                exit();
            }
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
    <title>Client Login</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper" style="width: 35%;">
        <form action="login.php"  method="POST">
            <br> <br>
            <h1>Connexion</h1>
          <br>
          <CENTER>  <div  class="input-box">  
                <input style="width: 80%;" type="text" name="username" placeholder="Nom d'utilisateur        " required>
            </div></CENTER>
            
            <CENTER>  <div  class="input-box"> 
             <input style="width: 80%;"  type="password" name="password" placeholder="Mots de passe          " required>
                <br>
               <br>
            </div> </CENTER>
            
            
           <br>
            
            <CENTER><button  style="width: 80%;" type="submit" class="btn">Connexion</button></CENTER> 
            <div class="register-link">
                <p>vous n'avait pas de compte? 
                    <a href="inscription.php">S'inscrire</a>
                    <br><br>
                    <a href="password.php">Mot de Passe Oublié?</a>
                </p>
               
            </div>
            <?php if (isset($error_message)) { echo "<p style='color: red; text-align: center;'>" . htmlspecialchars($error_message) . "</p>"; } ?>
        </form> <center><a href="siteweb.html"><button class="btn" style="width: 30%; background-color: black; color:white; " > Retour </button></a></center> <br>
    
    </div>
    
</body>
</html>
