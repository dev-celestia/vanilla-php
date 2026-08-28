# ⚡ Lightweight Native PHP Micro-Framework & Web Starter

Sebuah **micro-framework & starter kit web modular** berbasis **Native PHP 8.x**, **Tailwind CSS**, **Alpine.js**, dan **MySQL/MariaDB**. Dirancang khusus untuk menghasilkan website dan aplikasi web yang sangat cepat, hemat memori RAM, bebas dependensi vendor yang berat (*zero bloat*), dan 100% siap dijalankan di berbagai lingkungan (Shared Hosting / cPanel, VPS, Docker, maupun Localhost).

---

## 🌟 Arsitektur & Keunggulan Framework

### 1. 🚀 Inti Framework Ringan & Eksekusi Cepat (Zero Bloat)
- **Pure PHP 8.x**: Eksekusi instan tanpa overhead runtime framework yang rumit. Respon server (TTFB) sangat rendah (< 50ms).
- **Core Abstraction Layer (`helpers/framework.php`)**: Menyediakan helper request input (`request()`), response JSON (`json_response()`), redirect (`redirect()`), abort status (`abort()`), serta query database (`db()`, `db_fetch()`, `db_fetch_all()`, `db_query()`).
- **Database PDO Terisolasi**: Koneksi singleton PDO dengan *Prepared Statements* untuk proteksi maksimal terhadap SQL Injection.
- **Keamanan Berlapis**: Proteksi token CSRF terintegrasi, hashing password Bcrypt, validasi upload file, sanitasi XSS (`sanitize()`), dan guard otentikasi sesi admin.

### 2. 🧩 Sistem Komponen UI Modular (ala shadcn/ui)
Framework ini dilengkapi koleksi fungsi primitif UI modular berbasis PHP murni di folder `components/ui/`:
- **Tombol & Tautan**: `ui_button()`
- **Input & Form**: `ui_input()`, `ui_textarea()`, `ui_select()`, `ui_toggle()`
- **Permukaan & Tampilan Data**: `ui_card()`, `ui_badge()`, `ui_alert()`, `ui_stat_card()`, `ui_avatar()`, `ui_empty_state()`
- **Navigasi & Visual**: `ui_breadcrumb()`, `ui_icon()`, `ui_icon_box()`, `ui_product_card()`
- **Living Style Guide**: Akses showcase dan dokumentasi interaktif seluruh komponen di `/admin/design-system.php`.

### 3. 🎨 Dynamic Theme Engine & Apple Design System
- **8 Palet Warna Primer**: Emerald, Classic Blue, Indigo, Violet, Rose, Amber, Teal, Slate.
- **6 Preset Corner Radius**: Sharp (0px), Subtle (6px), Standard Apple (12px), Soft (16px), Round (24px), Pill (9999px).
- **Estetika Modern**: Material *crisp hairline borders*, *translucent backdrop blur*, *zero shadows*, dan interaksi fisik *apple-tap*.
- **Ikon Font Phosphor**: Integrasi **[Phosphor Icons](https://phosphoricons.com/)** berbasis CSS font tanpa resiko Cumulative Layout Shift (CLS).

### 4. 📦 Modul Bawaan Siap Pakai
- **Landing Page & Website Portofolio (`index.php`)**: Halaman beranda responsif, modern, dan informatif.
- **Demo Modul E-Commerce & WhatsApp Checkout (`demo.php`)**: Etalase produk interaktif, live search, filter kategori, drawer keranjang belanja reaktif (Alpine.js LocalStorage), dan checkout WhatsApp otomatis.
- **Profil Perusahaan & Kontak (`about.php`, `contact.php`)**: Halaman profil bisnis, visi misi, dan formulir pesan.
- **Panel Admin CMS Lengkap (`admin/`)**: Dashboard metrik, CRUD produk & kategori, riwayat pesanan, pengaturan website, dan live customizer tema.

---

## 🛠️ Panduan Instalasi & Menjalankan

### 1. Kebutuhan Sistem
- **PHP**: Versi 8.0 ke atas (dengan ekstensi `pdo_mysql` dan `fileinfo`).
- **Database**: MySQL 5.7+ atau MariaDB 10.3+.
- **Web Server**: Apache / Nginx / Caddy / Built-in PHP CLI Server.

### 2. Konfigurasi Database
Salin atau sesuaikan kredensial database pada file `config/database.php`:
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'native_shop');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Inisialisasi Database (Setup Otomatis)
Jalankan salah satu metode berikut untuk membuat tabel dan data awal:
- **Via Terminal (CLI):**
  ```bash
  php database/init.php
  ```
- **Via Browser:**
  Buka `http://localhost/path-proyek/database/init.php` di peramban Anda.
- **Via phpMyAdmin / MySQL:**
  Import file `database/schema.sql`.

### 4. Menjalankan Server Lokal
Gunakan built-in PHP server untuk pengembangan cepat:
```bash
php -S localhost:8000
```
Buka browser pada: `http://localhost:8000`

---

## 🔑 Kredensial Default Dashboard Admin

- **URL Login Admin:** `http://localhost:8000/admin/login.php`
- **Username:** `admin`
- **Password:** `password123`

*(Kata sandi dan data toko/website dapat diperbarui kapan saja melalui menu Pengaturan di panel admin).*

---

## 💻 Panduan Pengembangan (Developer Guide)

### 1. Membuat Halaman Web Baru
Cukup buat file PHP baru (misal `layanan.php`), muat konfigurasi dan header/footer:
```php
<?php
$active_nav = 'layanan';
$page_title = 'Layanan Kami - Nama Website';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 py-12">
    <?= ui_card([
        'title' => 'Layanan Profesional',
        'subtitle' => 'Solusi tepat untuk kebutuhan digital Anda.'
    ], '
        <p class="text-sm text-slate-600 mb-4">Konten halaman Anda di sini...</p>
        ' . ui_button('Hubungi Kami', ['variant' => 'primary', 'href' => base_url('contact.php')])
    ) ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
```

### 2. Mengambil Data dari Database
Gunakan helper query yang praktis dan aman:
```php
// Ambil semua data
$items = db_fetch_all("SELECT * FROM products WHERE is_active = :status ORDER BY id DESC", [':status' => 1]);

// Ambil satu baris data
$item = db_fetch("SELECT * FROM products WHERE id = :id", [':id' => $id]);

// Eksekusi insert/update
db_query("UPDATE products SET stock = stock - 1 WHERE id = :id", [':id' => $id]);
```

### 3. Membuat REST API / JSON Endpoint
Gunakan `request()` dan `json_response()`:
```php
<?php
require_once __DIR__ . '/config/app.php';

$categoryId = (int)request('category_id', 0);
$items = db_fetch_all("SELECT id, name, price FROM products WHERE category_id = :cat_id", [':cat_id' => $categoryId]);

json_response([
    'success' => true,
    'total'   => count($items),
    'data'    => $items
]);
```

### 4. Proteksi Form dengan CSRF Token
```php
<form method="POST" action="process.php">
    <?= csrf_field() ?>
    <?= ui_input('Nama Lengkap', 'name', ['required' => true]) ?>
    <?= ui_button('Kirim', ['type' => 'submit']) ?>
</form>

<?php
// Pada process.php:
if (is_post()) {
    if (!verify_csrf_token(request('csrf_token'))) {
        abort(403, 'Sesi CSRF tidak valid.');
    }
    // Proses form...
}
?>
```

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
│       ├── card.php        # Komponen Card & Container Surface
│       ├── input.php       # Komponen Input Form & Floating Labels
│       ├── textarea.php    # Komponen Textarea Form
│       ├── select.php      # Komponen Select & Dropdown
│       ├── toggle.php      # Komponen Toggle Switch
│       ├── badge.php       # Komponen Badge & Status Chip
│       ├── alert.php       # Komponen Banner Alert / Notifikasi
│       ├── avatar.php      # Komponen Avatar & Icon Box
│       ├── stat-card.php   # Komponen Metric Stat Card
│       ├── empty-state.php # Komponen Empty State Data
│       ├── breadcrumb.php  # Komponen Breadcrumb Navigation
│       ├── product-card.php# Komponen Kartu Produk / Item
│       ├── icon.php        # Komponen Phosphor Icon
│       └── index.php       # Master component loader
├── helpers/
│   ├── framework.php       # Core micro-framework utilities (request, response, db, routing)
│   ├── components.php      # Component bridge & loader global
│   ├── auth.php            # Guard otentikasi & session security
│   ├── csrf.php            # Generator & validator token CSRF
│   ├── format.php          # Formatter mata uang, tanggal, string & slug
│   ├── upload.php          # Helper validasi & upload file gambar
│   └── vite.php            # Helper integrasi asset bundling Vite (opsional)
├── includes/
│   ├── header.php          # Header website, meta tags, navigasi & store Alpine.js
│   ├── footer.php          # Footer website, info kontak & floating action button
│   └── cart_drawer.php     # Slide-over keranjang belanja reaktif
├── admin/
│   ├── index.php           # Dashboard statistik & ringkasan
│   ├── login.php           # Halaman autentikasi login
│   ├── logout.php          # Handler logout sesi
│   ├── products.php        # Manajemen daftar item & produk
│   ├── product-form.php    # Form tambah & edit data produk + file upload
│   ├── categories.php      # Manajemen kategori konten
│   ├── orders.php          # Manajemen riwayat transaksi / data pesanan
│   ├── settings.php        # Pengaturan website, branding, & tema visual
│   ├── design-system.php   # Showcase & dokumentasi hidup Design System
│   └── includes/           # Layout header, sidebar, & footer panel admin
├── uploads/
│   └── products/           # Direktori penyimpanan file upload gambar
├── index.php               # Halaman Beranda Utama (Landing Page & Framework Showcase)
├── demo.php                # Halaman Demo Interaktif (Katalog & WhatsApp Checkout)
├── product.php             # Halaman Detail Item / Produk
├── about.php               # Halaman Profil Bisnis / Tentang Kami
├── contact.php             # Halaman Kontak & Pertanyaan
├── cart.php                # Halaman Keranjang Belanja
├── checkout.php            # Halaman Checkout & Formulir Pemesanan
├── order-success.php       # Halaman Konfirmasi Pesanan Berhasil
├── THEME_GUIDE.md          # Dokumentasi Kustomisasi Tema & Design Tokens
└── README.md               # Dokumentasi Utama Framework
```

---

## ⚡ Dukungan Asset Bundling Vite (Opsional)

Aplikasi ini mendukung dua mode frontend:
1. **Mode Standalone / CDN (Default)**: Langsung berjalan di hosting apapun tanpa perlu instalasi Node.js/npm.
2. **Mode Vite 6 & Tailwind CSS v4 (Opsional)**:
   ```bash
   npm run dev    # Server Vite HMR & live reload saat file .php diedit
   npm run build  # Kompilasi & minifikasi aset ke folder dist/
   ```

---

## 📄 Lisensi & Kontribusi

Framework ini bersifat *open-source*, fleksibel, dan bebas dimodifikasi untuk proyek website profil perusahaan, aplikasi internal kantor, portal berita, landing page portofolio, maupun toko online katalog.
