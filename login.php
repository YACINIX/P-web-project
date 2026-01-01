<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Si déjà connecté, on va direct au catalogue
if (!empty($_SESSION['user'])) {
  header("Location: catalog.php");
  exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = trim($_POST["password"] ?? "");

   
  $GOOD_USER = "admin";
  $GOOD_PASS = "1234";

  if ($username === $GOOD_USER && $password === $GOOD_PASS) {
    $_SESSION["user"] = ["username" => $username];

    
    if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
      $_SESSION["cart"] = [];
    }

    header("Location: index.php");
    exit;
  } else {
    $error = "Identifiants incorrects.";
  }
}

require_once __DIR__ . "/Includes/header.php";
?>

<h1>Connexion</h1>

<?php if ($error): ?>
  <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
  <label>Username</label><br>
  <input type="text" name="username" required><br><br>

  <label>Password</label><br>
  <input type="password" name="password" required><br><br>

  <button type="submit">Se connecter</button>
</form>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
