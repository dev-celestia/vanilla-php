# 🚀 Lightweight Native PHP Web App (Katalog & WhatsApp Checkout)

Aplikasi web toko online & katalog produk modern berbasis **Native PHP 8.x**, **Tailwind CSS**, **Alpine.js**, dan **MySQL/MariaDB**. Didesain super ringan, hemat memori RAM, dan tidak membebani limit proses server hosting (100% kompatibel dengan shared hosting paket Single / cPanel).

---

## 🌟 Fitur Utama

1. **Website Basic & Profil Bisnis Responsif (HP & Laptop)**
   - Hero banner dengan CTA pemesanan.
   - Filter Kategori & Pencarian Produk Real-time.
   - Halaman **Tentang Kami (About Us)** & Nilai Keunggulan Toko.
   - Halaman **Kontak & Lokasi** dengan interaksi WhatsApp langsung.

2. **Interactive Shopping Cart (Keranjang Belanja)**
   - Slide-over Cart Drawer reaktif (Alpine.js) tanpa reload halaman.
   - Tambah/kurang jumlah barang, hapus item, dan perhitungan subtotal otomatis (Format Rupiah).
   - Sinkronisasi penyimpanan LocalStorage agar keranjang tidak hilang saat halaman di-refresh.

3. **Integrasi Checkout / Order via WhatsApp**
   - Formulir checkout: Nama Pembeli, Nomor WhatsApp, Alamat Lengkap, dan Catatan Pesanan.
   - Otomatis mencatat order ke database (`orders` & `order_items`) dengan kode unik transaksi.
   - Otomatis membuat template pesan WhatsApp yang rapi dan terstruktur untuk dikirimkan ke Admin.

4. **Dashboard Admin (Katalog & Pengaturan)**
   - Login & autentikasi aman dengan Bcrypt hash & proteksi CSRF.
   - **Manajemen Produk (CRUD):** Tambah, Ubah, Hapus produk, upload foto dengan preview, atur stok, harga normal, harga promo/diskon, dan badge unggulan.
   - **Manajemen Kategori (CRUD):** Tambah, ubah, dan hapus kategori produk.
   - **Riwayat Pesanan WhatsApp:** Memantau semua transaksi masuk, melihat rincian item, dan mengubah status pesanan (Pending, Processing, Completed, Cancelled).
   - **Pengaturan Toko:** Ubah Nama Toko, Nomor WhatsApp CS/Admin, Slogan, Alamat, Sosial Media, dan Banner langsung dari panel admin.

---

## 🛠️ Panduan Instalasi & Konfigurasi

### 1. Konfigurasi Database
Buka file `config/database.php` dan sesuaikan kredensial database Anda:
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'native_shop');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 2. Inisialisasi Database (Auto-Setup)
Anda dapat menginisialisasi tabel database dan data awal melalui salah satu cara berikut:
- **Cara 1 (Via Browser):** Buka `http://domain-anda/database/init.php`
- **Cara 2 (Via phpMyAdmin):** Import file `database/schema.sql` ke database MySQL Anda.
- **Cara 3 (Via Terminal):**
  ```bash
  php database/init.php
  ```

---

## 🔑 Kredensial Default Admin

- **URL Login Admin:** `http://domain-anda/admin/login.php`
- **Username:** `admin`
- **Password:** `password123`

*(Kata sandi dapat diganti kapan saja melalui menu **Pengaturan Toko & WA** di dalam dashboard admin).*

---

## 📁 Struktur Direktori

```text
Native-PHP/
├── config/
│   ├── database.php        # Konfigurasi koneksi database PDO
│   └── app.php             # Base URL & helper settings
├── database/
│   ├── schema.sql          # Skema database MySQL & Data Seed
│   └── init.php            # Script auto-setup database
├── helpers/
│   ├── auth.php            # Helper session login & guard
│   ├── csrf.php            # Helper token CSRF keamanan
│   ├── format.php          # Helper rupiah, tanggal, slug
│   └── upload.php          # Helper upload & validasi foto
├── includes/
│   ├── header.php          # Header website & Alpine store cart
│   ├── footer.php          # Footer & floating WA button
│   └── cart_drawer.php     # Slide-over keranjang belanja
├── admin/
│   ├── index.php           # Dashboard ringkasan & statistik
│   ├── login.php           # Login admin
│   ├── logout.php          # Logout admin
│   ├── products.php        # Daftar & kelola katalog produk
│   ├── product-form.php    # Form tambah & edit produk + upload
│   ├── categories.php      # Manajemen kategori
│   ├── orders.php          # Riwayat pesanan WhatsApp
│   ├── settings.php        # Pengaturan toko & nomor WhatsApp
│   └── includes/           # Layout header/footer admin
├── uploads/
│   └── products/           # Folder foto produk upload
├── index.php               # Halaman Beranda & Katalog
├── product.php             # Halaman Detail Produk
├── about.php               # Halaman Profil Bisnis & Toko
├── contact.php             # Halaman Kontak & Pertanyaan
├── cart.php                # Halaman Keranjang Belanja
├── checkout.php            # Halaman Checkout WhatsApp
├── order-success.php       # Halaman Konfirmasi Pesanan
└── .htaccess               # Konfigurasi keamanan Apache / LiteSpeed
```
