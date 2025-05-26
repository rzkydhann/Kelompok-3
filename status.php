<?php
  session_start();
  if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Status Proses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow-sm" style="width: 22rem;">
    <div class="card-body text-center">
      <h5 class="card-title">Status Proses</h5>

      <div class="alert alert-success my-3" role="alert">
        ✅ Sudah Selesai
      </div>

      <a href="https://wa.me/62895329398040" 
         target="_blank" 
         class="btn btn-success">
        Hubungi via WhatsApp
      </a>

      <div class="mt-3">
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
      </div>
    </div>
  </div>

</body>
</html>
