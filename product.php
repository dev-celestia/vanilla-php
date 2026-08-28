<?php
/**
 * Product Detail Page
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$productSlug = trim($_GET['slug'] ?? '');

$db = getDB();
$product = null;
$relatedProducts = [];

if ($db && ($productId > 0 || !empty($productSlug))) {
    try {
        if ($productId > 0) {
            $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug 
                                  FROM products p 
                                  LEFT JOIN categories c ON p.category_id = c.id 
                                  WHERE p.id = :id AND p.is_active = 1 LIMIT 1");
            $stmt->execute([':id' => $productId]);
        } else {
            $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug 
                                  FROM products p 
                                  LEFT JOIN categories c ON p.category_id = c.id 
                                  WHERE p.slug = :slug AND p.is_active = 1 LIMIT 1");
            $stmt->execute([':slug' => $productSlug]);
        }
        $product = $stmt->fetch();

        if ($product) {
            // Fetch related products
            $relStmt = $db->prepare("SELECT p.*, c.name as category_name 
                                    FROM products p 
                                    LEFT JOIN categories c ON p.category_id = c.id 
                                    WHERE p.category_id = :cat_id AND p.id != :prod_id AND p.is_active = 1 
                                    LIMIT 4");
            $relStmt->execute([':cat_id' => $product['category_id'], ':prod_id' => $product['id']]);
            $relatedProducts = $relStmt->fetchAll();
        }
    } catch (PDOException $e) {
        error_log('Error loading product: ' . $e->getMessage());
    }
}

if (!$product) {
    header('Location: ' . base_url('demo.php'));
    exit;
}

$page_title = sanitize($product['name']) . ' - ' . get_settings()['store_name'];
$hasPromo = !empty($product['promo_price']) && $product['promo_price'] < $product['price'];
$currentPrice = $hasPromo ? $product['promo_price'] : $product['price'];
$discountPct = $hasPromo ? round((($product['price'] - $product['promo_price']) / $product['price']) * 100) : 0;
$isOutOfStock = $product['stock'] <= 0;
$imgUrl = upload_url($product['image']);

// Format WhatsApp direct message for single item
$waNumber = preg_replace('/[^0-9]/', '', get_settings()['whatsapp_number']);
$waDirectText = "Halo Admin " . get_settings()['store_name'] . ", saya ingin memesan:\n\n"
              . "• Produk: " . $product['name'] . "\n"
              . "• Harga: " . format_rupiah($currentPrice) . "\n"
              . "• Link: " . base_url('product.php?id=' . $product['id']) . "\n\n"
              . "Mohon info ketersediaan stok & ongkirnya ya. Terima kasih!";
$waDirectUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waDirectText);

require_once __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb Primitive -->
<?= ui_breadcrumb([
    ['label' => 'Beranda', 'href' => base_url()],
    ['label' => 'Demo E-Commerce', 'href' => base_url('demo.php')],
    ['label' => $product['category_name'] ?? 'Kategori', 'href' => base_url('demo.php?category=' . $product['category_id'])],
    ['label' => $product['name']]
]) ?>

<!-- Product Main Container -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16" x-data="{ orderQty: 1 }">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left Column: Gallery Image (Zero Shadow, Crisp Border) -->
        <div class="lg:col-span-5">
            <div class="sticky top-28 space-y-4">
                <div class="relative bg-white rounded-card border border-slate-200/80 p-3 overflow-hidden">
                    <img 
                        src="<?= $imgUrl ?>" 
                        alt="<?= sanitize($product['name']) ?>" 
                        class="w-full aspect-square object-cover rounded-btn"
                    >
                    <?php if ($hasPromo): ?>
                        <div class="absolute top-6 left-6">
                            <?= ui_badge('HEMAT ' . $discountPct . '%', 'danger') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quick trust info -->
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 bg-white rounded-card border border-slate-200/80">
                        <?= ui_icon_box('package', 'brand', ['size' => 'md', 'class' => 'mx-auto mb-1']) ?>
                        <p class="text-[11px] font-bold text-slate-800 tracking-tight">Quality Check</p>
                        <p class="text-[10px] text-slate-500">Dicek sebelum kirim</p>
                    </div>
                    <div class="p-3 bg-white rounded-card border border-slate-200/80">
                        <?= ui_icon_box('truck', 'brand', ['size' => 'md', 'class' => 'mx-auto mb-1']) ?>
                        <p class="text-[11px] font-bold text-slate-800 tracking-tight">Pengiriman Cepat</p>
                        <p class="text-[10px] text-slate-500">Packing aman bubble wrap</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Details & Actions -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Category & Title -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <?= ui_badge(sanitize($product['category_name'] ?? 'Katalog'), 'brand') ?>
                    <?= ui_badge($isOutOfStock ? 'Stok Habis' : 'Sisa Stok: ' . $product['stock'] . ' unit', $isOutOfStock ? 'danger' : 'neutral', ['dot' => true]) ?>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                    <?= sanitize($product['name']) ?>
                </h1>
            </div>

            <!-- Price Container (Flat Crisp Border, Zero Shadow) -->
            <div class="p-5 rounded-card bg-brand-50/60 border border-brand-200/80 flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-500 block mb-0.5">Harga Spesial</span>
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-black text-brand-600 tracking-tight">
                            <?= format_rupiah($currentPrice) ?>
                        </span>
                        <?php if ($hasPromo): ?>
                            <span class="text-sm font-semibold text-slate-400 line-through">
                                <?= format_rupiah($product['price']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($hasPromo): ?>
                    <?= ui_badge('Diskon ' . $discountPct . '%', 'danger') ?>
                <?php endif; ?>
            </div>

            <!-- Quantity Selector & Actions -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-slate-700 tracking-tight">Jumlah Pesanan:</span>
                    <div class="flex items-center border border-slate-200/90 rounded-btn bg-white overflow-hidden">
                        <button 
                            type="button" 
                            @click="orderQty = Math.max(1, orderQty - 1)" 
                            class="px-3.5 py-2 text-slate-600 hover:bg-slate-100 font-bold text-sm transition apple-tap">
                            -
                        </button>
                        <input 
                            type="number" 
                            x-model.number="orderQty" 
                            min="1" 
                            max="<?= (int)$product['stock'] ?>"
                            class="w-14 py-2 text-center text-sm font-bold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        >
                        <button 
                            type="button" 
                            @click="orderQty = Math.min(<?= (int)$product['stock'] ?>, orderQty + 1)" 
                            class="px-3.5 py-2 text-slate-600 hover:bg-slate-100 font-bold text-sm transition apple-tap">
                            +
                        </button>
                    </div>
                    <span class="text-xs text-slate-400">Tersedia <?= $product['stock'] ?> pcs</span>
                </div>

                <!-- CTA Buttons (Apple Tactile) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <button 
                        type="button" 
                        <?= $isOutOfStock ? 'disabled' : '' ?>
                        @click="$store.cart.addItem({
                            id: <?= $product['id'] ?>,
                            name: '<?= addslashes(sanitize($product['name'])) ?>',
                            price: <?= (float)$currentPrice ?>,
                            image: '<?= $imgUrl ?>',
                            stock: <?= (int)$product['stock'] ?>
                        }, orderQty)"
                        class="w-full py-3.5 px-5 rounded-btn <?= $isOutOfStock ? 'bg-slate-200 text-slate-400 cursor-not-allowed border border-slate-300' : 'bg-slate-900 hover:bg-slate-800 text-white border border-slate-800' ?> font-bold text-sm transition apple-tap flex items-center justify-center gap-2">
                        <i class="ph ph-shopping-cart text-base"></i>
                        <span>Tambah ke Keranjang</span>
                    </button>

                    <?= ui_button('Pesan Cepat via WhatsApp', [
                        'variant' => 'primary',
                        'size'    => 'lg',
                        'href'    => $waDirectUrl,
                        'target'  => '_blank',
                        'icon'    => 'whatsapp-logo',
                        'class'   => 'w-full',
                    ]) ?>
                </div>
            </div>

            <!-- Description -->
            <div class="pt-6 border-t border-slate-200/80 space-y-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Deskripsi Lengkap Produk</h3>
                <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-white p-6 rounded-card border border-slate-200/80">
                    <?= nl2br(sanitize($product['description'] ?? 'Belum ada deskripsi untuk produk ini.')) ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="mt-20 pt-12 border-t border-slate-200/80">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Produk Serupa Lainnya</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Rekomendasi produk terkait dalam kategori yang sama</p>
                </div>
                <a href="<?= base_url('demo.php?category=' . $product['category_id']) ?>" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                    <span>Lihat Lainnya</span>
                    <i class="ph ph-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($relatedProducts as $rel): ?>
                    <?= ui_product_card($rel) ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
