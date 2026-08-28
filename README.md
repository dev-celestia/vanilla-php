# 🚀 Lightweight Native PHP Web App (Katalog & WhatsApp Checkout)

Aplikasi web toko online & katalog produk modern berbasis **Native PHP 8.x**, **Tailwind CSS**, **Alpine.js**, dan **MySQL/MariaDB**. Didesain super ringan, hemat memori RAM, dan tidak membebani limit proses server hosting (100% kompatibel dengan shared hosting paket Single / cPanel).

---

## 🌟 Fitur Utama

1. **Website Basic & Profil Bisnis Responsif (HP & Laptop)**
   - Hero banner dengan CTA pemesanan.
   - Filter Kategori & Pencarian Produk Real-time.
   - Halaman **Tentang Kami (About Us)** & Nilai Keunggulan Toko.
   - Halaman **Kontak & Lokasi** dengan interaksi WhatsApp langsung.

2. **Apple Design System, Phosphor Icons & 100% Zero Shadow**
   - Menggunakan **[Phosphor Icons](https://phosphoricons.com/)** (`@phosphor-icons/web@2.1.2`) berbasis font & CSS (zero Cumulative Layout Shift, zero JS lag).
   - Flat crisp hairline borders (`border-slate-200/80`), translucent glass materials (`backdrop-blur-xl bg-white/90`), dan zero drop shadows.
   - Respon fisik tekan instan Apple (`apple-tap` dengan `scale 0.975`).
   - Komponen UI primitif PHP reusable: `ui_button()`, `ui_input()`, `ui_select()`, `ui_toggle()`, `ui_card()`, `ui_badge()`, `ui_alert()`, `ui_stat_card()`, `ui_avatar()`, `ui_icon()`.

3. **Global Theme & Palette Customizer**
   - **8 Pilihan Palet Warna Primer**: Emerald, Classic Blue, Indigo, Violet, Rose, Amber, Teal, Slate.
   - **6 Preset Corner Radius**: Sharp (0px), Subtle (6px), Standard Apple (12px), Soft (16px), Round (24px), Pill (9999px).
   - Pengaturan instan langsung via Panel Admin (`admin/settings.php`) atau kode (`config/theme.php`).
   - Panduan lengkap kustomisasi di [`THEME_GUIDE.md`](./THEME_GUIDE.md).

4. **Interactive Shopping Cart (Keranjang Belanja)**
   - Slide-over Cart Drawer reaktif (Alpine.js) tanpa reload halaman.
   - Tambah/kurang jumlah barang, hapus item, dan perhitungan subtotal otomatis (Format Rupiah).
   - Sinkronisasi penyimpanan LocalStorage agar keranjang tidak hilang saat halaman di-refresh.

5. **Integrasi Checkout / Order via WhatsApp**
   - Formulir checkout: Nama Pembeli, Nomor WhatsApp, Alamat Lengkap, dan Catatan Pesanan.
   - Otomatis mencatat order ke database (`orders` & `order_items`) dengan kode unik transaksi.
   - Otomatis membuat template pesan WhatsApp yang rapi dan terstruktur untuk dikirimkan ke Admin.

6. **Dashboard Admin (Katalog & Pengaturan)**
   - Login & autentikasi aman dengan Bcrypt hash & proteksi CSRF.
   - **Manajemen Produk (CRUD):** Tambah, Ubah, Hapus produk, upload foto dengan preview, atur stok, harga normal, harga promo/diskon, dan badge unggulan.
   - **Manajemen Kategori (CRUD):** Tambah, ubah, dan hapus kategori produk.
   - **Riwayat Pesanan WhatsApp:** Memantau semua transaksi masuk, melihat rincian item, dan mengubah status pesanan.
   - **Showcase & Guide Design System:** Akses living style guide di `/admin/design-system.php`.
   - **Pengaturan Toko:** Ubah Nama Toko, Nomor WhatsApp CS/Admin, Slogan, Alamat, Sosial Media, Warna Tema, dan Radius Corner.

---

## 🎨 Panduan Kustomisasi Tema

Untuk panduan mendalam tentang cara mengubah warna, corner radius, token design system, dan membuat komponen baru, silakan baca dokumentasi khusus:
👉 **[Lihat Panduan Kustomisasi Tema (THEME_GUIDE.md)](./THEME_GUIDE.md)** atau kunjungi halaman **Showcase & Guide** di `/admin/design-system.php`.

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
│   ├── theme.php           # Token Design System, Palet Warna & Radius
│   └── app.php             # Base URL & helper settings
├── database/
│   ├── schema.sql          # Skema database MySQL & Data Seed
│   └── init.php            # Script auto-setup database
├── helpers/
│   ├── components.php      # Library Komponen Primitif PHP (Button, Input, Card, Badge, Alert)
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
│   ├── settings.php        # Pengaturan toko & kustomisasi tema
│   ├── design-system.php   # Showcase & Panduan Design System
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
├── THEME_GUIDE.md          # Panduan Kustomisasi Tema & Design Tokens
└── README.md
```
