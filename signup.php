<?php
require_once __DIR__ . "/Includes/auth.php";
require_once __DIR__ .idaDIR__ . "/Config/db.php";

if (!empty($_SESSION["user"])) {
  header("Location: index.php");
  exit;
}

$error = "";
$success = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = (string)($_POST["password"] ?? "");
  $confirm  = (string)($_POST["confirm"] ?? "");

  if ($username === "" || $password === "" || $confirm === "") {
    $error = "Tous les champs sont obligatoires.";
  } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    $error = "Username: 3-20 caractères (lettres/chiffres/_).";
  } elseif (strlen($password) < 6) {
    $error = "Mot de passe: minimum 6 caractères.";
  } elseif ($password !== $confirm) {
    $error = "Les mots de passe ne correspondent pas.";
  } else {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetch()) {
      $error = "Ce username est déjà utilisé.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'user')");
      $stmt->execute([$username, $hash]);
      $success = "Compte créé ! Tu peux te connecter.";
    }
  }
}

require_once __DIR__ . "/Includes/header.php";
?>

<h1 style="text-align:center;">Créer un compte</h1>

<?php if ($error): ?>
  <p style="color:red; text-align:center; margin: 0 0 12px;">
    <?= htmlspecialchars($error) ?>
  </p>
<?php endif; ?>

<?php if ($success): ?>
  <p style="color:green; text-align:center; margin-top:12px;">
    <?= htmlspecialchars($success) ?>
  </p>

  <p style="text-align:center; margin-top:10px;">
    <a class="btn" href="login.php" style="margin-top:0; display:inline-block;">
      Aller au login
    </a>
  </p>
<?php else: ?>

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

    <label>Confirmez votre mot de passe</label>
    <input
      type="password"
      name="confirm"
      required
      style="width:100%; height:40px; margin:6px 0 12px;"
    >

    <button class="btn" type="submit" style="margin-top:0; width:100%;">Créer le compte</button>

    <p style="margin-top:12px; text-align:center;">
      Déjà un compte ? <a href="login.php">Se connecter</a>
    </p>
  </form>

<?php endif; ?>

<?php require_once __DIR__ . "/Includes/footer.php"; ?>
