# 🎨 Panduan Lengkap Kustomisasi Tema & Design System

Panduan ini menjelaskan cara mengonfigurasi, mengubah, dan memperluas tema warna, corner radius, sistem ikon **[Phosphor Icons](https://phosphoricons.com/)**, serta komponen primitif pada platform **Vanilla-PHP Storefront & Admin**.

---

## 📑 Daftar Isi
1. [Kustomisasi via Panel Admin (Tanpa Coding)](#1-kustomisasi-via-panel-admin)
2. [Menambah Palet Warna Baru (Custom Color Palette)](#2-menambah-palet-warna-baru)
3. [Menambah / Mengubah Preset Corner Radius](#3-menambah--mengubah-preset-corner-radius)
4. [Sumber Ikon: Phosphor Icons Engine](#4-sumber-ikon-phosphor-icons-engine)
5. [Menggunakan Token Design System dalam Template HTML/PHP](#5-menggunakan-token-dalam-template)
6. [Daftar Lengkap Komponen Primitif PHP (shadcn-style)](#6-daftar-lengkap-komponen-primitif-php-shadcn-style)
7. [Penanganan Event & Interaktivitas (Event Handlers)](#7-penanganan-event--interaktivitas-event-handlers)
8. [Prinsip Desain (Apple Fluid & Zero Shadow)](#8-prinsip-desain-apple-fluid--zero-shadow)

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
            '600' => '#ea580c', // Warna utama tombol & aksen
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

## 4. Sumber Ikon: Phosphor Icons Engine

Proyek ini menggunakan **[Phosphor Icons](https://phosphoricons.com/)** (`@phosphor-icons/web@2.1.2`) sebagai sumber utama ikon. 

### Keunggulan Phosphor Icons:
- **Font & CSS-based**: Dirender seketika saat HTML dimuat tanpa *Cumulative Layout Shift* (CLS) dan tanpa delay JavaScript `createIcons()`.
- **Fleksibel & Konsisten**: Mendukung bobot (weight) `regular`, `bold`, `fill`, dan lainnya.
- **Koleksi Lengkap**: Lebih dari 1.000+ ikon berkualitas tinggi untuk kebutuhan e-commerce & aplikasi modern.

### Cara Penggunaan Ikon:

#### A. Menggunakan Helper PHP `ui_icon()`
```php
// Regular icon
echo ui_icon('shopping-cart', 'text-brand-600 text-lg');

// Bold weight icon
echo ui_icon('shield-check', 'text-emerald-500 text-xl', 'bold');

// Fill weight icon
echo ui_icon('star', 'text-amber-400 text-sm', 'fill');
```

#### B. Menggunakan Tag HTML Langsung
```html
<!-- Regular -->
<i class="ph ph-shopping-cart text-lg text-brand-600"></i>

<!-- Bold -->
<i class="ph-bold ph-shield-check text-xl text-emerald-500"></i>

<!-- Fill -->
<i class="ph-fill ph-star text-sm text-amber-400"></i>
```

#### C. Smart Aliases Otomatis
Helper `ui_icon()` dan seluruh komponen primitif mendukung pemetaan nama otomatis:
- `search` ➔ `ph-magnifying-glass`
- `trash-2` / `trash` ➔ `ph-trash`
- `message-circle` / `message-square` ➔ `ph-chat-circle-dots` / `ph-whatsapp-logo`
- `chevron-right` ➔ `ph-caret-right`
- `chevron-down` ➔ `ph-caret-down`
- `chevron-left` ➔ `ph-caret-left`
- `arrow-up-down` ➔ `ph-arrows-down-up`
- `rotate-ccw` ➔ `ph-arrows-counter-clockwise`
- `package-search` ➔ `ph-package`
- `layout-dashboard` / `layout-grid` ➔ `ph-squares-four`
- `settings` ➔ `ph-gear`
- `external-link` ➔ `ph-arrow-square-out`
- `log-out` ➔ `ph-sign-out`
- `log-in` ➔ `ph-sign-in`
- `banknote` / `dollar-sign` ➔ `ph-currency-dollar`
- `alert-triangle` ➔ `ph-warning`
- `alert-circle` ➔ `ph-warning-circle`
- `check-circle-2` ➔ `ph-check-circle`

---

## 5. Menggunakan Token dalam Template

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
    
    <button class="mt-4 px-4 py-2 rounded-btn bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs apple-tap flex items-center gap-2">
        <i class="ph ph-shopping-cart text-sm"></i>
        <span>Aksi Utama</span>
    </button>
</div>
```

---

## 6. Daftar Lengkap Komponen Primitif PHP (shadcn-style)

Komponen UI terbagi modular per berkas di folder [`components/ui/`](file:///Users/arham/Desktop/project/Native-PHP/components/ui) (dan di-load otomatis via [`components/ui/index.php`](file:///Users/arham/Desktop/project/Native-PHP/components/ui/index.php) / [`helpers/components.php`](file:///Users/arham/Desktop/project/Native-PHP/helpers/components.php)).

### Struktur Berkas Komponen:
- `components/ui/button.php` (`ui_button`, `ui_btn`)
- `components/ui/card.php` (`ui_card`)
- `components/ui/input.php` (`ui_input`)
- `components/ui/textarea.php` (`ui_textarea`)
- `components/ui/select.php` (`ui_select`)
- `components/ui/toggle.php` (`ui_toggle`)
- `components/ui/badge.php` (`ui_badge`)
- `components/ui/alert.php` (`ui_alert`)
- `components/ui/avatar.php` (`ui_avatar`, `ui_icon_box`)
- `components/ui/stat-card.php` (`ui_stat_card`)
- `components/ui/empty-state.php` (`ui_empty_state`)
- `components/ui/breadcrumb.php` (`ui_breadcrumb`)
- `components/ui/product-card.php` (`ui_product_card`)
- `components/ui/icon.php` (`ui_icon`)

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
    'icon'     => 'chart-bar',
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
    'icon'      => 'currency-dollar',
    'subtitle'  => 'Bulan berjalan',
    'trend'     => '+14.2%',
    'trendType' => 'up',
]);
```

### 10. Avatar & Icon Container (`ui_avatar`, `ui_icon_box`)
```php
echo ui_avatar('Admin Toko', ['size' => 'md']);
echo ui_icon_box('shopping-cart', 'brand', ['size' => 'md']);
```

---

## 7. Penanganan Event & Interaktivitas (Event Handlers)

Seluruh komponen UI mendukung opsi `'attrs'` untuk memasang event handler secara deklaratif (baik menggunakan **Alpine.js**, **Vanilla JavaScript**, maupun **HTMX**).

### A. Alpine.js Click & State Manipulation
```php
// Toggle state boolean di Alpine
echo ui_button('Buka Modal', [
    'variant' => 'primary',
    'icon'    => 'sparkle',
    'attrs'   => '@click="isModalOpen = true"',
]);

// Memanggil method Store Alpine (misal: Keranjang Belanja)
echo ui_button('Tambah ke Keranjang', [
    'variant' => 'primary',
    'icon'    => 'shopping-cart',
    'attrs'   => '@click="$store.cart.addItem({ id: 10, name: \'Baju Keren\', price: 150000 }, 1)"',
]);
```

### B. Form Input Events (Live Search / Debounce / Formatting)
```php
// Live search dengan debounce
echo ui_input('search_query', [
    'placeholder' => 'Cari produk...',
    'icon'        => 'magnifying-glass',
    'attrs'       => 'x-model.debounce.300ms="searchQuery" @input="fetchResults()"',
]);

// Auto uppercase dan change listener (Vanilla JS)
echo ui_input('voucher_code', [
    'label'       => 'Kode Voucher',
    'placeholder' => 'Masukkan kupon...',
    'attrs'       => 'oninput="this.value = this.value.toUpperCase()" onchange="applyVoucher(this.value)"',
]);
```

### C. Select Dropdown Change Event
```php
// Alpine change event
echo ui_select('sort_by', [
    'newest'     => 'Terbaru',
    'price_asc'  => 'Harga Terendah',
    'price_desc' => 'Harga Tertinggi',
], [
    'label' => 'Urutkan Berdasarkan',
    'attrs' => '@change="updateSorting($event.target.value)"',
]);

// Auto submit form saat dropdown dipilih (Vanilla JS)
echo ui_select('kategori_id', $categoriesList, [
    'label' => 'Filter Kategori',
    'attrs' => 'onchange="this.form.submit()"',
]);
```

### D. Toggle Switch Events & Two-Way Binding
```php
echo ui_toggle('is_featured', 'Tampilkan di Beranda', false, [
    'helper' => 'Produk akan disematkan di bagian atas katalog.',
    'attrs'  => 'x-model="isFeatured" @change="console.log(\'Featured status:\', isFeatured)"',
]);
```

### E. Vanilla JavaScript (`onclick` & `addEventListener` via ID)
```php
// Inline confirm dialog
echo ui_button('Hapus Data', [
    'variant' => 'danger',
    'icon'    => 'trash',
    'attrs'   => 'onclick="if(confirm(\'Yakin ingin menghapus?\')) deleteItem(123)"',
]);

// Menggunakan ID untuk addEventListener
echo ui_button('Export Data', [
    'id'      => 'btn-export-csv',
    'variant' => 'outline',
    'icon'    => 'download-simple',
]);
```
```javascript
// Di bagian script:
document.getElementById('btn-export-csv')?.addEventListener('click', () => {
    exportToCSV();
});
```

---

## 8. Prinsip Desain (Apple Fluid & Zero Shadow)

1. **Zero Shadows**: Tidak menggunakan drop-shadow (`box-shadow: none !important`). Kedalaman visual diciptakan melalui:
   - **Translucent materials**: `backdrop-blur-xl bg-white/90` di atas konten yang bergerak.
   - **Hairline borders**: Garis batas presisi tinggi `border border-slate-200/80` pada mode terang dan `border border-slate-800` pada mode gelap.
2. **Instant Tactile Feedback**: Setiap tombol merespon seketika pada saat pointer-down (`active:scale-[0.975]`).
3. **Typography**: Menggunakan sistem optical sizing dengan tracking lebih rapat (`tracking-tight`) untuk heading besar dan leading yang nyaman untuk isi teks.
4. **Reduced Motion Accessibility**: Transisi dan efek scale otomatis disederhanakan jika perangkat pengguna menyalakan preferensi `prefers-reduced-motion`.

---

✨ *Untuk melihat demo visual interaktif seluruh komponen dan token aktif, buka halaman **Showcase Primitif UI** di `/design-system.php`.*

