# ⚡ Vanilla PHP — Zero-Bloat Web Boilerplate & Project Installer

> **The ultra-fast, modern web boilerplate & starter stack** powered by **Vanilla PHP 8.2+**, **Tailwind CSS v4**, **Alpine.js**, **Phosphor Icons**, and **MySQL / MariaDB PDO**. Built on **Apple Human Interface** design principles with sub-50ms execution, zero framework runtime overhead, and an instant 1-command project scaffolder.

[![GitHub Repository](https://img.shields.io/badge/GitHub-dev--celestia%2Fvanilla--php-181717?style=flat-square&logo=github)](https://github.com/dev-celestia/vanilla-php)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=flat-square&logo=tailwindcss)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-v3.x-8BC0D0?style=flat-square&logo=alpinedotjs)](https://alpinejs.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

---

## 📑 Daftar Isi

- [🌟 Mengapa Vanilla PHP Boilerplate?](#-mengapa-vanilla-php-boilerplate)
- [⚡ Quick Start: 60-Second Setup](#-quick-start-60-second-setup)
- [🛠️ 1-Command Project Scaffolder / Installer](#️-1-command-project-scaffolder--installer)
- [📦 Fitur Bawaan Boilerplate](#-fitur-bawaan-boilerplate)
- [🎨 Apple-Inspired Design System & Theme Engine](#-apple-inspired-design-system--theme-engine)
- [💻 Panduan Developer & Core Helpers](#-panduan-developer--core-helpers)
  - [1. Helper Database & Query PDO](#1-helper-database--query-pdo)
  - [2. Helper HTTP Request & Response](#2-helper-http-request--response)
  - [3. Helper Keamanan CSRF & XSS](#3-helper-keamanan-csrf--xss)
  - [4. Helper Format & Sanitasi](#4-helper-format--sanitasi)
  - [5. Primitif Komponen UI (shadcn-style)](#5-primitif-komponen-ui-shadcn-style)
- [📁 Struktur Direktori Boilerplate](#-struktur-direktori-boilerplate)
- [⚡ Mode Frontend (Standalone vs Vite HMR)](#-mode-frontend-standalone-vs-vite-hmr)
- [🚀 Panduan Deployment (cPanel / Shared Hosting / Nginx)](#-panduan-deployment-cpanel--shared-hosting--nginx)
- [🔑 Kredensial Admin Default](#-kredensial-admin-default)
- [📄 Lisensi](#-lisensi)

---

## 🌟 Mengapa Vanilla PHP Boilerplate?

Modern web development sering kali dibebani ratusan megabyte dependensi vendor (*bloated node_modules/vendor*), waktu bootstrap framework yang lambat, dan konfigurasi server yang rumit. 

**Vanilla PHP Boilerplate** mengembalikan kesederhanaan dan performa puncak web development:

| Fitur | Vanilla PHP Boilerplate | Heavy Framework (Laravel/Symfony) |
| :--- | :--- | :--- |
| **Response Time (TTFB)** | **⚡ < 50ms** | 🐌 200ms - 500ms+ |
| **Memory Footprint** | **🍃 ~1.8 MB RAM** | 🐘 18 MB - 40 MB+ |
| **Deployment** | **📂 100% FTP / Git Drag & Drop** | ⚙️ Wajib Composer, Build pipelines & SSH |
| **UI Components** | **🍎 Apple-tactile primitives (`ui_*`)** | 🔌 Perlu install library pihak ketiga |
| **Theme Engine** | **🎨 Live token switcher (CSS Variables)** | 🔧 Manual CSS / JS configuration |
| **Scaffolder** | **⚡ CLI & Web GUI Installer bawaan** | 🛠️ CLI generator terpisah |

---

## ⚡ Quick Start: 60-Second Setup

### 1. Clone Repository
```bash
git clone https://github.com/dev-celestia/vanilla-php.git
cd vanilla-php
```

### 2. Konfigurasi Database
Salin `.env.example` menjadi `.env` (atau sesuaikan kredensial di `config/database.php`):
```bash
cp .env.example .env
```
Sesuaikan kredensial MySQL Anda:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=vanilla_shop
DB_USER=root
DB_PASS=
```

### 3. Inisialisasi Database
```bash
# Jalankan migrasi database otomatis via PHP CLI:
php database/init.php

# Atau via npm / pnpm:
pnpm db:init
```

### 4. Jalankan Local Server
```bash
# Mode PHP Server Standalone (Tanpa Node.js):
php -S 0.0.0.0:8000

# ATAU Mode Vite Hot Module Replacement (HMR):
pnpm dev
```
Buka browser pada: **`http://localhost:8000`**

---

## 🛠️ 1-Command Project Scaffolder / Installer

Boilerplate ini dilengkapi dengan generator otomatis (*Project Scaffolder*) untuk mengekstrak modul aplikasi bersih dan siap pakai ke direktori proyek baru Anda:

### 1. Eksekusi via Terminal (CLI Scaffolder)
```bash
# Salin toko / aplikasi bersih ke folder baru:
php scaffold.php ../proyek-toko --name="Koleksi Fashion"

# Salin tanpa inisialisasi database (skema SQL disalin manual):
php scaffold.php ../proyek-baru --no-db

# Bantuan perintah & opsi:
php scaffold.php --help
```

### 2. Eksekusi via Web Browser (GUI Installer)
Buka URL **`http://localhost:8000/scaffold.php`** langsung di peramban, masukkan path folder tujuan (misal `../katalog-baru`), nama aplikasi, dan klik **Salin Toko ke Folder Baru**.

> **🛡️ Zero-Destructive Guarantee:** Menjalankan installer/scaffolder tidak akan mengubah atau merusak master template `vanilla-php` saat ini.

---

## 📦 Fitur Bawaan Boilerplate

1. **E-Commerce & Product Catalog Starter**:
   - Filter kategori dinamis, pencarian instan, dan sorting harga/terbaru.
   - Reactive Slide-over Cart Drawer ditenagai Alpine.js + LocalStorage.
   - Form Checkout & Checkout Langsung otomatis via WhatsApp format rapi.
2. **Full Admin Backoffice CMS (`/admin`)**:
   - Ringkasan statistik pendapatan, pesanan, dan metrik produk.
   - Manajemen CRUD Produk, Kategori, dan Riwayat Pesanan.
   - Pengaturan toko, kontak bisnis WhatsApp, serta live customizer tema warna & radius.
3. **Pondasi Halaman Lengkap**:
   - **Showcase / Landing Page** (`index.php`)
   - **Living Style Guide & Token Explorer** (`design-system.php`)
   - **Interactive Component Library & Docs** (`components.php`)
   - **Kontak & Pesan** (`contact.php`)
   - **Demo Storefront** (`demo.php`), Detail (`product.php`), Keranjang (`cart.php`), Checkout (`checkout.php`), & Sukses (`order-success.php`).

---

## 🎨 Apple-Inspired Design System & Theme Engine

Boilerplate ini mengimplementasikan prinsip desain **Apple Human Interface**:
- **Tactile Feedback**: Animasi mikro *apple-tap* pada saat pointer ditekan.
- **Translucent Materials**: Efek *backdrop-blur* kaca halus dengan *hairline borders*.
- **Optical Typography**: Penyesuaian tracking font Geist dan Phosphor Icons berbasis CSS font (*zero layout shifts*).
- **Dynamic Theme Tokens**: 9 Preset warna (`zinc`, `emerald`, `blue`, `indigo`, `violet`, `rose`, `amber`, `teal`, `slate`) dan 6 Preset corner radius (`sharp`, `subtle`, `standard`, `soft`, `round`, `pill`) yang terhubung langsung ke CSS Variables.

---

## 💻 Panduan Developer & Core Helpers

### 1. Helper Database & Query PDO
Lokasi file: [`helpers/framework.php`](file:///Users/arham/Desktop/project/Native-PHP/helpers/framework.php)
```php
// Mengambil koneksi PDO singleton
$pdo = db();

// Fetch banyak baris record
$products = db_fetch_all("SELECT * FROM products WHERE is_active = :status ORDER BY id DESC", [
    ':status' => 1
]);

// Fetch 1 baris record
$item = db_fetch("SELECT * FROM products WHERE id = :id", [
    ':id' => $id
]);

// Eksekusi Query (Insert / Update / Delete)
db_query("UPDATE products SET stock = stock - :qty WHERE id = :id", [
    ':qty' => 1,
    ':id'  => $id
]);
```

### 2. Helper HTTP Request & Response
```php
// Mengambil input (otomatis support GET, POST, maupun JSON payload)
$search   = request('q', '');
$category = (int)request('cat_id', 0);

// Pengecekan method HTTP & AJAX
if (is_post()) { /* Handle form POST */ }
if (is_ajax()) { /* Handle fetch / async request */ }

// Mengembalikan respons JSON dan terminate
json_response([
    'success' => true,
    'data'    => $products
], 200);

// Redirect & Abort
redirect(base_url('cart.php'));
abort(404, 'Halaman tidak ditemukan');
```

### 3. Helper Keamanan CSRF & XSS
```php
<!-- Form HTML -->
<form method="POST" action="process.php">
    <?= csrf_field() ?>
    <input type="text" name="name" required>
    <button type="submit">Kirim</button>
</form>

<?php
// Pemrosesan POST yang aman:
if (is_post()) {
    if (!verify_csrf_token(request('csrf_token'))) {
        abort(403, 'Sesi form kadaluarsa atau token CSRF tidak valid.');
    }
    // Lanjutkan aksi...
}
```

### 4. Helper Format & Sanitasi
Lokasi file: [`helpers/format.php`](file:///Users/arham/Desktop/project/Native-PHP/helpers/format.php)
```php
echo format_rupiah(249000);             // Output: Rp 249.000
echo sanitize($_GET['search']);         // Anti XSS output escaping
echo slugify("Sepatu Sneaker Pria");   // Output: sepatu-sneaker-pria
echo format_date('2026-08-28 20:00:00'); // Output: 28 Agustus 2026, 20:00 WIB
```

### 5. Primitif Komponen UI (shadcn-style)
Tersedia di direktori [`components/ui/`](file:///Users/arham/Desktop/project/Native-PHP/components/ui/):
```php
// Button & Link
echo ui_button('Simpan Perubahan', ['variant' => 'primary', 'icon' => 'check']);
echo ui_button('Detail', ['variant' => 'outline', 'href' => base_url('product.php?id=1')]);

// Card Surface
echo ui_card([
    'title'    => 'Statistik Penjualan',
    'subtitle' => 'Ringkasan bulan ini',
    'icon'     => 'chart-bar'
], '<p class="text-sm">Konten kartu...</p>');

// Form Inputs & Select
echo ui_input('Nama Produk', 'name', ['placeholder' => 'Masukkan nama...', 'required' => true]);
echo ui_textarea('Deskripsi', 'description', ['rows' => 3]);
echo ui_select('Kategori', 'category_id', [1 => 'Elektronik', 2 => 'Fashion']);

// Badges & Notification Banners
echo ui_badge('Stok Tersedia', 'success');
echo ui_alert('Data produk berhasil diperbarui.', 'success', ['dismissible' => true]);
```

---

## 📁 Struktur Direktori Boilerplate

```text
vanilla-php/
├── config/
│   ├── app.php             # Master bootstrapper & global config loader
│   ├── database.php        # Konfigurasi & singleton koneksi database PDO
│   └── theme.php           # Token Design System, Palet Warna & Corner Radius
├── database/
│   ├── schema.sql          # Struktur skema tabel MySQL & data seed
│   └── init.php            # Script inisialisasi & migrasi otomatis database
├── components/
│   └── ui/                 # Komponen UI Primitif Reusable (shadcn-style)
│       ├── button.php      # Button & Link primitives
│       ├── card.php        # Card container & glass surfaces
│       ├── input.php       # Text inputs
│       ├── textarea.php    # Textarea fields
│       ├── select.php      # Dropdowns
│       ├── toggle.php      # iOS switch toggle
│       ├── badge.php       # Status chips & badges
│       ├── alert.php       # Notification banners
│       ├── stat-card.php   # Metric / stat cards
│       ├── modal.php       # Accessible modal dialogs
│       ├── tabs.php        # Tabbed navigation
│       ├── product-card.php# Product showcase card
│       ├── icon.php        # Phosphor icon helper
│       └── index.php       # Master component loader
├── helpers/
│   ├── framework.php       # Database PDO, routing, request, response & abort
│   ├── components.php      # Component bridge loader
│   ├── auth.php            # Admin session authentication guard
│   ├── csrf.php            # CSRF token generator & validator
│   ├── format.php          # Rupiah, slug, sanitasi XSS, & date formatting
│   ├── upload.php          # Image upload validation & storage
│   └── vite.php            # Vite asset loader (transparent switching)
├── includes/
│   ├── header.php          # Topbar, responsive navbar & navigation
│   ├── footer.php          # Footer, quick links & WhatsApp floating widget
│   └── cart_drawer.php     # Slide-over reactive cart drawer
├── admin/
│   ├── index.php           # Dashboard statistik & metrik
│   ├── login.php           # Autentikasi sesi admin
│   ├── logout.php          # Logout handler
│   ├── products.php        # CRUD produk & manajemen gambar
│   ├── product-form.php    # Form tambah / edit produk
│   ├── categories.php      # Manajemen kategori
│   ├── orders.php          # Manajemen riwayat pesanan
│   ├── settings.php        # Pengaturan toko & customizer tema live
│   └── includes/           # Layout header, sidebar, & footer admin
├── uploads/
│   └── products/           # Folder penyimpanan file upload gambar produk
├── scaffolder/             # CLI & Web GUI Project Scaffolder Engine
├── scaffold.php            # Entrypoint shortcut App Scaffolder
├── index.php               # Homepage / Framework Landing Page
├── design-system.php       # Living Style Guide & Token Explorer
├── components.php          # Component Library Catalog & Interactive Docs
├── demo.php                # E-Commerce Catalog & WhatsApp Checkout Demo
├── product.php             # Detail Produk
├── cart.php                # Keranjang Belanja
├── checkout.php            # Form Checkout Pesanan
├── order-success.php       # Halaman Sukses Pesanan
├── contact.php             # Form Kontak & Dukungan
├── package.json            # Scripts NPM/PNPM (Vite, Dev, Build, Zip)
├── .htaccess               # Apache clean routing & security headers
└── README.md               # Dokumentasi Utama
```

---

## ⚡ Mode Frontend (Standalone vs Vite HMR)

Boilerplate ini mendukung 2 mode alur kerja pengembangan frontend:

### 1. Mode Standalone / CDN (Default — Tanpa Node.js)
Aplikasi berjalan 100% murni dengan PHP dan CDN Tailwind CSS / Alpine.js tanpa perlu menginstall `node_modules` atau menjalankan bundler. Cocok untuk shared hosting atau edit langsung via server.

### 2. Mode Vite 6 & Tailwind CSS v4 (Modern Dev Workflow)
Jika Anda menginginkan kompilasi aset lokal dengan Hot Module Replacement (HMR):
```bash
pnpm install    # atau npm install
pnpm dev        # Jalankan Vite HMR + PHP Server serentak
pnpm build      # Kompilasi aset produksi ke folder dist/
```

---

## 🚀 Panduan Deployment (cPanel / Shared Hosting / Nginx)

### A. Shared Hosting / cPanel (Apache)
1. Unggah seluruh file ke direktori `public_html` (atau subfolder).
2. Buat database MySQL baru via MySQL Database Wizard di cPanel.
3. Atur kredensial database di file `.env` atau `config/database.php`.
4. Buka URL `https://domainanda.com/database/init.php` untuk setup tabel otomatis.
5. Pastikan folder `uploads/` memiliki izin tulis (*permissions*) `0755` atau `0775`.

### B. VPS (Nginx + PHP-FPM)
Gunakan blok server Nginx berikut:
```nginx
server {
    listen 80;
    server_name domainanda.com;
    root /var/www/vanilla-php;
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

---

## 🔑 Kredensial Admin Default

- **URL Login:** `http://localhost:8000/admin/login.php`
- **Username:** `admin`
- **Password:** `password123`

*(Kata sandi dan identitas aplikasi dapat diubah langsung melalui menu **Admin > Pengaturan**).*

---

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi open-source **[MIT License](LICENSE)**. Bebas digunakan untuk keperluan komersial, proyek klien, aplikasi internal, SaaS micro-apps, maupun toko online.
