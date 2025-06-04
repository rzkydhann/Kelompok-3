<?php
session_start();
if (isset($_SESSION['username'])) {
  header("Location: status.php");
  exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Cek login sederhana (bisa diganti dengan database)
    if ($username === "admin" && $password === "12345") {
        $_SESSION["username"] = $username;
        header("Location: status.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow-sm p-4" style="width: 22rem;">
    <h4 class="text-center mb-4">Login</h4>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required />
      </div>
      <div class="d-grid">
        <button class="btn btn-primary" type="submit">Login</button>
      </div>
    </form>
  </div>

</body>
</html>
