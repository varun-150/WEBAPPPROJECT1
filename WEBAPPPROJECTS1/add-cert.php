<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// This block will be executed when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // an include to the database is needed
    include 'database.php';

    $name = $_POST['name'];
    $issuer = $_POST['issuer'];
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];
    $file_path = null;

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_path = $target_dir . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $file_path);
    }

    $stmt = $db->prepare('INSERT INTO certifications (name, issuer, issue_date, expiry_date, file_path) VALUES (:name, :issuer, :issue_date, :expiry_date, :file_path)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':issuer', $issuer, SQLITE3_TEXT);
    $stmt->bindValue(':issue_date', $issue_date, SQLITE3_TEXT);
    $stmt->bindValue(':expiry_date', $expiry_date, SQLITE3_TEXT);
    $stmt->bindValue(':file_path', $file_path, $file_path === null ? SQLITE3_NULL : SQLITE3_TEXT);
    $stmt->execute();

    header('Location: dashboard.php');
    exit; // It's a good practice to exit after a redirect
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Certification</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Add Certification</h2>
    <form action="add-cert.php" method="post" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Certificate Name" required><br>
      <input type="text" name="issuer" placeholder="Issuer" required><br>
      <input type="date" name="issue_date" placeholder="Issue Date" required><br>
      <input type="date" name="expiry_date" placeholder="Expiry Date" required><br>
      <input type="file" name="file"><br>
      <button type="submit" class="btn">Save</button>
    </form>
    <br>
    <a href="dashboard.php" class="btn danger">⬅ Back</a>
  </div>
</body>
</html>