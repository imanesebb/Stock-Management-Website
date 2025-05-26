<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: login.html');
    exit();
}

$client_id = $_SESSION['client_id'];
$cart_items = $_SESSION['cart'] ?? [];

if (empty($cart_items)) {
    echo "Votre panier est vide.";
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

$conn->autocommit(FALSE);

try {
    $sql = "INSERT INTO commandeclient (Datecommandecli, Montantcommandecli, Idclient) VALUES (NOW(), 0, '$client_id')";
    if (!$conn->query($sql)) {
        throw new Exception("erreur lors de l'insertion " . $conn->error);
    }
    $order_id = $conn->insert_id;

    $total_price = 0;
    foreach ($cart_items as $item) {
        if (!is_array($item) || !isset($item['product_id'], $item['quantity'])) {
            throw new Exception("structure invalide");
        }

        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        $sql = "SELECT nomcomposant, prixunitairecomp, QuantitestockComp FROM composant WHERE idcomposant = '$product_id'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            throw new Exception("Produit non trouvé.");
        }
        $row = $result->fetch_assoc();
        $unit_price = floatval($row["prixunitairecomp"]);
        $total = $quantity * $unit_price;
        $total_price += $total;

        if ($row['QuantitestockComp'] < $quantity) {
            $product_name = $row['nomcomposant'];
            $current_stock = $row['QuantitestockComp'];
            throw new Exception("Stock insuffisant pour le produit $product_name. Quantité courante: $current_stock.");
        }

        $sql = "INSERT INTO lignecommandeclient (Idcommandecli, Idcomposant, Quantitecomposant) VALUES ('$order_id', '$product_id', '$quantity')";
        if (!$conn->query($sql)) {
            throw new Exception("Error inserting into lignecommandeclient: " . $conn->error);
        }

        $new_stock = $row['QuantitestockComp'] - $quantity;
        $sql = "UPDATE composant SET QuantitestockComp = '$new_stock' WHERE idcomposant = '$product_id'";
        if (!$conn->query($sql)) {
            throw new Exception("Error updating stock: " . $conn->error);
        }
    }

    $sql = "UPDATE commandeclient SET Montantcommandecli = '$total_price' WHERE Idcommandecli = '$order_id'";
    if (!$conn->query($sql)) {
        throw new Exception("Error updating order total: " . $conn->error);
    }

    $conn->commit();
    $_SESSION['success_message'] = "<h2>Commande confirmée!</h2>";
    header('Location: panier.php');
    unset($_SESSION['cart']);
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error_message'] = "Erreur: " . $e->getMessage();
    header('Location: panier.php');
}

$conn->close();
