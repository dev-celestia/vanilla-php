# 🎨 Panduan Lengkap Kustomisasi Tema & Design System

Panduan ini menjelaskan cara mengonfigurasi, mengubah, dan memperluas tema warna, corner radius, dan komponen primitif pada platform **Native-PHP Storefront & Admin**.

---

## 📑 Daftar Isi
1. [Kustomisasi via Panel Admin (Tanpa Coding)](#1-kustomisasi-via-panel-admin)
2. [Menambah Palet Warna Baru (Custom Color Palette)](#2-menambah-palet-warna-baru)
3. [Menambah / Mengubah Preset Corner Radius](#3-menambah--mengubah-preset-corner-radius)
4. [Menggunakan Token Design System dalam Template HTML/PHP](#4-menggunakan-token-dalam-template)
5. [Daftar Lengkap Komponen Primitif PHP](#5-daftar-lengkap-komponen-primitif-php)
6. [Prinsip Desain (Apple Fluid & Zero Shadow)](#6-prinsip-desain)

---

## 1. Kustomisasi via Panel Admin

Cara termudah untuk mengubah tampilan toko adalah langsung dari Dashboard Admin:

1. Buka browser dan login ke **Admin Panel** di `/admin/login.php`.
2. Masuk ke menu **Pengaturan Toko & WA** (`/admin/settings.php`).
3. Pada bagian teratas **"Design System Tokens & Tampilan"**:
   - **Pilih Warna Utama (Primary Color Token)**: Tersedia pilihan instan seperti *Emerald Green*, *Apple Classic Blue*, *Modern Indigo*, *Electric Violet*, *Vibrant Rose*, *Warm Amber*, *Nordic Teal*, dan *Minimal Slate*.
   - **Pilih Corner Radius (Rounded Token)**: Tersedia pilihan *Sharp (0px)*, *Subtle (6px)*, *Standard Apple (12px)*, *Soft (16px)*, *Extra Round (24px)*, dan *Pill (9999px)*.
4. Klik tombol **"Simpan Semua Pengaturan & Tema"**.
5. Perubahan akan **langsung diterapkan secara global** ke seluruh halaman pengunjung dan panel admin.

---

## 2. Menambah Palet Warna Baru

Jika Anda ingin menambahkan warna khusus (misal warna brand perusahaan Anda), cukup buka file:
📁 `config/theme.php`

Temukan fungsi `get_theme_color_palettes()` dan tambahkan key warna baru dengan skala 50–950:

```php
function get_theme_color_palettes(): array {
    return [
        // Palet default...
        'emerald' => [ ... ],
        
        // ✨ Contoh menambahkan warna custom: 'cyber_orange'
        'cyber_orange' => [
            'name' => 'Cyber Orange',
            '50'  => '#fff7ed',
            '100' => '#ffedd5',
            '200' => '#fed7aa',
            '300' => '#fdba74',
            '400' => '#fb923c',
            '500' => '#f97316', // Warna aksen
            '600' => '#ea580c', // Warna utama tombol
            '700' => '#c2410c', // Hover tombol
            '800' => '#9a3412',
            '900' => '#7c2d12',
            '950' => '#431407',
        ],
    ];
}
```

Setelah disimpan, opsi *Cyber Orange* akan otomatis muncul sebagai opsi pilihan di Admin Settings!

---

## 3. Menambah / Mengubah Preset Corner Radius

Untuk mengatur kelengkungan sudut elemen secara global, buka:
📁 `config/theme.php`

Pada fungsi `get_theme_radius_presets()`, Anda dapat menyesuaikan atau menambah preset baru:

```php
function get_theme_radius_presets(): array {
    return [
        'custom_smooth' => [
            'name'        => 'Custom Super Smooth (18px)',
            'description' => 'Sudut melengkung halus untuk tampilan modern',
            'btn'         => '14px',   // Radius tombol
            'card'        => '20px',   // Radius kartu & wadah
            'input'       => '14px',   // Radius input form
            'badge'       => '8px',    // Radius label status
            'modal'       => '24px',   // Radius dialog popup
            'avatar'      => '14px',   // Radius avatar profil
            'tailwind'    => 'rounded-2xl'
        ],
    ];
}
```

---

## 4. Menggunakan Token dalam Template

Saat Anda membuat halaman PHP atau komponen baru, Anda dapat memanfaatkan token CSS & Tailwind yang otomatis tersedia:

### A. Utility Classes Tailwind
- `bg-brand-600` / `hover:bg-brand-700` : Warna background primer sesuai tema
- `text-brand-600` / `text-brand-700`   : Warna teks primer
- `border-brand-500/20` / `border-brand-200/80` : Border warna brand
- `rounded-btn`   : Mengikuti token radius tombol aktif
- `rounded-card`  : Mengikuti token radius kartu aktif
- `rounded-input` : Mengikuti token radius form input
- `rounded-badge` : Mengikuti token radius badge status
- `rounded-modal` : Mengikuti token radius popup/modal

### B. Utility Feedback Apple Tactile
- `class="apple-tap"` : Menambahkan efek fisika tekan instan (`transform: scale(0.975)` pada saat pointer-down) tanpa jeda.

### C. Contoh Penggunaan HTML:
```html
<div class="rounded-card bg-white border border-slate-200/80 p-6">
    <h3 class="text-base font-bold text-slate-900 tracking-tight">Judul Kartu</h3>
    <p class="text-xs text-slate-500 mt-1">Deskripsi konten kartu...</p>
    
    <button class="mt-4 px-4 py-2 rounded-btn bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs apple-tap">
        Aksi Utama
    </button>
</div>
```

---

## 5. Daftar Lengkap Komponen Primitif PHP

Semua helper komponen didefinisikan dalam [`helpers/components.php`](file:///Users/arham/Desktop/project/Native-PHP/helpers/components.php).

### 1. Button (`ui_button` / `ui_btn`)
```php
// Button Submit
echo ui_button('Simpan Data', [
    'variant' => 'primary', // 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'subtle'
    'size'    => 'md',      // 'xs' | 'sm' | 'md' | 'lg'
    'type'    => 'submit',
    'icon'    => 'check',   // Nama ikon Phosphor (misal: 'check', 'shopping-cart', 'trash')
]);

// Button Link (<a>)
echo ui_button('Buka Katalog', [
    'variant' => 'outline',
    'href'    => base_url('index.php'),
    'icon'    => 'shopping-bag',
]);
```

### Ikon Primitif (`ui_icon`)
```php
// Helper khusus Phosphor Icons (https://phosphoricons.com/)
echo ui_icon('shopping-cart', 'text-brand-600 text-lg');
```

### 2. Form Input (`ui_input`)
```php
echo ui_input('username', [
    'label'       => 'Username Admin',
    'placeholder' => 'Masukkan username...',
    'value'       => 'admin',
    'icon'        => 'user',
    'required'    => true,
    'helper'      => 'Gunakan huruf kecil tanpa spasi.',
]);
```

### 3. Select Dropdown (`ui_select`)
```php
echo ui_select('kategori_id', [
    '1' => 'Elektronik',
    '2' => 'Pakaian & Fashion',
    '3' => 'Peralatan Rumah',
], [
    'label'    => 'Kategori Produk',
    'selected' => '1',
]);
```

### 4. Textarea (`ui_textarea`)
```php
echo ui_textarea('alamat', [
    'label'       => 'Alamat Lengkap',
    'rows'        => 3,
    'placeholder' => 'Jl. Contoh No. 123...',
    'required'    => true,
]);
```

### 5. Toggle Switch (`ui_toggle`)
```php
echo ui_toggle('is_active', 'Aktifkan Produk', true, [
    'helper' => 'Produk akan langsung tampil di etalase toko.',
]);
```

### 6. Card Surface (`ui_card`)
```php
echo ui_card('<p class="text-xs text-slate-600">Isi kartu di sini...</p>', [
    'title'    => 'Ringkasan Informasi',
    'subtitle' => 'Data statistik terbaru',
    'icon'     => 'bar-chart',
    'glass'    => true, // Efek glassmorphism translucent Apple
]);
```

### 7. Badge & Status Tag (`ui_badge`)
```php
echo ui_badge('Pesanan Baru', 'brand', ['dot' => true]);
echo ui_badge('Selesai', 'success', ['dot' => true]);
echo ui_badge('Dibatalkan', 'danger', ['dot' => true]);
echo ui_badge('Menunggu Transfer', 'warning', ['dot' => true]);
```

### 8. Alert & Banner Notifikasi (`ui_alert`)
```php
echo ui_alert('Data berhasil disimpan ke sistem.', 'success', [
    'title'       => 'Berhasil!',
    'dismissible' => true,
]);
```

### 9. Stat Card Metrik Dashboard (`ui_stat_card`)
```php
echo ui_stat_card('Total Penjualan', 'Rp 15.450.000', [
    'icon'      => 'banknote',
    'subtitle'  => 'Bulan berjalan',
    'trend'     => '+14.2%',
    'trendType' => 'up',
]);
```

---

## 6. Prinsip Desain (Apple Fluid & Zero Shadow)

1. **Zero Shadows**: Tidak menggunakan drop-shadow (`box-shadow: none !important`). Kedalaman visual diciptakan melalui:
   - **Translucent materials**: `backdrop-blur-xl bg-white/90` di atas konten yang bergerak.
   - **Hairline borders**: Garis batas presisi tinggi `border border-slate-200/80` pada mode terang dan `border border-slate-800` pada mode gelap.
2. **Instant Tactile Feedback**: Setiap tombol merespon seketika pada saat pointer-down (`active:scale-[0.975]`).
3. **Typography**: Menggunakan sistem optical sizing dengan tracking lebih rapat (`tracking-tight`) untuk heading besar dan leading yang nyaman untuk isi teks.
4. **Reduced Motion Accessibility**: Transisi dan efek scale otomatis disederhanakan jika perangkat pengguna menyalakan preferensi `prefers-reduced-motion`.

---

✨ *Untuk melihat demo visual interaktif seluruh komponen dan token aktif, buka halaman **Showcase Primitif UI** di `/admin/design-system.php`.*
