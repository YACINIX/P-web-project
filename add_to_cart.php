<?php
require_once __DIR__ . "/Includes/auth.php";
require_login();
require_once __DIR__ . "/Config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: catalog.php");
  exit;
}

$product_id = (int)($_POST["product_id"] ?? 0);
$qty = (int)($_POST["qty"] ?? 1);

if ($qty < 1) $qty = 1;
if ($qty > 99) $qty = 99;

if ($product_id <= 0) {
  header("Location: catalog.php");
  exit;
}

$stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$stmt->execute([$product_id]);
if (!$stmt->fetch()) {
  header("Location: catalog.php");
  exit;
}

if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
  $_SESSION["cart"] = [];
}

$_SESSION["cart"][$product_id] = (int)($_SESSION["cart"][$product_id] ?? 0) + $qty;

$back = (string)($_POST["back"] ?? "catalog.php");
if ($back === "" || preg_match('/^(https?:)?\/\//i', $back) || str_contains($back, "\0")) {
  $back = "catalog.php";
}

header("Location: " . $back);
exit;
