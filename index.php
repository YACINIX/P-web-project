<?php
require_once __DIR__ . "/Includes/auth.php";
require_login();
require_once __DIR__ . "/Config/db.php";
require_once __DIR__ . "/Includes/header.php";

$USD_TO_DA = 130;

function product_img_src($img): string {
  $img = trim((string)$img);
  if ($img === "") return "Public/uploads/placeholder.png";
  if (preg_match('/^https?:\/\//i', $img)) return $img;
  return "Public/uploads/" . $img;
}

$reco = $pdo->query("SELECT id, name, description, price, image FROM products ORDER BY id DESC LIMIT 12")->fetchAll();
?>

<section class="landing">
  <div class="landing-bg"></div>

  <div class="landing-content">
    <p class="landing-badge reveal">⚡ Findora • E-commerce</p>

    <h1 class="landing-title reveal">
      Trouve <span class="grad">le bon produit</span> en quelques secondes.
    </h1>

    <p class="landing-sub reveal">
      Un catalogue tech, un panier rapide, et une expérience fluide.
      <span class="typing" data-words='["PC portables","Claviers","Souris","Écrans","Accessoires"]'></span>
    </p>

    <div class="landing-actions reveal">
      <a class="btn" href="catalog.php" style="margin-top:0;">Voir le catalogue</a>
      <a class="btn btn-ghost" href="cart.php" style="margin-top:0;">Aller au panier</a>
    </div>

    <div class="landing-stats reveal">
      <div class="stat">
        <div class="stat-num" data-count="42000">0</div>
        <div class="stat-label">produits importés</div>
      </div>
      <div class="stat">
        <div class="stat-num" data-count="4">0</div>
        <div class="stat-label">catégories principales</div>
      </div>
      <div class="stat">
        <div class="stat-num" data-count="99">0</div>
        <div class="stat-label">% satisfaction</div>
      </div>
    </div>
  </div>

  <div class="landing-card reveal">
    <div class="mini-card">
      <div class="mini-top">
        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
      </div>
      <h3>Recherche instantanée</h3>
      <p>Filtre par catégorie + recherche rapide. Un style clean, comme un vrai site.</p>
      <div class="mini-line"></div>
      <div class="mini-row">
        <span>Catalogue</span>
        <span class="pill">Nouveau</span>
      </div>
      <div class="mini-row">
        <span>Panier</span>
        <span class="pill">Rapide</span>
      </div>
    </div>
  </div>
</section>

<section class="section reveal">
  <h2 class="section-title" style="text-align:center;">Pourquoi Findora ?</h2>

  <div class="features features-center">
    <div class="feature-card reveal" data-feature="0"></div>
    <div class="feature-card reveal" data-feature="1"></div>
    <div class="feature-card reveal" data-feature="2"></div>
  </div>
</section>

<section class="section reveal">
  <h2 class="section-title reco-title reveal">Recommandations</h2>

  <div class="grid grid-fancy reco-grid">
    <?php foreach ($reco as $p): ?>
      <div class="card reco-card reveal">
        <img
          src="<?= htmlspecialchars(product_img_src($p["image"] ?? "")) ?>"
          alt="<?= htmlspecialchars($p["name"]) ?>"
          class="product-img"
        >
        <h3><?= htmlspecialchars($p["name"]) ?></h3>
        <p><?= htmlspecialchars($p["description"] ?? "") ?></p>
        <div class="price">
          <?= number_format((float)$p["price"] * $USD_TO_DA, 2) ?> DA
        </div>
        <p class="card-actions">
          <a class="btn" href="produit.php?id=<?= (int)$p["id"] ?>">Voir</a>
        </p>
      </div>
    <?php endforeach; ?>
  </div>

  <p style="text-align:center; margin-top:14px;">
    <button class="btn" id="reco-more" type="button" style="margin-top:0;">Plus de produits</button>
  </p>
</section>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
