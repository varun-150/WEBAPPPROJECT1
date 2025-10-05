<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Welcome to CertTrack</h2>
    <nav>
      <a href="add-cert.php" class="btn">➕ Add Certification</a>
      <a href="view-cert.php" class="btn">📜 View Certifications</a>
      <a href="admin.php" class="btn">👨‍💼 Admin</a>
      <a href="logout.php" class="btn danger">🚪 Logout</a>
    </nav>
  </div>
</body>
</html>