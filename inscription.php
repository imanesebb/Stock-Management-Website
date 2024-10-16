<?php
if (isset($_POST["submit"])) {
    $nomclient = $_POST["nomclient"];
    $emailclient = $_POST["emailclient"];
    $teleclient = $_POST["teleclient"];
    $adresseclient= $_POST["adresseclient"];
    $loginusername = $_POST["loginusername"];
    $loginmdp = $_POST["loginmdp"];
    $loginmdpconf= $_POST["loginmdpconf"];

    $conn =  mysqli_connect("localhost", "root", "", "projecttst");
    if($conn->connect_error){
        die("could not connect" . $conn->connect_error);
    }
    $errors = [];
    if (empty($nomclient) || empty($emailclient) || empty($teleclient) || empty($loginusername) || empty($loginmdp) || empty($adresseclient)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($emailclient, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email non valide");
    }
    if (strlen($loginmdp) < 4) {
        array_push($errors, "mot de passe tres court ");
    }
    if ($loginmdp !== $loginmdpconf) {
        array_push($errors, "mot de passe incorrect");
    }

    $sql = "SELECT * FROM client WHERE emailclient = '$emailclient'";
    $result = $conn->query($sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, " email exist deja");
    }
    if (count($errors) > 0) {
        foreach ($errors as  $error) {
            echo $error;
        }
    } else {
        $sql = "INSERT INTO login (loginusername, loginmdp) VALUES ('$loginusername', '$loginmdp')";
        if (mysqli_query($conn, $sql)) {
            $idlogin = mysqli_insert_id($conn); 
            $sql_login = "INSERT INTO client (idlogin,nomclient, emailclient, teleclient, adresseclient) VALUES ('$idlogin','$nomclient', '$emailclient', '$teleclient', '$adresseclient')";
            if (mysqli_query($conn, $sql_login)) {
                header("Location: login.html");
                 exit();
            } else {
                echo "Error: " . $sql_login . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="inscription.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
   <div class="wrapper">
    <form action="inscription.php" method="post">
        <h1>Inscription</h1>

        <div class="input-box">
            <div class="input-field">

                <input type="text" placeholder="Nom complet" name="nomclient" required >
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-field">

                <input type="text" placeholder="nom d'utilisateur"  name="loginusername" required >
                <i class='bx bxs-user'></i>
            </div>    
        </div>

        <div class="input-box">
            
            <div class="input-field">

                <input type="email" placeholder="Email" name="emailclient" required >
                <i class='bx bx-envelope'></i>
            </div>
            <div class="input-field">

                <input type="number" placeholder="Telephone" name="teleclient" required >
                <i class='bx bx-phone'></i>
            </div>  
            <div class="input-field">

                <input type="text" placeholder="votre adresse " name="adresseclient" required >
                <i class='bx bx-envelope'></i>
            </div>  
        </div>  
        <div class="input-box">
            <div class="input-field">

                <input type="password" placeholder="mots de passe"  name="loginmdp" required >
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-field">

                <input type="password" placeholder="confirmer mots de passe" name="loginmdpconf" required >
                <i class='bx bxs-lock-alt'></i>
            </div>    
        </div>
    
       <button type="submit" name="submit" class="btn">s'inscrire</button>
       <br> <br>
       

    </form>
    <a href="login.php"><button style="background-color: black; color:white"  class="btn">retour</button></a>
   </div> 
</body>
</html>