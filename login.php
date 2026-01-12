<?php
require_once __DIR__ . "/Includes/auth.php";
require_once __DIR__ . "/Config/db.php";

if (!empty($_SESSION["user"])) {
  header("Location: index.php");
  exit;
}

$error = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = (string)($_POST["password"] ?? "");

  $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $u = $stmt->fetch();

  if ($u && password_verify($password, $u["password_hash"])) {
    session_regenerate_id(true);
    $_SESSION["user"] = [
      "id" => (int)$u["id"],
      "username" => (string)$u["username"],
      "role" => (string)$u["role"]
    ];
    header("Location: index.php");
    exit;
  }

  $error = "Identifiants incorrects.";
}

require_once __DIR__ . "/Includes/header.php";
?>

<h1 style="text-align:center;">Connexion</h1>

<?php if ($error): ?>
  <p style="color:red; text-align:center; margin: 0 0 12px;">
    <?= htmlspecialchars($error) ?>
  </p>
<?php endif; ?>

<form method="post" style="max-width:360px; margin: 0 auto;">
  <label>Nom d'utilisateur</label>
  <input
    name="username"
    required
    value="<?= htmlspecialchars($username) ?>"
    style="width:100%; height:40px; margin:6px 0 12px;"
  >

  <label>Mot de passe</label>
  <input
    type="password"
    name="password"
    required
    style="width:100%; height:40px; margin:6px 0 12px;"
  >

  <button class="btn" type="submit" style="margin-top:0; width:100%;">Se connecter</button>

  <p style="text-align:center; margin-top:12px;">
    Pas de compte ? <a href="signup.php">Créer un compte</a>
  </p>
</form>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
