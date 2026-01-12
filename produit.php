<?php
require_once __DIR__ . "/Includes/auth.php";
require_login();
require_once __DIR__ . "/Config/db.php";

$USD_TO_DA = 130;

function product_img_src($img): string {
  $img = trim((string)$img);
  if ($img === "") return "Public/uploads/placeholder.png";
  if (preg_match('/^https?:\/\//i', $img)) return $img;
  return "Public/uploads/" . $img;
}

$id = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare("SELECT id, name, description, price, image FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

require_once __DIR__ . "/Includes/header.php";

if (!$p): ?>
  <h1 style="text-align:center;">Produit introuvable</h1>
  <p style="text-align:center; margin-top:12px;">
    <a class="btn" href="catalog.php" style="margin-top:0;">Retour au catalogue</a>
  </p>
  <?php require_once __DIR__ . "/Includes/footer.php"; ?>
  <?php exit; ?>
<?php endif; ?>

<h1 style="text-align:center; margin-bottom:10px;">
  <?= htmlspecialchars($p["name"]) ?>
</h1>

<img
  src="<?= htmlspecialchars(product_img_src($p["image"] ?? "")) ?>"
  alt="<?= htmlspecialchars($p["name"]) ?>"
  class="product-img-big"
>

<p style="color:var(--muted); text-align:center; max-width:800px; margin: 0 auto;">
  <?= htmlspecialchars($p["description"] ?? "") ?>
</p>

<p class="price" style="text-align:center; margin-top:12px;">
  <?= number_format((float)$p["price"] * $USD_TO_DA, 2) ?> DA
</p>

<form action="add_to_cart.php" method="post" style="margin-top:14px; display:flex; gap:10px; justify-content:center; align-items:center; flex-wrap:wrap;">
  <input type="hidden" name="product_id" value="<?= (int)$p["id"] ?>">
  <input type="hidden" name="back" value="produit.php?id=<?= (int)$p["id"] ?>">

  <label style="font-weight:700;">Quantité :</label>
  <input type="number" name="qty" value="1" min="1" max="99" style="width:90px; height:40px;">

  <button class="btn" type="submit" style="margin-top:0;">Ajouter au panier</button>
</form>

<p style="margin-top:14px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
  <a class="btn" href="cart.php" style="margin-top:0;">Voir le panier</a>
  <a class="btn btn-ghost" href="catalog.php" style="margin-top:0;">Retour au catalogue</a>
</p>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
