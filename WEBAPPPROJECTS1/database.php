<?php
require_once 'config.php';

$db = new SQLite3(DB_PATH);

$db->exec('CREATE TABLE IF NOT EXISTS certifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    issuer TEXT NOT NULL,
    issue_date TEXT NOT NULL,
    expiry_date TEXT NOT NULL,
    file_path TEXT
)');
?>