<?php
require_once __DIR__ . "/Config/db.php";

$csvPath = __DIR__ . "/Data/amazon.csv";
$maxRows = 300;
$defaultCategoryName = "Autres";

function clean_price($s): float {
  $s = trim((string)$s);
  $s = str_replace(["$", "€", "£", "DA", "DZD"], "", $s);
  $s = str_replace(",", ".", $s);
  $s = preg_replace("/[^0-9.]/", "", $s);
  return (float)$s;
}

if (!file_exists($csvPath)) {
  exit("CSV introuvable");
}

$fh = fopen($csvPath, "r");
if (!$fh) {
  exit("Impossible d'ouvrir le CSV");
}

$header = fgetcsv($fh);
if (!$header) {
  exit("CSV vide");
}

$cols = array_flip($header);

$nameCol    = $cols["product_title"] ?? null;
$priceCol   = $cols["discounted_price"] ?? ($cols["original_price"] ?? null);
$imgCol     = $cols["product_image_url"] ?? null;
$catCol     = $cols["product_category"] ?? null;
$ratingCol  = $cols["product_rating"] ?? null;
$reviewsCol = $cols["total_reviews"] ?? null;

if ($nameCol === null || $priceCol === null || $imgCol === null) {
  exit("Colonnes obligatoires manquantes");
}

$insCat = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
$getCat = $pdo->prepare("SELECT id FROM categories WHERE name = ?");

$getCat->execute([$defaultCategoryName]);
$defaultCatId = (int)$getCat->fetchColumn();
if ($defaultCatId <= 0) {
  $insCat->execute([$defaultCategoryName]);
  $defaultCatId = (int)$pdo->lastInsertId();
}

$insProd = $pdo->prepare("
  INSERT INTO products (name, description, price, image, category_id)
  VALUES (?, ?, ?, ?, ?)
");

$catCache = [$defaultCategoryName => $defaultCatId];

try {
  $pdo->beginTransaction();

  $imported = 0;

  while (($row = fgetcsv($fh)) !== false) {
    if ($imported >= $maxRows) break;

    $name = trim((string)($row[$nameCol] ?? ""));
    if ($name === "") continue;

    $price = clean_price($row[$priceCol] ?? "");
    if ($price <= 0) $price = 1.00;

    $imageUrl = trim((string)($row[$imgCol] ?? ""));

    $rating = $ratingCol !== null ? trim((string)($row[$ratingCol] ?? "")) : "";
    $reviews = $reviewsCol !== null ? trim((string)($row[$reviewsCol] ?? "")) : "";
    $desc = "Rating: " . ($rating !== "" ? $rating : "N/A") . " | Reviews: " . ($reviews !== "" ? $reviews : "N/A");

    $catId = $defaultCatId;
    if ($catCol !== null) {
      $catName = trim((string)($row[$catCol] ?? ""));
      if ($catName !== "") {
        if (!isset($catCache[$catName])) {
          $getCat->execute([$catName]);
          $found = (int)$getCat->fetchColumn();
          if ($found > 0) {
            $catCache[$catName] = $found;
          } else {
            $insCat->execute([$catName]);
            $catCache[$catName] = (int)$pdo->lastInsertId();
          }
        }
        $catId = $catCache[$catName];
      }
    }

    $insProd->execute([$name, $desc, $price, $imageUrl, $catId]);
    $imported++;
  }

  $pdo->commit();
  fclose($fh);

  echo "✅ Import terminé. Lignes importées: " . $imported;
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  if (is_resource($fh)) fclose($fh);
  exit("Erreur import");
}
