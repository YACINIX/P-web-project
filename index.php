<?php
require_once __DIR__ . "/Includes/header.php";
require_once __DIR__ . "/Config/db.php";

$n = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
?>

<p>Produits en base : <?= (int)$n ?></p>

<h1>Bienvenue chez Findora</h1>
<p>Voici quelques recommandations du moment.</p>

<section class="reco">
  <ul>
    <li>Produit recommandé #1</li>
    <li>Produit recommandé #2</li>
    <li>Produit recommandé #3</li>
    <li>Produit recommandé #4</li>
  </ul>
</section>

<p><a href="catalog.php">Aller au catalogue →</a></p>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
