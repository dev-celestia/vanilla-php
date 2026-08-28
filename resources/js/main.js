import '../css/app.css';
import '@phosphor-icons/web/regular';
import '@phosphor-icons/web/bold';
import '@phosphor-icons/web/fill';
import '@phosphor-icons/web/duotone';
import '@phosphor-icons/web/light';
import '@phosphor-icons/web/thin';
import Alpine from 'alpinejs';

// Initialize Alpine Cart Store
document.addEventListener('alpine:init', () => {
  if (!Alpine.store('cart')) {
    Alpine.store('cart', {
      items: JSON.parse(localStorage.getItem('native_shop_cart') || '[]'),
      isOpen: false,

      init() {
        this.save();
      },

      save() {
        localStorage.setItem('native_shop_cart', JSON.stringify(this.items));
      },

      addItem(product, qty = 1) {
        const existing = this.items.find(i => i.id === product.id);
        const quantityToAdd = parseInt(qty) || 1;

        if (existing) {
          if (product.stock && (existing.qty + quantityToAdd) > product.stock) {
            alert('Maaf, jumlah pesanan melebihi stok yang tersedia (' + product.stock + ' unit).');
            existing.qty = product.stock;
          } else {
            existing.qty += quantityToAdd;
          }
        } else {
          if (product.stock && quantityToAdd > product.stock) {
            alert('Maaf, jumlah melebihi stok yang tersedia.');
            return;
          }
          this.items.push({
            id: product.id,
            name: product.name,
            price: parseFloat(product.price),
            image: product.image,
            stock: parseInt(product.stock) || 999,
            qty: quantityToAdd
          });
        }
        this.save();
        this.isOpen = true;
      },

      updateQty(id, delta) {
        const item = this.items.find(i => i.id === id);
        if (item) {
          const newQty = item.qty + delta;
          if (newQty <= 0) {
            this.removeItem(id);
          } else if (newQty > item.stock) {
            alert('Maksimal stok tersedia: ' + item.stock);
          } else {
            item.qty = newQty;
            this.save();
          }
        }
      },

      removeItem(id) {
        this.items = this.items.filter(i => i.id !== id);
        this.save();
      },

      clearCart() {
        this.items = [];
        this.save();
      },

      get count() {
        return this.items.reduce((sum, item) => sum + item.qty, 0);
      },

      get subtotal() {
        return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
      },

      formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0
        }).format(amount);
      }
    });
  }
});

// Expose Alpine globally & start
window.Alpine = Alpine;
Alpine.start();
