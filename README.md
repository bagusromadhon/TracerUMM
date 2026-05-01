# 🎓 Crimson Tracker — Hybrid Alumni Command Center

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)
![Status](https://img.shields.io/badge/Status-Production%20Ready-green?style=for-the-badge)

*Sistem Pelacakan & Validasi Rekam Jejak Karir Alumni — Universitas Muhammadiyah Malang*

</div>

---

## 📋 Tentang Proyek

**Crimson Tracker** adalah sistem administrasi berbasis web yang dirancang untuk mendukung program **Tracer Study** di Universitas Muhammadiyah Malang (UMM). Sistem ini menggabungkan data historis internal (survei alumni) dengan kumpulan data eksternal yang telah dikumpulkan melalui proses ***offline web scraping & data mining***, membentuk satu *command center* yang terintegrasi.

Dasbor admin memungkinkan pengelola untuk memantau, mencari, dan memvalidasi rekam jejak karir alumni dari puluhan ribu data secara efisien melalui antarmuka modern yang intuitif.

---

## ✨ Fitur Unggulan

| Fitur | Deskripsi |
|---|---|
| 🔄 **Hybrid Data Engine** | Mengintegrasikan data survei internal dengan data hasil *offline mining* untuk profil alumni yang lebih lengkap |
| ✅ **Validasi Bertingkat** | Sistem verifikasi dua lapis (tunggal & massal) dengan konfirmasi password admin |
| ⚡ **Bulk Validation** | Memvalidasi ribuan data dalam satu proses menggunakan optimasi *SQL Array Batching* |
| 📊 **Real-time Analytics** | Dasbor grafik langsung (Chart.js) untuk Status, Top Profesi, Persebaran Fakultas & Tren Tahun |
| 🔍 **Pencarian & Filter** | Pencarian cepat berdasarkan Nama/NIM serta filter Status, Fakultas, dan Tahun Lulus |
| 🛡️ **Defensive Programming** | Perlindungan Anti-XSS, validasi JSON malformed, dan error handling berlapis |
| 🎲 **Smart Data Shuffling** | Pengacakan data berbasis *Session Seed* untuk variasi distribusi yang organik |
| 📱 **Slide-out Drawer** | Panel detail interaktif dengan kalkulator kelengkapan data & masa studi |
| 🌐 **Portabel ke Hosting** | Konfigurasi `.env` terpusat, siap deploy dari XAMPP ke *cloud hosting* |

---

## 🛠️ Tech Stack

### Front-End
| Komponen | Teknologi | Versi |
|---|---|---|
| Struktur Halaman | HTML5 Semantik | 5 |
| Gaya & Animasi | Vanilla CSS3 | 3 |
| Logika Aplikasi | Vanilla JavaScript | ES6+ |
| Grafik Interaktif | Chart.js | ^4 |
| Pop-up Notifikasi | SweetAlert2 | ^11 |
| Ikon | FontAwesome | 6.4.0 |
| Parsing CSV | PapaParse | ^5 |

### Back-End
| Komponen | Teknologi |
|---|---|
| Server-side Script | PHP 8.x |
| Database | MySQL / MariaDB |
| Arsitektur API | RESTful JSON API |
| Konfigurasi | Dotenv (.env) |

---

## 📂 Struktur Direktori

```text
📦 UI-D4 (Root Proyek)
 ┣ 📂 api/
 ┃ ┣ 📜 db.php              ← Koneksi database & parser .env
 ┃ ┣ 📜 login.php           ← Endpoint autentikasi admin
 ┃ ┣ 📜 alumni.php          ← Endpoint pengambilan data + sorting cerdas
 ┃ ┣ 📜 stats.php           ← Endpoint agregat statistik & grafik
 ┃ ┣ 📜 validate.php        ← Endpoint validasi tunggal & massal
 ┃ ┗ 📜 .htaccess           ← Blokir akses browser langsung ke API
 ┣ 📂 css/
 ┃ ┗ 📜 style.css           ← Desain sistem (tema merah-putih UMM)
 ┣ 📂 js/
 ┃ ┣ 📜 config.js           ← Konfigurasi global (kolom, status, URL API)
 ┃ ┣ 📜 utils.js            ← Fungsi utilitas (escapeHTML, format, hash)
 ┃ ┣ 📜 app-ergo.js         ← Logika utama dasbor & hybrid engine
 ┃ ┗ 📜 app.js              ← Logika pendukung (filter, paginasi)
 ┣ 📜 index.html            ← Halaman utama dasbor
 ┣ 📜 .env                  ← Konfigurasi rahasia (JANGAN di-commit!)
 ┣ 📜 .env.production       ← Template konfigurasi hosting
 ┗ 📜 README.md             ← Dokumentasi ini
```

---

## 🚀 Instalasi & Setup Lokal (XAMPP)

### Prasyarat
- [XAMPP](https://www.apachefriends.org/) (PHP 8.x, MySQL)
- Browser modern (Chrome, Firefox, Edge)

### Langkah-langkah

**1. Kloning atau Ekstrak Proyek**
```
Salin folder proyek ke dalam: C:\xampp\htdocs\UI-D4\
```

**2. Siapkan Database**
- Buka XAMPP, nyalakan **Apache** dan **MySQL**.
- Akses `http://localhost/phpmyadmin`.
- Buat database baru dengan nama `umm_data`.
- Klik tab **Import** dan pilih file `umm_data.sql` dari folder proyek.

**3. Konfigurasi File `.env`**

Buat file bernama `.env` di root proyek, isi dengan:
```env
# Database
DB_HOST=localhost
DB_NAME=umm_data
DB_USER=root
DB_PASS=

# Login Admin Dasbor
ADMIN_USERNAME=admin
ADMIN_PASSWORD=password

APP_NAME=Alumni Tracker
```

**4. Akses Aplikasi**
```
Buka browser → http://localhost/UI-D4/
```

---

## 🌍 Panduan Deploy ke Hosting

### Untuk InfinityFree / Hosting Serupa

**Tahap 1: Impor Database**
1. Ekspor database lokal `umm_data` → unduh sebagai `umm_data.sql`.
2. Di phpMyAdmin hosting Anda, pilih database yang tersedia (misal: `if0_xxxxx_alumni`).
3. Hapus baris `CREATE DATABASE` dan `USE` di awal file `.sql` jika ada.
4. Impor file `.sql` tersebut.

**Tahap 2: Upload File**
1. Masuk ke File Manager hosting → buka folder `htdocs`.
2. Upload **seluruh isi** folder proyek (termasuk folder `api/`, `css/`, `js/`).

**Tahap 3: Konfigurasi**
1. Rename file `.env.production` menjadi `.env` di File Manager.
2. Sesuaikan isinya dengan kredensial database hosting Anda.

---

## 🔌 Dokumentasi API Endpoint

### `GET /api/alumni.php`
Mengambil data alumni dengan filter, paginasi, dan pengurutan cerdas.

| Parameter | Tipe | Keterangan |
|---|---|---|
| `page` | `int` | Nomor halaman (default: 1) |
| `limit` | `int` | Jumlah baris per halaman (max: 100) |
| `search` | `string` | Kata kunci pencarian Nama/NIM |
| `status` | `string` | Filter status: `Tervalidasi`, `Perlu Divalidasi`, `Data Abu-Abu` |

**Contoh Respons:**
```json
{
  "data": [ {...}, {...} ],
  "total": 142350,
  "page": 1,
  "totalPages": 4745
}
```

---

### `GET /api/stats.php`
Mengambil ringkasan statistik untuk grafik dasbor.

**Contoh Respons:**
```json
{
  "tervalidasi": 5120,
  "perluDivalidasi": 3000,
  "dataAbuAbu": 134230,
  "perFakultas": [ {"fakultas": "FKIP", "jumlah": 18000} ],
  "perTahun": [ {"tahun": "2020", "jumlah": 12000} ],
  "perJabatan": [ {"posisi": "Software Engineer", "jumlah": 420} ]
}
```

---

### `POST /api/validate.php`
Memvalidasi satu atau banyak alumni sekaligus.

**Payload Validasi Tunggal:**
```json
{
  "nim": "202010370311001",
  "password": "passwordAdmin",
  "enrichedData": { "Tempat Bekerja (Present)": "PT Contoh", ... }
}
```

**Payload Validasi Massal (Bulk):**
```json
{
  "nims": ["2020...", "2019...", "2021..."],
  "password": "passwordAdmin"
}
```

| Kode HTTP | Arti |
|---|---|
| `200 OK` | Validasi berhasil |
| `400 Bad Request` | Parameter tidak lengkap atau JSON tidak valid |
| `401 Unauthorized` | Password admin salah |
| `405 Method Not Allowed` | Bukan request POST |

---

## 🧪 Tabel Pengujian (Test Case)

### Pengujian Fungsional

| ID | Modul | Skenario Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-01 | Autentikasi | Login dengan kredensial benar | user: `admin`, pass: `password` | Masuk ke dasbor, overlay login hilang | ✅ Lulus |
| TC-02 | Autentikasi | Login dengan password salah | user: `admin`, pass: `salah123` | Muncul pesan error "Kredensial tidak valid" | ✅ Lulus |
| TC-03 | Autentikasi | Login dengan field kosong | user: `""`, pass: `""` | Form tidak terkirim, validasi HTML5 aktif | ✅ Lulus |
| TC-04 | Pencarian | Cari alumni berdasarkan nama | Kata kunci: `Budi` | Tabel menampilkan baris yang mengandung "Budi" | ✅ Lulus |
| TC-05 | Pencarian | Cari alumni berdasarkan NIM | Kata kunci: `20201` | Tabel menampilkan data NIM yang cocok | ✅ Lulus |
| TC-06 | Pencarian | Pencarian dengan kata tidak ada | Kata kunci: `xyzabc123` | Tampil pesan "Tidak ada data ditemukan" | ✅ Lulus |
| TC-07 | Filter | Filter berdasarkan status `Tervalidasi` | Dropdown: `Tervalidasi` | Hanya baris berstatus Tervalidasi yang tampil | ✅ Lulus |
| TC-08 | Filter | Filter berdasarkan Fakultas | Dropdown Fakultas: `FKIP` | Hanya data alumni FKIP yang tampil | ✅ Lulus |
| TC-09 | Paginasi | Navigasi halaman berikutnya | Klik tombol `>` | Halaman berpindah, data berubah sesuai offset | ✅ Lulus |
| TC-10 | Drawer | Buka panel detail alumni | Klik baris tabel | Drawer muncul dari kanan berisi data alumni | ✅ Lulus |
| TC-11 | Validasi | Validasi tunggal dengan password benar | Password benar | Status berubah Tervalidasi, checkbox dinonaktifkan | ✅ Lulus |
| TC-12 | Validasi | Validasi tunggal dengan password salah | Password salah | Muncul SweetAlert error "Password salah" | ✅ Lulus |
| TC-13 | Validasi Massal | Pilih semua + validasi massal | Centang `Select All` | Semua data di halaman berhasil divalidasi sekaligus | ✅ Lulus |
| TC-14 | Grafik | Grafik berubah saat filter aktif | Terapkan filter Fakultas | Grafik per-Fakultas & status ikut ter-update | ✅ Lulus |
| TC-15 | Keamanan | Input nama dengan kode HTML | Nama: `<script>alert(1)</script>` | Teks ditampilkan literal, tidak dieksekusi | ✅ Lulus |

---

### Pengujian Non-Fungsional

| ID | Aspek | Metode Pengujian | Tolak Ukur | Status |
|---|---|---|---|---|
| NF-01 | Keamanan (XSS) | Injeksi `<script>` di field nama | Kode tidak tereksekusi di DOM | ✅ Lulus |
| NF-02 | Keamanan (API) | Kirim JSON kosong ke `validate.php` | Server merespons HTTP 400, tidak crash | ✅ Lulus |
| NF-03 | Performa (Massal) | Validasi 1.000 data sekaligus | Selesai < 3 detik, 1 query SQL | ✅ Lulus |
| NF-04 | Portabilitas | Ubah `.env` ke kredensial hosting | Aplikasi berjalan normal di hosting | ✅ Lulus |
| NF-05 | Integritas Data | Coba centang data `Tervalidasi` | Checkbox tidak aktif (disabled) | ✅ Lulus |

---

## 👥 Anggota Tim

| Nama | Peran |
|---|---|
| *[Nama Anggota 1]* | Back-end Developer & Database Administrator |
| *[Nama Anggota 2]* | Front-end Developer & UI Designer |
| *[Nama Anggota 3]* | Data Engineer & Analyst |
| *[Nama Anggota 4]* | Quality Assurance & Documentation |

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademis Tracer Study Universitas Muhammadiyah Malang.  
© 2026 — Universitas Muhammadiyah Malang. All rights reserved.
