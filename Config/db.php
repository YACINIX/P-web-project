<?php
$host = "localhost";
$dbname = "ecommerce_db";
$user = "root";
$pass = ""; // Wamp souvent vide

try {
  $pdo = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $user,
    $pass,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
} catch (PDOException $e) {
  die("Erreur DB : " . $e->getMessage());
}
