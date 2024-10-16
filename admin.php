<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: adminlog.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: siteweb.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Interface Admin</title>
    <link rel="stylesheet" href="admin.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
 
    
       <CENTER> <div class="wrapper">
            <h1>Gestion de stock</h1><br>
            <a href="ajoutercomp.php">
                <button class="btn"><i class='bx bx-add-to-queue'></i> Ajouter un produit</button>
            </a><br>
            <a href="supressioncomp.php">
                <button class="btn"><i class='bx bx-trash'></i> Supprimer un élément</button>
            </a><br>
            <a href="modificationcomp.php">
                <button class="btn"><i class='bx bx-edit-alt'></i> Modifier un produit</button>
            </a><br>
            <a href="modificationprod.php">
                <button class="btn"><i class='bx bx-edit-alt'></i> Modifier un composantt</button>
            </a><br>
            <a href="commandeclient.php">
                <button class="btn"><i class='bx bx-add-to-queue'></i> Afficher toutes les commandes</button>
            </a><br>
            <a href="composantdetail.php">
                <button class="btn"><i class='bx bx-add-to-queue'></i> Gérer les stocks</button>
            </a><br>
            <form method="post" action="">
                <button type="submit" name="logout" class="btnl"><i class='bx bx-log-out'></i> Déconnexion</button>
            </form>
        </div></CENTER>
    
</body>
</html>
