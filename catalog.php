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

$q = trim($_GET["q"] ?? "");
$cat = (int)($_GET["cat"] ?? 0);

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$sql = "
  SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name
  FROM products p
  LEFT JOIN categories c ON c.id = p.category_id
  WHERE 1=1
";
$params = [];

if ($cat > 0) {
  $sql .= " AND p.category_id = ? ";
  $params[] = $cat;
}

if ($q !== "") {
  $sql .= " AND (p.name LIKE ? OR p.description LIKE ?) ";
  $like = "%$q%";
  $params[] = $like;
  $params[] = $like;
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<h1 style="text-align:center;">Catalogue</h1>

<form method="get" style="display:flex; gap:10px; flex-wrap:wrap; margin:12px 0; justify-content:center; align-items:center;">
  <input
    type="search"
    name="q"
    value="<?= htmlspecialchars($q) ?>"
    placeholder="Rechercher un produit..."
    style="height:40px; padding:0 12px; border-radius:16px; border:1px solid var(--border);"
  >

  <select name="cat" style="height:40px; padding:0 12px; border-radius:16px; border:1px solid var(--border);">
    <option value="0">Toutes catégories</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int)$c["id"] ?>" <?= ((int)$c["id"] === $cat) ? "selected" : "" ?>>
        <?= htmlspecialchars($c["name"]) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button class="btn" type="submit" style="margin-top:0;">Filtrer</button>
</form>

<p style="color:var(--muted); margin: 6px 0 14px; text-align:center;">
  Résultats : <?= count($products) ?>
</p>

<div class="grid grid-fancy">
  <?php foreach ($products as $p): ?>
    <div class="card reveal">
      <img
        src="<?= htmlspecialchars(product_img_src($p["image"] ?? "")) ?>"
        alt="<?= htmlspecialchars($p["name"]) ?>"
        class="product-img"
      >

      <h3><?= htmlspecialchars($p["name"]) ?></h3>
      <p><?= htmlspecialchars($p["description"] ?? "") ?></p>

      <?php if (!empty($p["category_name"])): ?>
        <p class="rating" style="color:var(--muted); font-size:13px; margin:0;">
          <?= htmlspecialchars($p["category_name"]) ?>
        </p>
      <?php endif; ?>

      <div class="price">
        <?= number_format((float)$p["price"] * $USD_TO_DA, 2) ?> DA
      </div>

      <p class="card-actions">
        <a class="btn" href="produit.php?id=<?= (int)$p["id"] ?>">Voir</a>
      </p>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
