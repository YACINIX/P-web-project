<?php
require_once __DIR__ . "/auth.php";

$cartCount = 0;
foreach ($_SESSION["cart"] as $qty) {
  $cartCount += (int)$qty;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Findora</title>

  <link rel="icon" href="Public/icons/Web site Icon.png" type="image/png">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="Public/css/style.css?v=<?= time() ?>">
  <link rel="stylesheet" href="Public/style.css?v=<?= time() ?>">
</head>
<body>

<header class="site-header">
  <nav class="nav">
    <div class="nav-left"></div>

    <div class="nav-center">
      <a href="index.php" class="nav-icon" aria-label="Accueil" title="Accueil">
        <img src="Public/icons/home.png" alt="Accueil">
      </a>

      <a href="catalog.php">Catalogue</a>
      <a href="cart.php">Panier (<span id="cart-count"><?= $cartCount ?></span>)</a>

      <div class="search">
        <button class="search-btn" type="button">Rechercher</button>
        <form class="search-form" action="catalog.php" method="get">
          <input class="search-input" type="search" name="q" placeholder="Rechercher..." />
        </form>
      </div>
    </div>

    <div class="nav-right">
      <?php if (empty($_SESSION["user"])): ?>
        <a href="signup.php">Sign up</a>
        <a href="login.php">Log in</a>
      <?php else: ?>
        <span style="color:var(--muted); margin-right:10px;">
          <?= htmlspecialchars($_SESSION["user"]["username"]) ?>
        </span>
        <a class="nav-logout" href="logout.php">Logout</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

<main class="container">
