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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projecttst";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchTerm)) {
    $sql = "SELECT idcomposant, nomcomposant, prixunitairecomp, descriptioncomp FROM composant WHERE nomcomposant LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTermLike = "%" . $searchTerm . "%";
    $stmt->bind_param('s', $searchTermLike);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = false;  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="produit.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Recherche</title>
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
                <input type="text"  style="width: 80%;" placeholder="Rechercher..." name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" style="width: 10%;" class="search-button">CHERCHER</button>
            </form>
        </div>
        <a href="panier.php" name='panier' class="cart-link"><i class='bx bx-cart'></i> PANIER</a>
    </nav>
</header>
<br>
<div class="container">
    <?php
    if ($result !== false && $result->num_rows > 0) {
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
    } elseif ($result === false) {
        echo "<p>Entrez un terme de recherche pour afficher les résultats.</p>";
    } else {
        echo "<p>Aucun produit trouvé pour votre recherche.</p>";
    }

    if(isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
    ?>
   
</div>
<center><a href='produit.php' style='width: 10%;' class='btn btn-secondary mt-3'>Retour à l'accueil</a></center>
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
