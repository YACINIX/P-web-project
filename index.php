<?php
require_once __DIR__ . "/Includes/header.php";
require_once __DIR__ . "/Config/db.php";

$reco = $pdo->query("SELECT id, name, description, price FROM products ORDER BY id DESC LIMIT 4")->fetchAll();
?>

<section class="hero">
  <h1 align="center" >Bienvenue sur Findora</h1>
  <p align="center" >L'endroit où vous trouverez tout ce que vous souhaités. Découvrez !</p>
  <a class="btn" align= "center" href="catalog.php"> Voir le catalogue</a>
</section>



<h2 class="section-title">Recommandations :</h2>

<div class="grid">
  <?php foreach ($reco as $p): ?>
    <div class="card">
      <h3><?= htmlspecialchars($p["name"]) ?></h3>
      <p><?= htmlspecialchars($p["description"] ?? "") ?></p>
      <div class="price"><?= number_format((float)$p["price"], 2) ?> DA</div>
      <p style="margin-top:10px;">
        <a class="btn" href="produit.php?id=<?= (int)$p["id"] ?>">Voir</a>
      </p>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
