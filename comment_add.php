<?php
// comment_add.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // kalau domain beda, atur asalnya
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

function h($s){ return trim((string)$s); }

// Baca input (form urlencoded / multipart / JSON)
$nama  = isset($_POST['nama'])  ? $_POST['nama']  : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$wa    = isset($_POST['wa'])    ? $_POST['wa']    : '';
$pesan = isset($_POST['pesan']) ? $_POST['pesan'] : '';

if ($nama==='' && $pesan==='') {
  $raw = json_decode(file_get_contents('php://input'), true) ?: [];
  $nama  = $raw['nama']  ?? '';
  $email = $raw['email'] ?? '';
  $wa    = $raw['wa']    ?? '';
  $pesan = $raw['pesan'] ?? '';
}

$nama  = mb_substr(h($nama), 0, 60);
$email = mb_substr(h($email),0, 80);
$wa    = mb_substr(h($wa),   0, 30);
$pesan = mb_substr(h($pesan),0,500);

if ($nama==='' || $pesan==='') {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'Nama dan pesan wajib diisi']); exit;
}

// ——— SQLite setup ———
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
$db->exec("CREATE INDEX IF NOT EXISTS idx_ts ON comments(ts DESC)");

// Optional: rate limit super sederhana (maks 5 komentar / 5 menit per IP)
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$now = time();
$sth = $db->prepare("SELECT COUNT(*) FROM comments WHERE ip = :ip AND ts > :ts");
$sth->execute([':ip'=>$ip, ':ts'=>$now-300]);
if ($sth->fetchColumn() >= 5) {
  http_response_code(429);
  echo json_encode(['ok'=>false,'error'=>'Terlalu sering. Coba lagi nanti.']); exit;
}

// Simpan
$kontak = $email . (($email && $wa) ? ' • ' : '') . $wa;
$ins = $db->prepare("INSERT INTO comments (ts,nama,kontak,pesan,ip) VALUES (:ts,:nama,:kontak,:pesan,:ip)");
$ins->execute([
  ':ts'=>$now, ':nama'=>$nama, ':kontak'=>$kontak, ':pesan'=>$pesan, ':ip'=>$ip
]);

echo json_encode(['ok'=>true]);