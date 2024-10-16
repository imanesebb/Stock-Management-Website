<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $emailclient = $_POST['emailclient'];
    $confirme = $_POST['confirme'];

   
    if ($emailclient !== $confirme) {
        $_SESSION['error_message'] = "Les emails sont différents";
        header("Location: password.php");
        exit();
    }

    $conn = new mysqli("localhost", "root", '', "projecttst");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

  
    $query = "SELECT loginmdp FROM login inner join client on login.idlogin=client.idlogin  WHERE emailclient = '$emailclient'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $password = $row['password'];

       
        $to = $emailclient;
        $subject = "Password Recovery";
        $message = "Your password: $password";
        $headers = "From: imane.sebbar@uit.ac.ma\r\n" .
                   "Reply-To: imane.sebbar@uit.ac.ma\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        
        mail($to, $subject, $message, $headers);

      
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Aucun compte avec cet email";
        header("Location: password.php");
        exit();
    }

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="inscription.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
   <div class="wrapper">
    <form action="password.php" method="post">
        <h1>MOT DE PASSE OUBLIÉ?</h1>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<div class='error-message' style='color:red'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']);
        }
        ?>

        <div class="input-box">
            <div class="input-field">
                <input type="email" placeholder="Email" name="emailclient" required >
                <i class='bx bx-envelope'></i>
            </div>
            <div class="input-field">
                <input type="email" placeholder="Confirmer votre email" name="confirme" required >
                <i class='bx bx-envelope'></i>
            </div>  
        </div>  
        <br>
        <button type="submit" name="submit" class="btn">Envoyer le mots de passe</button>
    </form> <br> <br>
    <center><a href="login.php"><button class="btn" style="width: 30%; background-color: black; color:white; " > Retour </button></a></center>
   </div> 
</body>
</html>
