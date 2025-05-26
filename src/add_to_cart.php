<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: login.html');
    exit();
}

$client_id = $_SESSION['client_id'];
$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if (!$product_id || !$quantity) {
    header('Location: produit.php');
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


$_SESSION['cart'][] = [
    'product_id' => $product_id,
    'quantity' => $quantity,
];

header('Location: produit.php');
exit();
