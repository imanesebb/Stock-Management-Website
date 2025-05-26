<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: siteweb.html');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: siteweb.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="produit.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>boutique</title>
</head>
<body>
<header>
    <br>
    <a href="?logout=true">
        <button type="button" class="btnl"><i class='bx bx-log-out'></i>déconnexion</button>
    </a>
    <nav>
        <h3>E-PHARMA <i class='bx bxs-heart-circle'></i></h3>
        <div class="search-container">
    <form action="search.php" style="width: 100%;" method="get">
        <input type="text"  style="width: 80%;" placeholder="Rechercher..."  name="search">
        <button type="submit"  style="width: 10%;" class="search-button">CHERCHER</button>
    </form>
</div>
        <a href="panier.php" name='panier' class="cart-link"><i class='bx bx-cart'></i> PANIER</a>
    </nav>
</header>
<br>
<div class="container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projecttst";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT idcomposant, nomcomposant, prixunitairecomp, descriptioncomp FROM composant";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='composant-item'>";
            echo "<h1>" . htmlspecialchars($row["nomcomposant"]) . "</h1>";
            echo "<img src='showimage.php?id=" . htmlspecialchars($row["idcomposant"]) . "' alt='" . htmlspecialchars($row["nomcomposant"]) . "'>";
            echo "<h3>" . htmlspecialchars($row["prixunitairecomp"]) . " MAD</h3>";
            echo "<button class='btn' onclick='toggleDescription(this)'>Voir la description</button>";
            echo "<p class='description' style='display: none;'>" . htmlspecialchars($row["descriptioncomp"]) . "</p>";
            echo "<form action='add_to_cart.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row["idcomposant"]) . "'>";
            echo "<input class='input' type='number' name='quantity' placeholder='quantite' style='text-align: center' required>";
            echo "<button class='btna' type='submit' name='cart'><i class='bx bx-cart-add'></i></button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>
</div>

<script>
function toggleDescription(button) {
    var description = button.nextElementSibling;
    if (description.style.display === 'none') {
        description.style.display = 'block';
        button.textContent = 'Masquer la description';
    } else {
        description.style.display = 'none';
        button.textContent = 'Voir la description';
    }
}
</script>
</body>
</html>
