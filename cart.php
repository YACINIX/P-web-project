<?php
require_once __DIR__ . "/Includes/auth.php";
require_login();
require_once __DIR__ . "/Config/db.php";
require_once __DIR__ . "/Includes/header.php";

$USD_TO_DA = 130;

if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
  $_SESSION["cart"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = (string)($_POST["action"] ?? "");
  $pid = (int)($_POST["product_id"] ?? 0);

  if ($action === "update" && $pid > 0) {
    $qty = (int)($_POST["qty"] ?? 1);
    if ($qty < 1) $qty = 1;
    if ($qty > 99) $qty = 99;
    $_SESSION["cart"][$pid] = $qty;
  } elseif ($action === "remove" && $pid > 0) {
    unset($_SESSION["cart"][$pid]);
  } elseif ($action === "clear") {
    $_SESSION["cart"] = [];
  }

  header("Location: cart.php");
  exit;
}

$cart = $_SESSION["cart"];
$productIds = array_keys($cart);

$products = [];
if (!empty($productIds)) {
  $placeholders = implode(",", array_fill(0, count($productIds), "?"));
  $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
  $stmt->execute($productIds);

  foreach ($stmt->fetchAll() as $row) {
    $products[(int)$row["id"]] = $row;
  }

  foreach ($productIds as $pid) {
    if (!isset($products[$pid])) {
      unset($_SESSION["cart"][$pid]);
      unset($cart[$pid]);
    }
  }

  $productIds = array_keys($cart);
}

$grandTotal = 0;
?>

<h1>Panier</h1>

<?php if (empty($productIds)): ?>
  <p>Ton panier est vide.</p>
  <a class="btn" href="catalog.php">Aller au catalogue</a>
<?php else: ?>

  <table style="width:100%; border-collapse:collapse; margin-top:12px;">
    <thead>
      <tr style="text-align:left;">
        <th>Produit</th>
        <th>Prix</th>
        <th>Quantité</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($productIds as $pid):
        $name = $products[$pid]["name"];
        $price = (float)$products[$pid]["price"] * $USD_TO_DA;
        $qty = (int)$cart[$pid];
        if ($qty < 1) $qty = 1;
        if ($qty > 99) $qty = 99;

        $total = $price * $qty;
        $grandTotal += $total;
      ?>
        <tr style="border-top:1px solid var(--border);">
          <td><?= htmlspecialchars($name) ?></td>
          <td><?= number_format($price, 2) ?> DA</td>
          <td>
            <form method="post" style="display:flex; gap:8px; align-items:center; margin:0;">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="product_id" value="<?= (int)$pid ?>">
              <input type="number" name="qty" min="1" max="99" value="<?= (int)$qty ?>" style="width:80px;">
              <button class="btn" type="submit" style="margin-top:0;">Update</button>
            </form>
          </td>
          <td><?= number_format($total, 2) ?> DA</td>
          <td>
            <form method="post" style="margin:0;">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="product_id" value="<?= (int)$pid ?>">
              <button class="btn" type="submit" style="margin-top:0;">Remove</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p style="margin-top:14px; font-weight:900;">
    Grand total : <?= number_format($grandTotal, 2) ?> DA
  </p>

  <form method="post" style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
    <input type="hidden" name="action" value="clear">
    <button class="btn" type="submit" style="margin-top:0;">Vider le panier</button>
    <a class="btn" href="catalog.php" style="margin-top:0;">Continuer shopping</a>
  </form>

<?php endif; ?>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
