<?php
// comment_list.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$limit = isset($_GET['limit']) ? max(1, min(200, (int)$_GET['limit'])) : 100;

$db = new PDO('sqlite:' . __DIR__ . '/comments.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS comments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  ts INTEGER NOT NULL,
  nama TEXT NOT NULL,
  kontak TEXT,
  pesan TEXT NOT NULL,
  ip TEXT
)");

$rows = $db->query("SELECT id, ts, nama, kontak, pesan FROM comments ORDER BY ts DESC LIMIT $limit")
           ->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['ok'=>true,'data'=>$rows], JSON_UNESCAPED_UNICODE);