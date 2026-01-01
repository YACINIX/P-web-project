<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Compteur panier (même si vide pour l’instant)
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $qty) $cartCount += (int)$qty;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-commerce Project</title>
  <link rel="stylesheet" href="Public/style.css">
</head>
<body>

<header class="site-header">
  <nav class="nav">
    <a href="index.php">Accueil</a>
    <a href="catalog.php">Catalogue</a>
    <a href="cart.php">Panier (<span id="cart-count"><?= $cartCount ?></span>)</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<main class="container">
