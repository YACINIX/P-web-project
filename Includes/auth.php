<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login(): void {
  if (empty($_SESSION['user'])) {
    header("Location: login.php");
    exit;
  }
}

// Panier toujours prêt
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
