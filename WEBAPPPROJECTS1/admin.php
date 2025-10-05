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
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Admin Dashboard</h2>
    <p>Manage all users’ certifications and send renewal reminders.</p>
    <ul class="list">
      <li>User1 - AWS Certificate - Expiring Soon</li>
      <li>User2 - Data Analyst - Active</li>
    </ul>
    <a href="dashboard.php" class="btn danger">⬅ Back</a>
  </div>
</body>
</html>