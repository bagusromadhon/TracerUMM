<?php
/**
 * api/validate.php
 * POST { nim, password }
 * → Update _status = 'Tervalidasi' di database jika password benar
 */
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Baca body JSON
$body = json_decode(file_get_contents('php://input'), true);

// ── Defensive Programming: Validasi JSON ─────────────────────
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Format request tidak valid (JSON malformed).']);
    exit;
}

$password = trim($body['password'] ?? '');
$nim = isset($body['nim']) ? trim($body['nim']) : '';
$nims = isset($body['nims']) && is_array($body['nims']) ? $body['nims'] : [];

// Jika ada nim tunggal, masukkan ke array nims agar prosesnya seragam
if ($nim !== '' && empty($nims)) {
    $nims[] = $nim;
}

// ── Validasi input ────────────────────────────────────────────
if (empty($nims) || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'NIM dan password wajib diisi.']);
    exit;
}

// ── Cek password (diambil dari db.php) ───────────────────────
if ($password !== ADMIN_PASSWORD) {
    http_response_code(401);
    echo json_encode(['error' => 'Password salah.']);
    exit;
}

// ── Update status & simpan data estimasi (jika ada) di database ───
$enriched = $body['enrichedData'] ?? null;

$updateQuery = "UPDATE `alumni` SET `_status` = 'Tervalidasi', `_validated_at` = NOW()";
$params = [];
$types = '';

// Jika frontend mengirim data dummy (hasil estimasi), kita simpan secara permanen
// Catatan: Ini biasanya hanya untuk validasi tunggal, karena bulk validation tidak mengirim enrichedData
if (is_array($enriched) && count($nims) === 1) {
    $fields = [
        'Tempat Bekerja (Present)', 'Posisi Jabatan (Present)', 'Status Pekerjaan (Present)', 'Alamat Bekerja',
        'Tempat Bekerja (Terakhir)', 'Posisi Jabatan (Terakhir)', 'Status Pekerjaan (Terakhir)',
        'Linkedin', 'Instagram', 'TikTok', 'Facebook'
    ];
    foreach ($fields as $f) {
        if (!empty($enriched[$f])) {
            $updateQuery .= ", `$f` = ?";
            $params[] = $enriched[$f];
            $types .= 's';
        }
    }
}

// Tambahkan klausa WHERE IN untuk mendukung validasi massal sekaligus
$placeholders = implode(',', array_fill(0, count($nims), '?'));
$updateQuery .= " WHERE `NIM` IN ($placeholders)";

foreach ($nims as $n) {
    $params[] = $n;
    $types .= 's';
}

$stmt = $conn->prepare($updateQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();

if ($stmt->affected_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => "NIM '$nim' tidak ditemukan."]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'message' => "Alumni NIM $nim berhasil divalidasi.",
    'nim'     => $nim,
], JSON_UNESCAPED_UNICODE);
