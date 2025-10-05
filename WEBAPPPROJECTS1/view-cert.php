<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

include 'database.php';

// Fetch certifications
$result = $db->query('SELECT * FROM certifications');

// Helper functions to determine certificate status
function get_status($expiry_date) {
    if (!$expiry_date) {
        return 'active'; // Or 'unknown' if you prefer
    }
    $now = new DateTime();
    $expiry = new DateTime($expiry_date);
    $diff = $now->diff($expiry);

    if ($diff->invert) {
        return 'expired';
    } elseif ($diff->days <= 30) {
        return 'expiring';
    } else {
        return 'active';
    }
}

function get_status_class($status) {
    switch ($status) {
        case 'active': return 'status-active';
        case 'expiring': return 'status-expiring';
        case 'expired': return 'status-expired';
        default: return 'status-unknown';
    }
}

function get_status_text($status) {
    switch ($status) {
        case 'active': return 'Active';
        case 'expiring': return 'Expiring Soon';
        case 'expired': return 'Expired';
        default: return 'Unknown';
    }
}

function format_date($date_string) {
    if (!$date_string) return 'N/A';
    $date = new DateTime($date_string);
    return $date->format('F j, Y');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Certifications</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>My Certifications</h2>
    <div class="certificates-grid" id="certificatesGrid">
      <?php
        $certs = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $certs[] = $row;
        }

        if (empty($certs)):
      ?>
        <div class="no-certificates">
          <div class="no-cert-icon">📜</div>
          <h3>No Certificates Found</h3>
          <p>You haven't added any certificates yet. Click "Add New Certificate" to get started!</p>
        </div>
      <?php else: ?>
        <?php foreach ($certs as $cert): ?>
          <?php $status = get_status($cert['expiry_date']); ?>
          <div class="certificate-card">
            <div class="certificate-image">
              <img src="https://via.placeholder.com/300x200/4A90E2/FFFFFF?text=<?php echo urlencode($cert['name']); ?>" alt="<?php echo htmlspecialchars($cert['name']); ?>" loading="lazy">
              <div class="certificate-overlay">
                <?php if ($cert['file_path']): ?>
                  <a href="<?php echo htmlspecialchars($cert['file_path']); ?>" target="_blank" class="view-btn">👁️ View</a>
                <?php endif; ?>
              </div>
            </div>
            <div class="certificate-info">
              <h3 class="certificate-name"><?php echo htmlspecialchars($cert['name']); ?></h3>
              <p class="certificate-issuer"><?php echo htmlspecialchars($cert['issuer']); ?></p>
              <div class="certificate-details">
                <div class="detail-item">
                  <span class="detail-label">Issue Date:</span>
                  <span class="detail-value"><?php echo format_date($cert['issue_date']); ?></span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Expiry Date:</span>
                  <span class="detail-value"><?php echo format_date($cert['expiry_date']); ?></span>
                </div>
              </div>
              <div class="certificate-status">
                <span class="status-badge <?php echo get_status_class($status); ?>"><?php echo get_status_text($status); ?></span>
              </div>
              <div class="certificate-actions">
                <button class="action-btn edit-btn" disabled>✏️ Edit</button>
                <button class="action-btn delete-btn" disabled>🗑️ Delete</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="actions">
      <a href="add-cert.php" class="btn">+ Add New Certificate</a>
      <a href="dashboard.php" class="btn danger">⬅ Back to Dashboard</a>
    </div>
  </div>
</body>
</html>