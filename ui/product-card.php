<?php
/**
 * Product Card Component Primitive (ui_product_card)
 *
 * Apple Store-inspired e-commerce showcase card with discount badge, stock status,
 * image zoom micro-interaction, and Alpine.js cart interaction.
 */

require_once __DIR__ . '/icon.php';
require_once __DIR__ . '/button.php';
require_once __DIR__ . '/../helpers/format.php';

if (!function_exists('ui_product_card')) {
    function ui_product_card(array $product, array $options = []): string {
        $hasPromo     = !empty($product['promo_price']) && $product['promo_price'] < $product['price'];
        $currentPrice = $hasPromo ? (float)$product['promo_price'] : (float)$product['price'];
        $discountPct  = $hasPromo ? round((($product['price'] - $product['promo_price']) / $product['price']) * 100) : 0;
        $isOutOfStock = ((int)($product['stock'] ?? 0)) <= 0;
        $imgUrl       = upload_url($product['image'] ?? '');
        $prodUrl      = base_url('product.php?id=' . $product['id']);
        $extraCls     = $options['class'] ?? '';
        $categoryName = sanitize($product['category_name'] ?? 'General');
        $prodName     = sanitize($product['name'] ?? 'Product');
        $escapedName  = addslashes($prodName);

        $promoBadge = $hasPromo 
            ? '<span class="px-2 py-0.5 rounded-badge bg-rose-600 text-white text-[10px] font-semibold border border-rose-500/20 select-none">-' . $discountPct . '%</span>' 
            : '';
        
        $featuredBadge = !empty($product['is_featured']) 
            ? '<span class="px-2 py-0.5 rounded-badge bg-amber-500 text-white text-[10px] font-semibold border border-amber-400/20 flex items-center gap-1 select-none"><i class="ph ph-star-fill text-[9px]"></i> Featured</span>' 
            : '';

        $stockOverlay = $isOutOfStock 
            ? '<div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center select-none"><span class="px-3 py-1.5 rounded-badge bg-slate-900 border border-slate-700 text-white text-xs font-semibold">Out of Stock</span></div>' 
            : '';

        $promoStrike = $hasPromo 
            ? '<span class="text-xs font-medium text-slate-400 line-through">' . format_rupiah($product['price']) . '</span>' 
            : '';

        $cartBtnDisabled = $isOutOfStock ? 'disabled' : '';
        $cartBtnClass = $isOutOfStock 
            ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200' 
            : 'bg-brand-600 hover:bg-brand-700 text-white border border-brand-500/20 active:bg-brand-800';

        $cartIcon = ui_icon('shopping-cart-simple', 'text-sm flex-shrink-0');

        return "
        <div class=\"group bg-white rounded-card border border-slate-200/80 hover:border-brand-400 transition-all duration-150 flex flex-col overflow-hidden $extraCls\">
            <div class=\"relative aspect-square overflow-hidden bg-slate-50\">
                <a href=\"$prodUrl\" class=\"block w-full h-full\">
                    <img src=\"$imgUrl\" alt=\"$prodName\" loading=\"lazy\" class=\"w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-out\">
                </a>
                <div class=\"absolute top-3 left-3 flex flex-col gap-1.5 z-10 pointer-events-none\">
                    $promoBadge
                    $featuredBadge
                </div>
                $stockOverlay
            </div>

            <div class=\"p-5 flex-1 flex flex-col justify-between\">
                <div>
                    <div class=\"flex items-center justify-between text-[11px] text-slate-400 mb-1.5\">
                        <span class=\"font-medium uppercase tracking-wider text-[10px] text-slate-500\">$categoryName</span>
                        <span class=\"font-medium " . ($isOutOfStock ? 'text-rose-500' : 'text-slate-500') . "\">
                            " . ($isOutOfStock ? 'Sold out' : 'Stock: ' . (int)$product['stock']) . "
                        </span>
                    </div>
                    <h3 class=\"font-semibold text-sm text-slate-900 line-clamp-2 group-hover:text-brand-600 transition-colors leading-snug tracking-tight\">
                        <a href=\"$prodUrl\">$prodName</a>
                    </h3>
                </div>

                <div class=\"mt-4 pt-3.5 border-t border-slate-100\">
                    <div class=\"mb-3.5\">
                        <div class=\"flex items-baseline gap-2\">
                            <span class=\"text-base sm:text-lg font-semibold text-brand-600 tracking-tight\">
                                " . format_rupiah($currentPrice) . "
                            </span>
                            $promoStrike
                        </div>
                    </div>

                    <div class=\"grid grid-cols-2 gap-2\">
                        <a href=\"$prodUrl\" class=\"py-2 px-3 rounded-btn bg-slate-100 hover:bg-slate-200/80 text-slate-800 border border-slate-200/80 text-xs font-semibold text-center transition-all apple-tap\">
                            Details
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
                            class=\"py-2 px-3 rounded-btn $cartBtnClass text-xs font-semibold text-center transition-all apple-tap flex items-center justify-center gap-1.5\">
                            $cartIcon
                            <span>+ Add</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>";
    }
}

