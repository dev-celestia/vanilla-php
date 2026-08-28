# ⚡ Lightweight Native PHP Micro-Framework & Web Starter

> **Micro-framework & starter kit web modular** berbasis **Native PHP 8.x**, **Tailwind CSS v4**, **Alpine.js**, dan **MySQL / MariaDB**. Dirancang untuk menghasilkan website dan aplikasi web modern yang sangat cepat (TTFB < 50ms), hemat memori RAM, bebas dependensi vendor yang berat (*zero bloat*), dan 100% siap dijalankan di berbagai lingkungan hosting (Shared Hosting / cPanel, VPS, Docker, maupun Localhost).

---

## 📑 Daftar Isi

- [🌟 Keunggulan Utama](#-keunggulan-utama)
- [📦 Fitur Bawaan](#-fitur-bawaan)
- [🛠️ Kebutuhan Sistem & Instalasi Cepat](#️-kebutuhan-sistem--instalasi-cepat)
- [⚡ Feature Installer & App Scaffolder](#-feature-installer--app-scaffolder)
- [🔑 Kredensial Admin Default](#-kredensial-admin-default)
- [💻 Panduan Developer & API Helpers](#-panduan-developer--api-helpers)
  - [1. Helper Database & Query](#1-helper-database--query)
  - [2. Helper HTTP Request & Response](#2-helper-http-request--response)
  - [3. Helper Keamanan & CSRF](#3-helper-keamanan--csrf)
  - [4. Helper Format & Sanitasi](#4-helper-format--sanitasi)
  - [5. Komponen UI Primitif Reusable](#5-komponen-ui-primitif-reusable)
- [🎨 Dynamic Theme Engine & Design System](#-dynamic-theme-engine--design-system)
- [📁 Struktur Direktori Proyek](#-struktur-direktori-proyek)
- [⚡ Mode Frontend (Standalone vs Vite HMR)](#-mode-frontend-standalone-vs-vite-hmr)
- [🚀 Panduan Deployment (cPanel / Apache / Nginx)](#-panduan-deployment-cpanel--apache--nginx)
- [📄 Lisensi](#-lisensi)

---

## 🌟 Keunggulan Utama

| Keunggulan | Penjelasan |
| :--- | :--- |
| **🚀 Ultra Fast (Zero Bloat)** | Ditenagai PHP 8 murni tanpa runtime framework yang rumit. TTFB < 50ms dan konsumsi RAM minimal. |
| **🧩 UI Modular (shadcn-style)** | Koleksi primitif komponen UI PHP (`ui_button()`, `ui_card()`, `ui_input()`, `ui_stat_card()`, dll.) yang rapi dan konsisten. |
| **🎨 Dynamic Theme Engine** | 8 preset palet warna primer dan 6 corner radius yang dapat diubah secara instan via panel admin. |
| **📱 Reactive Frontend** | Integrasi Alpine.js untuk interaksi instan tanpa SPA bloat, ditambah Phosphor Icons berbasis font CSS tanpa resiko layout shift (CLS). |
| **🛡️ Keamanan Berlapis** | Koneksi PDO singleton terlindungi *Prepared Statements*, proteksi token CSRF otomatis, Bcrypt password hashing, dan sanitasi XSS. |
| **⚡ Instant Scaffolder** | CLI & Web GUI generator untuk mengekstrak modul toko & admin ke direktori baru dalam hitungan detik. |

---

## 📦 Fitur Bawaan

1. **Storefront & Katalog E-Commerce**:
   - Etalase produk interaktif dengan live filter & pencarian instan.
   - Drawer keranjang belanja reaktif (Alpine.js + LocalStorage).
   - Checkout langsung via WhatsApp otomatis & formulir pesanan terintegrasi.
2. **Dashboard Admin CMS Lengkap (`/admin`)**:
   - Ringkasan statistik & metrik penjualan.
   - Manajemen CRUD Produk, Kategori, dan Riwayat Pesanan.
   - Pengaturan toko, kontak bisnis, dan live customizer tema.
3. **Halaman Pendukung Siap Pakai**:
   - Landing Page / Showcase (`index.php`)
   - Halaman Profil Bisnis (`about.php`)
   - Halaman Kontak & Formulir Pesan (`contact.php`)
   - Detail Produk (`product.php`), Keranjang (`cart.php`), Checkout (`checkout.php`), & Sukses (`order-success.php`).

---

## 🛠️ Kebutuhan Sistem & Instalasi Cepat

### 1. Kebutuhan Sistem
- **PHP**: Versi 8.0 atau lebih baru (ekstensi `pdo_mysql`, `fileinfo`, `mbstring`).
- **Database**: MySQL 5.7+ atau MariaDB 10.3+.
- **Web Server**: Built-in PHP CLI Server, Apache, Nginx, LiteSpeed, atau Caddy.

### 2. Konfigurasi Database
Sesuaikan kredensial database pada file `config/database.php` (atau file `.env`):
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'native_shop');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Inisialisasi Database
Jalankan setup database otomatis menggunakan salah satu cara berikut:
```bash
# Via terminal PHP CLI:
php database/init.php

# Atau via pnpm/npm (jika ada Node.js):
pnpm db:init
```
*Atau buka `http://localhost:8000/database/init.php` langsung melalui peramban browser.*

### 4. Menjalankan Server Lokal
```bash
# Menggunakan built-in PHP server:
php -S 0.0.0.0:8000
```
Buka browser pada: **`http://localhost:8000`**

---

## ⚡ Feature Installer & App Scaffolder

Framework ini menyertakan *Feature Installer & Scaffolder* otomatis untuk menyalin aplikasi bersih (toko online + admin CMS + database) ke folder baru:

### Via Terminal (CLI):
```bash
# 1. Salin modul toko (demo.php sebagai index.php) ke direktori baru:
php scaffold.php ../toko-koleksi --name="Koleksi Fashion"

# 2. Salin tanpa inisialisasi database:
php scaffold.php ../proyek-baru --no-db

# 3. Tampilkan panduan penggunaan:
php scaffold.php --help
```

> **🛡️ Proteksi Keamanan:** Menjalankan `php scaffold.php` tidak akan menghapus atau mengubah file pada direktori template saat ini. Scaffolder secara khusus menyalin dan menyiapkan modul etalase toko ke folder tujuan baru yang Anda tentukan.

### Via Browser (GUI Installer):
Buka `http://localhost:8000/scaffold.php` pada browser, tentukan folder tujuan (misal `../toko-baru`), dan klik **Salin Toko ke Folder Baru**.

---

## 🔑 Kredensial Admin Default

- **URL Login Admin:** `http://localhost:8000/admin/login.php`
- **Username:** `admin`
- **Password:** `password123`

*(Kata sandi dan identitas toko dapat diubah kapan saja di menu **Admin > Pengaturan**).*

---

## 💻 Panduan Developer & API Helpers

### 1. Helper Database & Query
Semua helper database berada di [`helpers/framework.php`](file:///Users/arham/Desktop/project/Native-PHP/helpers/framework.php):

```php
// Mengambil koneksi PDO singleton
$pdo = db();

// Mengambil banyak baris (Fetch All)
$products = db_fetch_all("SELECT * FROM products WHERE is_active = :status ORDER BY id DESC", [
    ':status' => 1
]);

// Mengambil 1 baris record (Fetch Single)
$item = db_fetch("SELECT * FROM products WHERE id = :id", [
    ':id' => $id
]);

// Menjalankan query Insert / Update / Delete
$stmt = db_query("UPDATE products SET stock = stock - :qty WHERE id = :id", [
    ':qty' => 1,
    ':id'  => $id
]);
```

### 2. Helper HTTP Request & Response
```php
// Mengambil input parameter (otomatis support GET, POST, maupun JSON payload)
$search   = request('q', '');
$category = (int)request('cat_id', 0);

// Pengecekan method HTTP & AJAX
if (is_post()) { /* Handle POST request */ }
if (is_ajax()) { /* Handle fetch / AJAX request */ }

// Mengembalikan respons JSON dan terminate
json_response([
    'success' => true,
    'data'    => $products
], 200);

// Pengalihan halaman (Redirect)
redirect(base_url('cart.php'));

// Abort HTTP Status
abort(404, 'Halaman tidak ditemukan');
```

### 3. Helper Keamanan & CSRF
```php
<!-- Pada Form HTML -->
<form method="POST" action="process.php">
    <?= csrf_field() ?>
    <input type="text" name="name" required>
    <button type="submit">Kirim</button>
</form>

<?php
// Pada file pemrosesan (process.php):
if (is_post()) {
    if (!verify_csrf_token(request('csrf_token'))) {
        abort(403, 'Sesi form kadaluarsa atau token CSRF tidak valid.');
    }
    // Lanjutkan proses form yang aman...
}
```

### 4. Helper Format & Sanitasi
Helper berada di [`helpers/format.php`](file:///Users/arham/Desktop/project/Native-PHP/helpers/format.php):
```php
// Format Mata Uang Rupiah
echo format_rupiah(150000); // Output: Rp 150.000

// Sanitasi string anti XSS
echo sanitize($_GET['search']);

// Generate URL Slug
echo slugify("Sepatu Pria Original"); // Output: sepatu-pria-original

// Format Tanggal Indonesia
echo format_date('2026-08-28 20:00:00'); // Output: 28 Agustus 2026, 20:00 WIB
```

### 5. Komponen UI Primitif Reusable
Komponen UI modular tersedia di folder [`components/ui/`](file:///Users/arham/Desktop/project/Native-PHP/components/ui/):

```php
// 1. Tombol & Tautan (Button)
echo ui_button('Beli Sekarang', ['variant' => 'primary', 'icon' => 'shopping-cart']);
echo ui_button('Detail', ['variant' => 'outline', 'href' => base_url('product.php?id=1')]);

// 2. Kartu (Card Container)
echo ui_card([
    'title' => 'Statistik Penjualan',
    'subtitle' => 'Ringkasan bulan ini'
], '<p class="text-sm">Konten di dalam card...</p>');

// 3. Form Inputs
echo ui_input('Nama Lengkap', 'name', ['placeholder' => 'Masukkan nama...', 'required' => true]);
echo ui_textarea('Catatan Pengiriman', 'notes', ['rows' => 3]);
echo ui_select('Kategori', 'category_id', [1 => 'Elektronik', 2 => 'Fashion']);

// 4. Badges & Alerts
echo ui_badge('Stok Tersedia', 'success');
echo ui_alert('Pesanan Anda berhasil dikirim!', 'success');

// 5. Stat Card (Dashboard Metric)
echo ui_stat_card([
    'title' => 'Total Pendapatan',
    'value' => 'Rp 14.500.000',
    'icon'  => 'currency-dollar',
    'trend' => '+12% minggu ini'
]);
```

---

## 🎨 Dynamic Theme Engine & Design System

Framework ini memiliki Theme Token Engine terpusat di [`config/theme.php`](file:///Users/arham/Desktop/project/Native-PHP/config/theme.php) yang terhubung langsung dengan CSS Variables:

- **8 Pilihan Palet Warna**: `emerald`, `blue`, `indigo`, `violet`, `rose`, `amber`, `teal`, `slate`.
- **6 Corner Radius Preset**: `sharp` (0px), `subtle` (6px), `standard` (12px), `soft` (16px), `round` (24px), `pill` (9999px).
- **Desain Modern Apple-Style**: Translucent glassmorphism, crisp hairline borders, dan mikro-interaksi *apple-tap*.

*(Tema dapat diganti secara dinamis melalui menu Admin > Pengaturan).*

---

## 📁 Struktur Direktori Proyek

```text
Native-PHP/
├── config/
│   ├── app.php             # Master bootstrapper & global config loader
│   ├── database.php        # Konfigurasi & singleton koneksi database PDO
│   └── theme.php           # Token Design System, Palet Warna & Corner Radius
├── database/
│   ├── schema.sql          # Struktur skema tabel MySQL & data seed
│   └── init.php            # Script inisialisasi & migrasi otomatis database
├── components/
│   └── ui/                 # Komponen UI Primitif Reusable (shadcn-style)
│       ├── button.php      # Komponen Button & Link
│       ├── card.php        # Komponen Card Surface
│       ├── input.php       # Komponen Input Form
│       ├── textarea.php    # Komponen Textarea
│       ├── select.php      # Komponen Select Dropdown
│       ├── toggle.php      # Komponen Switch Toggle
│       ├── badge.php       # Komponen Badge Status
│       ├── alert.php       # Komponen Banner Notifikasi
│       ├── stat-card.php   # Komponen Metric Card
│       ├── empty-state.php # Komponen Empty State Data
│       ├── breadcrumb.php  # Komponen Breadcrumb
│       ├── product-card.php# Komponen Kartu Produk
│       ├── icon.php        # Komponen Phosphor Icon
│       └── index.php       # Master component loader
├── helpers/
│   ├── framework.php       # Core micro-framework utilities (request, response, db, routing)
│   ├── components.php      # Component bridge loader
│   ├── auth.php            # Guard otentikasi sesi admin
│   ├── csrf.php            # Generator & validator token CSRF
│   ├── format.php          # Formatter Rupiah, tanggal, slug, & sanitasi XSS
│   ├── upload.php          # Helper validasi upload gambar
│   └── vite.php            # Integrasi asset bundling Vite (opsional)
├── includes/
│   ├── header.php          # Header website, navigasi & store Alpine.js
│   ├── footer.php          # Footer website & informasi kontak
│   └── cart_drawer.php     # Slide-over keranjang belanja reaktif
├── admin/
│   ├── index.php           # Dashboard statistik & ringkasan
│   ├── login.php           # Autentikasi login admin
│   ├── logout.php          # Handler logout sesi
│   ├── products.php        # CRUD daftar produk & upload gambar
│   ├── product-form.php    # Form tambah / edit produk
│   ├── categories.php      # Manajemen kategori
│   ├── orders.php          # Manajemen riwayat pesanan
│   ├── settings.php        # Pengaturan toko & customizer tema
│   └── includes/           # Layout header, sidebar & footer admin
├── uploads/
│   └── products/           # Direktori penyimpanan file upload gambar
├── scaffolder/             # Engine CLI & GUI App Scaffolder
├── scaffold.php            # Entrypoint shortcut Feature Installer
├── index.php               # Halaman Beranda Utama
├── demo.php                # Halaman Katalog & WhatsApp Checkout
├── product.php             # Halaman Detail Produk
├── cart.php                # Halaman Keranjang Belanja
├── checkout.php            # Halaman Checkout & Formulir Pesanan
├── order-success.php       # Halaman Konfirmasi Pesanan
├── about.php               # Halaman Profil Bisnis
├── contact.php             # Halaman Kontak & Pertanyaan
├── package.json            # Scripts NPM / PNPM (Vite, Dev, Build, Zip)
├── .htaccess               # Apache routing & security headers
└── README.md               # Dokumentasi Utama
```

---

## ⚡ Mode Frontend (Standalone vs Vite HMR)

Framework ini mendukung dua alur kerja pengembangan frontend:

### 1. Mode Standalone / CDN (Default - Tanpa Node.js)
Aplikasi langsung berjalan murni dengan PHP dan CDN Tailwind CSS / Alpine.js tanpa perlu menginstall `node_modules` atau menjalankan bundler.

### 2. Mode Vite 6 & Tailwind CSS v4 (Pengembangan Modern)
Jika ingin menggunakan compiler Tailwind CSS v4 lokal dengan Hot Module Replacement (HMR):
```bash
# Install dependencies
pnpm install # atau npm install

# Jalankan Vite & PHP Server secara bersamaan (Live Reload):
pnpm dev

# Build aset produksi ke folder dist/
pnpm build
```

---

## 🚀 Panduan Deployment (cPanel / Apache / Nginx)

### A. Shared Hosting / cPanel (Apache)
1. Unggah seluruh isi file ke direktori `public_html` (atau subfolder).
2. Buat database MySQL baru melalui cPanel MySQL Database Wizard.
3. Sesuaikan `DB_NAME`, `DB_USER`, dan `DB_PASS` pada file `config/database.php`.
4. Buka URL `https://domainanda.com/database/init.php` untuk setup tabel awal secara otomatis.
5. Pastikan folder `uploads/` memiliki izin tulis (*permissions*) `0755` atau `0775`.

### B. VPS (Nginx + PHP-FPM)
Gunakan blok server Nginx berikut:
```nginx
server {
    listen 80;
    server_name domainanda.com;
    root /var/www/native-php;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 📄 Lisensi & Kontribusi

Framework ini bersifat *open-source*, fleksibel, dan bebas dimodifikasi untuk proyek website profil perusahaan, aplikasi internal kantor, portal berita, landing page portofolio, maupun toko online katalog.
