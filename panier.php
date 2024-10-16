<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: login.html');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: siteweb.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel_order'])) {
        unset($_SESSION['cart']);
    } elseif (isset($_POST['remove_item'])) {
        $product_id_to_remove = $_POST['product_id'];
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id_to_remove) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projecttst";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="produit.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>PANIER</title>
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
                <input type="text"  style="width: 80%;" placeholder="Rechercher..." name="search">
                <button type="submit" style="width: 10%;" class="search-button">CHERCHER</button>
            </form>
        </div>
        <a href="panier.php" name='panier' class="cart-link"><i class='bx bx-cart'></i> PANIER</a>
    </nav>
</header>
<br> <br>
<?php
if (isset($_SESSION['error_message'])) {
    echo '<CENTER><div style="color: #8B0000; text-align: center;"><h2>' . $_SESSION['error_message'] . '</h2></div></CENTER>';
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['success_message'])) {
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<br><center><h3>le panier est vide .....</h3></center>";
    echo "<center><a href='produit.php' style='width: 10%;' class='btn btn-secondary mt-3'>Retour à l'accueil</a></center>";
    exit();
}

$cart_items = $_SESSION['cart'];
$total_price = 0;

echo "<h1>Panier:</h1><BR><BR>";
echo "<CENTER><table class='table' border='1'><tr><th>Nom</th><th>Quantité</th><th>Prix Unitaire</th><th>Total</th><th>Actions</th></tr>";

foreach ($cart_items as $item) {
    if (!is_array($item) || !isset($item['product_id'], $item['quantity'])) {
        echo "Invalid cart item structure.<br>";
        continue;
    }

    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    $sql = "SELECT nomcomposant, prixunitairecomp FROM composant WHERE idcomposant = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row["nomcomposant"]);
            $unit_price = floatval($row["prixunitairecomp"]);
            $total = $quantity * $unit_price;
            $total_price += $total;

            echo "<tr>
                    <td>$name</td>
                    <td>$quantity</td>
                    <td>$unit_price MAD</td>
                    <td>$total MAD</td>
                    <td>
                        <form action='' method='post' style='display:inline;'>
                            <input type='hidden' name='product_id' value='$product_id'>
                            <button type='submit' name='remove_item' style='background-color: #dc3545; color: #fff; border: none; padding: 5px 10px; cursor: pointer;'>Supprimer</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "Produit non trouvé.<br>";
    }
}
echo "</table>";
echo "<BR><BR><BR><BR><h2>Total: " . number_format($total_price, 2) . " MAD</h2></CENTER>";
?>
<CENTER>
<form action="confirm_order.php" method="post">
    <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Confirmer la commande</button>
</form>
</CENTER>
<br>
<CENTER>
<form action="" method="post">
    <button type="submit" name="cancel_order" style="padding: 10px 20px; background-color: #dc3545; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Annuler la commande</button>
</form>
</CENTER>
<center><a href='produit.php' style='width: 10%;' class='btn btn-secondary mt-3'>Retour à l'accueil</a></center>
<?php
$conn->close();
?>
</body>
</html>
