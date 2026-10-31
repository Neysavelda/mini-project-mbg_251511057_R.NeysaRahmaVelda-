# Aplikasi Pelaporan dan Pemantauan Keamanan Makanan MBG (Makan Bergizi Gratis)

Dokumentasi resmi Mini Project Proyek 3 - Pengembangan Aplikasi Web Berbasis Framework Laravel.

---

## 1. Identitas Mahasiswa

- **Nama Lengkap**: R. Neysa Rahma Velda
- **NIM**: 251511057
- **Kelas**: D3-2B
- **Program Studi**: D3-Teknik Informatika
- **Judul Mini Project**: Pelaporan dan Pemantauan Keamanan Makanan MBG
- **URL Repository**: https://github.com/Neysavelda/mini-project-mbg_251511057_R.NeysaRahmaVelda-
- **Tag Versi Peer Test**: v1.0-peer-test

---

## 2. Deskripsi dan Pemahaman Masalah

### Masalah Utama
Setelah makanan dari program Makan Bergizi Gratis (MBG) dikonsumsi di lingkungan sekolah, keluhan kesehatan atau ketidaksesuaian kualitatif sering kali dilaporkan secara terpisah-pisah oleh masyarakat melalui media yang tidak terstruktur. Hal ini menyebabkan pengelola kesulitan untuk:
1. Mengidentifikasi sekolah dan jadwal/menu distribusi makanan yang berkaitan dengan keluhan secara akurat.
2. Memantau apakah terdapat keluhan berulang (cluster) pada satu sesi distribusi yang sama.
3. Mengetahui status pemeriksaan laporan serta rekapitulasi penanganan secara real-time.

### Solusi dan Fungsi Utama Sistem
Aplikasi Lapor MBG dibangun sebagai platform terpusat yang membantu:
- **Masyarakat/Pelapor**: Membuat laporan keluhan terstruktur yang terhubung secara valid ke data sekolah dan jadwal distribusi makanan, menyertakan bukti pendukung aman (private storage), serta memantau progres penanganan.
- **Pemantau Komunitas**: Meninjau kelengkapan dan keabsahan laporan khusus pada wilayah sekolah pengawasannya serta memberikan catatan evaluasi.
- **Koordinator Komunitas**: Mengelola master data sekolah dan distribusi, melihat dashboard rekapitulasi statistik kejadian/korban terdampak per sekolah, serta mencatat status penerusan laporan ke pihak eksternal.

> **Prinsip Netralitas Sistem**: Aplikasi ini tidak melakukan diagnosis medis otomatis dan tidak menetapkan pihak yang bersalah. Seluruh status penanganan bersifat netral dan objektif.

---

## 3. Matriks Aktor dan Kewenangan Access Control

Sistem membagi pengguna ke dalam 3 peran (Role) utama dengan batasan otorisasi sebagai berikut:

| Aktivitas / Fitur Sistem | Pelapor | Pemantau Komunitas | Koordinator Komunitas |
| :--- | :---: | :---: | :---: |
| Authenticated Login dan Logout | Ya | Ya | Ya |
| Membuat Laporan dan Unggah Bukti Private | Ya | Tidak | Tidak |
| Mengubah Laporan Sendiri (Dikirim / Perlu Dilengkapi) | Ya (BR-01) | Tidak | Tidak |
| Melihat Laporan Milik Sendiri | Ya | Tidak | Tidak |
| Melihat Laporan Pengguna Lain | Tidak | Ya (Terbatas Sekolah Dipantau) | Ya (Seluruh Sekolah) |
| Memberi Catatan dan Mengubah Status Peninjauan | Tidak | Ya (BR-04) | Ya |
| Mencatat Penerusan dan Menutup Laporan | Tidak | Tidak | Ya |
| Melihat Dashboard Rekap dan Statistik | Tidak | Ya | Ya |
| Mengelola Master Data Sekolah dan Distribusi | Tidak | Tidak | Ya (Gate: manage-master-data) |

---

## 4. Arsitektur Data, Relasi, dan Kebijakan Integrity

### Relasi Entitas (ERD)
- **schools -> meal_distributions** (1 : N): Satu sekolah memiliki banyak jadwal penyaluran makanan.
- **meal_distributions -> incident_reports** (1 : N): Satu sesi distribusi dapat dilaporkan oleh beberapa pelapor jika terjadi masalah massal.
- **users -> incident_reports** (1 : N): Menjadi dasar verifikasi ownership dan hak privasi pelapor.
- **incident_reports -> report_notes** (1 : N): Riwayat catatan evaluasi peninjau.
- **schools -> users** (1 : N, Optional): Membatasi Pemantau Komunitas agar hanya dapat melihat laporan pada sekolah yang dipantau (school_id).

### Kebijakan Integritas Foreign Key (Server-Side Integrity)
- **meal_distributions.school_id**: Restrict (Sekolah yang memiliki riwayat distribusi terikat tidak dapat dihapus).
- **incident_reports.meal_distribution_id**: Restrict (Distribusi yang telah dilaporkan terproteksi dari penghapusan).
- **incident_reports.user_id**: Restrict (Akun pengguna yang memiliki riwayat laporan terproteksi).
- **report_notes.incident_report_id**: Cascade (Jika laporan utama dihapus, riwayat catatan otomatis terhapus).
- **users.school_id**: Set Null (Jika master sekolah dihapus, penugasan pemantau diset null).

---

## 5. Aturan Bisnis (Business Rules / BR)

1. **BR-01**: Pelapor hanya dapat mengedit laporannya sendiri saat berstatus Dikirim (submitted) atau Perlu Dilengkapi (needs_info).
2. **BR-02**: Pembatasan Hak Akses Master Data Sekolah dan Distribusi khusus untuk Koordinator via Gate::define('manage-master-data').
3. **BR-03**: Pemantau Komunitas hanya dapat melihat dan meninjau laporan dari sekolah yang diampunya (whereHas('distribution', fn($q) => $q->where('school_id', $user->school_id))).
4. **BR-04**: Perubahan status laporan oleh Pemantau wajib disertai dengan Catatan Peninjau tertulis.
5. **BR-05**: Pelapor dilarang membuat laporan baru pada jadwal distribusi makanan yang sama selama laporan sebelumnya di distribusi tersebut belum Selesai (resolved).

---

## 6. Fitur Keamanan Keberangkatan (Acceptance Criteria / AC)

- **AC-01 (Role Authorization & Guest Guard)**: Pengguna guest yang mencoba mengakses URL terproteksi (seperti /dashboard atau /summary) otomatis dialihkan (redirect) ke /login.
- **AC-04 (Strict File Validation)**: Upload bukti pendukung dibatasi maksimal 2MB dengan ekstensi .jpg, .jpeg, .png, atau .pdf. File .txt atau berkas invalid ditolak oleh validasi server-side.
- **AC-05 (Secure Private Storage Access)**: Berkas bukti disimpan di direktori terproteksi storage/app/private/evidences (bukan public). Akses berkas hanya dapat dipanggil via EvidenceController@show yang memeriksa otorisasi ownership / role, dan mengembalikan respons 403 Forbidden bagi pengguna tidak berhak.
- **AC-09 (Dynamic AJAX Fetch API)**: Form pembuatan laporan memanfaatkan Fetch API JavaScript untuk memuat opsi jadwal distribusi secara dinamis berdasarkan sekolah yang dipilih tanpa reload halaman.
- **AC-12 (Status Observer Log)**: Perubahan status laporan dipantau otomatis oleh IncidentReportObserver dan dicatat secara terstruktur pada berkas log aplikasi (storage/logs/laravel.log).

---

## 7. Akun Demo untuk Pengujian Peer Test

Seluruh akun demo di bawah ini dibuat otomatis melalui seeder (php artisan migrate:fresh --seed):

- **Password Default Seluruh Akun**: Mbg#Demo2026

| Peran Pengguna | Username | Nama Pengguna | Penugasan Sekolah / Keterangan |
| :--- | :--- | :--- | :--- |
| **Koordinator Komunitas** | sita | Ibu Sita | Memegang otorisasi Master Data dan Dashboard Ringkasan (/summary) |
| **Pemantau Komunitas** | dedi | Pak Dedi | Terikat Pengawasan SMP Harapan (school_id: 1) |
| **Pelapor (Masyarakat)** | rina | Bu Rina | Akun Pelapor Utama |
| **Pelapor (Masyarakat)** | andi | Pak Andi | Akun Pelapor Pembanding (Pengujian 403 Access File) |

---

## 8. Panduan Instalasi dan Jalankan Proyek

Lakukan langkah-langkah berikut untuk menjalankan proyek dari clone baru di lingkungan komputer penguji:

### 1. Clone Repository
```powershell
git clone https://github.com/Neysavelda/mini-project-mbg_251511057_R.NeysaRahmaVelda-.git
cd mini-project-mbg_251511057_R.NeysaRahmaVelda-
```

### 2. Install Dependensi Composer
```powershell
composer install
```

### 3. Konfigurasi Environment (.env)
Salin berkas .env.example menjadi .env:
```powershell
copy .env.example .env
```
(Pastikan pada file .env nama database terkonfigurasi: DB_DATABASE=db_mbg dan SESSION_DRIVER=file).

### 4. Generate Application Key
```powershell
php artisan key:generate
```

### 5. Migrasi dan Seed Database
Jalankan migrasi bersih beserta seeder data demo:
```powershell
php artisan migrate:fresh --seed
```

### 6. Jalankan Local Server
```powershell
php artisan serve
```
Aplikasi dapat diakses via browser pada alamat: http://127.0.0.1:8000

> **Catatan Penting Security**: Perintah `php artisan storage:link` TIDAK PERLU dijalankan karena berkas bukti disimpan pada Private Storage terproteksi (AC-05).

---

## 9. Riwayat Commit GitHub

Berikut adalah urutan riwayat commit pengembangan proyek dari awal hingga versi pengujian:

1. e13b387 - chore: initialize Laravel project
2. 534007b - feat: add username and role to users with demo accounts
3. 18606ab - feat: add login and logout with role-aware dashboard
4. bfd27ab - feat: add schema and relationships for reports
5. b6e7a2f - feat: seed schools, distributions, and school-linked monitor
6. 8e99d94 - feat: add school and distribution management for coordinator with gate authorization
7. 16c762a - feat: add incident report form with dynamic AJAX distribution options
8. a6561cd - feat: add secure private storage evidence access with strict authorization rules
9. 22df03d - feat: add report review dashboard for monitors with review notes and status management
10. 8a6261f - feat: add coordinator report summary dashboard with statistics and school aggregates
11. v1.0-peer-test - docs: add README, env config, status observer, and finalize peer test preparation

---
(c) 2026 R. Neysa Rahma Velda (251511057) - Mini Project Proyek 3 MBG.