<?php
/**
 * Product Card Component Primitive (ui_product_card)
 *
 * Expressive e-commerce product card with discount badge, stock badge,
 * image preview, and Alpine.js cart interaction.
 */

require_once __DIR__ . '/icon.php';
require_once __DIR__ . '/../../helpers/format.php';

if (!function_exists('ui_product_card')) {
    function ui_product_card(array $product, array $options = []): string {
        $hasPromo = !empty($product['promo_price']) && $product['promo_price'] < $product['price'];
        $currentPrice = $hasPromo ? (float)$product['promo_price'] : (float)$product['price'];
        $discountPct = $hasPromo ? round((($product['price'] - $product['promo_price']) / $product['price']) * 100) : 0;
        $isOutOfStock = ((int)($product['stock'] ?? 0)) <= 0;
        $imgUrl = upload_url($product['image'] ?? '');
        $prodUrl = base_url('product.php?id=' . $product['id']);
        $extraCls = $options['class'] ?? '';
        $categoryName = sanitize($product['category_name'] ?? 'Umum');
        $prodName = sanitize($product['name'] ?? 'Produk');
        $escapedName = addslashes($prodName);

        $promoBadge = $hasPromo 
            ? '<span class="px-2.5 py-1 rounded-badge bg-rose-600 text-white text-[11px] font-extrabold border border-rose-500/20">-' . $discountPct . '%</span>' 
            : '';
        
        $featuredBadge = !empty($product['is_featured']) 
            ? '<span class="px-2.5 py-1 rounded-badge bg-amber-500 text-white text-[10px] font-bold border border-amber-400/20 flex items-center gap-1">⭐ Pilihan</span>' 
            : '';

        $stockOverlay = $isOutOfStock 
            ? '<div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center"><span class="px-3 py-1.5 rounded-badge bg-slate-900 border border-slate-700 text-white text-xs font-bold">Stok Habis</span></div>' 
            : '';

        $promoStrike = $hasPromo 
            ? '<span class="text-xs font-medium text-slate-400 line-through">' . format_rupiah($product['price']) . '</span>' 
            : '';

        $cartBtnDisabled = $isOutOfStock ? 'disabled' : '';
        $cartBtnClass = $isOutOfStock 
            ? 'bg-slate-200 text-slate-400 cursor-not-allowed border border-slate-300' 
            : 'bg-brand-600 hover:bg-brand-700 text-white border border-brand-500/20';

        $cartIcon = ui_icon('shopping-cart', 'text-sm');

        return "
        <div class=\"group bg-white rounded-card border border-slate-200/80 hover:border-brand-400 transition-colors duration-150 flex flex-col overflow-hidden $extraCls\">
            <div class=\"relative aspect-square overflow-hidden bg-slate-100\">
                <a href=\"$prodUrl\" class=\"block w-full h-full\">
                    <img src=\"$imgUrl\" alt=\"$prodName\" loading=\"lazy\" class=\"w-full h-full object-cover group-hover:scale-105 transition-transform duration-300\">
                </a>
                <div class=\"absolute top-3 left-3 flex flex-col gap-1.5\">
                    $promoBadge
                    $featuredBadge
                </div>
                $stockOverlay
            </div>

            <div class=\"p-5 flex-1 flex flex-col justify-between\">
                <div>
                    <div class=\"flex items-center justify-between text-[11px] text-slate-400 mb-1\">
                        <span>$categoryName</span>
                        <span class=\"font-medium " . ($isOutOfStock ? 'text-rose-500' : 'text-slate-500') . "\">
                            Stok: " . (int)$product['stock'] . "
                        </span>
                    </div>
                    <h3 class=\"font-bold text-sm text-slate-900 line-clamp-2 group-hover:text-brand-600 transition leading-snug tracking-tight\">
                        <a href=\"$prodUrl\">$prodName</a>
                    </h3>
                </div>

                <div class=\"mt-4 pt-3 border-t border-slate-100\">
                    <div class=\"mb-3\">
                        <div class=\"flex items-baseline gap-2\">
                            <span class=\"text-base font-extrabold text-brand-600 tracking-tight\">
                                " . format_rupiah($currentPrice) . "
                            </span>
                            $promoStrike
                        </div>
                    </div>

                    <div class=\"grid grid-cols-2 gap-2\">
                        <a href=\"$prodUrl\" class=\"py-2.5 px-3 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/80 text-xs font-bold text-center transition apple-tap\">
                            Detail
                        </a>

                        <button 
                            type=\"button\" 
                            $cartBtnDisabled 
                            @click=\"\$store.cart.addItem({
                                id: {$product['id']},
                                name: '$escapedName',
                                price: $currentPrice,
                                image: '$imgUrl',
                                stock: " . (int)$product['stock'] . "
                            }, 1)\" 
                            class=\"py-2.5 px-3 rounded-btn $cartBtnClass text-xs font-bold text-center transition apple-tap flex items-center justify-center gap-1.5\">
                            $cartIcon
                            <span>+ Keranjang</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>";
    }
}
