<?php
/**
 * api/stats.php
 * GET → {
 *   tervalidasi, perluDivalidasi, dataAbuAbu, total,
 *   perFakultas: [ {fakultas, jumlah}, ... ],
 *   perTahun:    [ {tahun, jumlah}, ... ]
 * }
 */
require_once __DIR__ . '/db.php';

// ── 1. Status counts ─────────────────────────────────────────────
$res = $conn->query("SELECT `_status`, COUNT(*) AS jumlah FROM `alumni` GROUP BY `_status`");
$counts = ['Tervalidasi' => 0, 'Perlu Divalidasi' => 0, 'Data Abu-Abu' => 0];
while ($row = $res->fetch_assoc()) {
    if (array_key_exists($row['_status'], $counts)) {
        $counts[$row['_status']] = (int) $row['jumlah'];
    }
}

// ── 2. Alumni per Fakultas ───────────────────────────────────────
$perFakultas = [];
$resFak = $conn->query(
    "SELECT `Fakultas`, COUNT(*) AS jumlah 
     FROM `alumni`
     WHERE `Fakultas` IS NOT NULL AND `Fakultas` NOT IN ('', '-', 'Tidak Publik', 'Tidak Dicantumkan')
     GROUP BY `Fakultas`
     ORDER BY jumlah DESC
     LIMIT 10"
);
if ($resFak) {
    while ($row = $resFak->fetch_assoc()) {
        $perFakultas[] = ['fakultas' => $row['Fakultas'], 'jumlah' => (int) $row['jumlah']];
    }
}

// ── 3. Alumni per Tahun Lulus ─────────────────────────────────────
// Extract 4-digit year from `Tanggal Lulus` (handles formats like '4 Februari 2017' or '2017')
$perTahun = [];
$resTahun = $conn->query(
    "SELECT 
        SUBSTRING(`Tanggal Lulus`, CHAR_LENGTH(`Tanggal Lulus`) - 3, 4) AS tahun,
        COUNT(*) AS jumlah
     FROM `alumni`
     WHERE `Tanggal Lulus` IS NOT NULL 
       AND `Tanggal Lulus` NOT IN ('', '-', 'Tidak Publik', 'Tidak Dicantumkan')
       AND `Tanggal Lulus` REGEXP '[0-9]{4}$'
     GROUP BY tahun
     ORDER BY tahun ASC
     LIMIT 20"
);
if ($resTahun) {
    while ($row = $resTahun->fetch_assoc()) {
        if (is_numeric($row['tahun']) && (int)$row['tahun'] >= 1990 && (int)$row['tahun'] <= 2030) {
            $perTahun[] = ['tahun' => $row['tahun'], 'jumlah' => (int) $row['jumlah']];
        }
    }
}

// ── 4. Top Posisi Jabatan (Present) ─────────────────────────────────
$perJabatan = [];
$resJabatan = $conn->query(
    "SELECT `Posisi Jabatan (Present)` AS posisi, COUNT(*) AS jumlah
     FROM `alumni`
     WHERE `Posisi Jabatan (Present)` IS NOT NULL 
       AND `Posisi Jabatan (Present)` NOT IN ('', '-', 'Tidak Publik', 'Tidak Dicantumkan')
     GROUP BY `Posisi Jabatan (Present)`
     ORDER BY jumlah DESC
     LIMIT 6"
);
if ($resJabatan) {
    while ($row = $resJabatan->fetch_assoc()) {
        $perJabatan[] = ['posisi' => $row['posisi'], 'jumlah' => (int) $row['jumlah']];
    }
}

$conn->close();

echo json_encode([
    'tervalidasi'     => $counts['Tervalidasi'],
    'perluDivalidasi' => $counts['Perlu Divalidasi'],
    'dataAbuAbu'      => $counts['Data Abu-Abu'],
    'tidakDitemukan'  => 0,
    'total'           => array_sum($counts),
    'perFakultas'     => $perFakultas,
    'perTahun'        => $perTahun,
    'perJabatan'      => $perJabatan,
], JSON_UNESCAPED_UNICODE);

