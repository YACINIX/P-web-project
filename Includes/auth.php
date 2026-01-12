<?php
if (session_status() === PHP_SESSION_NONE) {
  ini_set("session.use_strict_mode", "1");
  ini_set("session.cookie_httponly", "1");
  session_start();
}

if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
  $_SESSION["cart"] = [];
}

function require_login(): void {
  if (empty($_SESSION["user"])) {
    header("Location: login.php");
    exit;
  }
}
