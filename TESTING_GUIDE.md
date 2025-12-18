# PANDUAN CUSTOMER UI E-COMMERCE

## ✅ YANG SUDAH DIBUAT

### 1. Controllers
- `ShopController.php` - Katalog produk dengan search dan filter kategori
- `CartController.php` - Shopping cart (session-based)
- `CheckoutController.php` - Proses checkout dan pembayaran
- `OrderController.php` - Riwayat pesanan customer

### 2. Routes
File `routes/web.php` sudah diupdate dengan:
- Customer routes (shop, cart, checkout, orders)
- Admin routes (dengan middleware `role:admin`)
- Redirect homepage berdasarkan role

### 3. Views
**Layout:**
- `resources/views/layouts/customer.blade.php` - Layout Bootstrap untuk customer

**Shop/Katalog:**
- `resources/views/customer/shop/index.blade.php` - Grid katalog produk
- `resources/views/customer/shop/show.blade.php` - Detail produk

**Cart:**
- `resources/views/customer/cart/index.blade.php` - Keranjang belanja

**Checkout:**
- `resources/views/customer/checkout/index.blade.php` - Form checkout + upload bukti

**Orders:**
- `resources/views/customer/orders/index.blade.php` - List pesanan customer
- `resources/views/customer/orders/show.blade.php` - Detail pesanan

### 4. Middleware
- `RoleMiddleware.php` - Sudah registered di `bootstrap/app.php`

---

## 🚀 CARA TESTING

### A. TEST SEBAGAI CUSTOMER (Pembeli)

#### 1. Register Akun Customer Baru
```
1. Buka: http://localhost:8000/register
2. Isi form:
   - Name: Customer Test
   - Email: customer@test.com
   - Password: customer123
   - Password Confirmation: customer123
3. Klik Register
4. Otomatis login sebagai customer (role: 'customer')
5. Redirect ke: http://localhost:8000/shop (katalog produk)
```

#### 2. Browse dan Cari Produk
```
- Filter berdasarkan kategori (Elektronik, Fashion, dll)
- Gunakan search box untuk cari produk
- Klik "Detail" untuk lihat detail produk
```

#### 3. Tambah ke Keranjang
```
- Dari katalog: klik "Tambah ke Keranjang"
- Dari detail produk: atur jumlah → klik "Tambah ke Keranjang"
- Badge di navbar akan update otomatis
```

#### 4. Kelola Keranjang
```
- Klik "Keranjang" di navbar
- Update jumlah produk
- Hapus produk dari keranjang
- Kosongkan semua keranjang
```

#### 5. Checkout dan Bayar
```
1. Klik "Lanjut ke Pembayaran" di keranjang
2. Isi alamat pengiriman
3. Upload bukti pembayaran (JPG/PNG max 2MB)
4. Klik "Proses Pesanan"
5. Redirect ke detail pesanan
```

#### 6. Lihat Riwayat Pesanan
```
- Klik "Pesanan Saya" di navbar
- Lihat status: Pending / Lunas / Ditolak
- Klik "Detail Pesanan" untuk lihat detail
```

---

### B. TEST SEBAGAI ADMIN

#### 1. Login sebagai Admin
```
Email: admin@admin.com
Password: admin123
```

#### 2. Verifikasi Pesanan Customer
**CATATAN:** Belum ada UI admin untuk verifikasi. Bisa manual via database:
```sql
-- Lihat pesanan pending
SELECT * FROM orders WHERE status_pembayaran = 'pending';

-- Approve pesanan (ubah status jadi lunas)
UPDATE orders SET status_pembayaran = 'lunas' WHERE id = 1;

-- Tolak pesanan
UPDATE orders SET status_pembayaran = 'ditolak' WHERE id = 1;
```

---

## 📋 CHECKLIST TESTING

### Customer Flow:
- [ ] Register akun customer baru
- [ ] Login sebagai customer → redirect ke /shop
- [ ] Browse katalog produk
- [ ] Filter by kategori
- [ ] Search produk
- [ ] Lihat detail produk
- [ ] Tambah produk ke keranjang
- [ ] Update jumlah di keranjang
- [ ] Hapus item dari keranjang
- [ ] Checkout dengan alamat pengiriman
- [ ] Upload bukti pembayaran
- [ ] Lihat riwayat pesanan
- [ ] Lihat detail pesanan

### Admin Flow:
- [ ] Login sebagai admin → redirect ke /dashboard
- [ ] CRUD Categories (sudah ada)
- [ ] CRUD Products (sudah ada)
- [ ] Customer tidak bisa akses /dashboard, /categories, /products
- [ ] Admin tidak bisa akses /shop, /cart, /checkout

---

## 🛠️ FITUR YANG SUDAH ADA

### 1. Role-Based Access Control
- Admin: Akses dashboard, CRUD categories & products
- Customer: Akses shop, cart, checkout, orders
- Redirect otomatis berdasarkan role

### 2. Shopping Cart (Session-Based)
- Tambah produk ke cart
- Update quantity
- Remove item
- Clear cart
- Badge counter di navbar

### 3. Checkout & Payment
- Form alamat pengiriman
- Upload bukti pembayaran (image)
- Generate order number otomatis
- Stock reduction otomatis
- Order history untuk customer

### 4. Order Management
- Status: pending, lunas, ditolak
- Order number auto-generate (ORD-timestamp)
- Price snapshot di order_products (harga tidak berubah jika admin edit harga produk)
- Relationship: User → Orders → Products via pivot

---

## 🎯 YANG MASIH BISA DITAMBAHKAN (OPSIONAL)

### 1. Admin Order Management UI
```php
// Route
Route::get('/admin/orders', [AdminOrderController::class, 'index']);
Route::patch('/admin/orders/{order}/approve', [AdminOrderController::class, 'approve']);

// View: resources/views/admin/orders/index.blade.php
// - List semua orders
// - Filter by status
// - View bukti pembayaran
// - Approve/reject payment
```

### 2. Email Notifications
```bash
php artisan make:notification OrderCreated
php artisan make:notification PaymentApproved
```

### 3. Advanced Features
- Rating & review produk
- Wishlist
- Order tracking (processing, shipped, delivered)
- Multiple payment methods
- Discount codes / vouchers

---

## 🐛 TROUBLESHOOTING

### Error: "Target class [RoleMiddleware] does not exist"
**Fix:** Sudah di-register di `bootstrap/app.php` dengan alias 'role'

### Error: Storage symlink not found
```bash
php artisan storage:link
```

### Gambar tidak muncul
```env
# .env
APP_URL=http://localhost:8000
```

### Session cart hilang
Pastikan session driver = database dan table sessions sudah di-migrate:
```bash
php artisan migrate
php artisan config:clear
```

---

## 📸 TESTING DUMMY DATA

### Sample Customer:
```
Email: customer@test.com
Password: customer123
```

### Sample Products:
Admin sudah bisa add produk via http://localhost:8000/products/create

### Sample Order Flow:
1. Login as customer
2. Add 2-3 products to cart
3. Go to checkout
4. Upload dummy image as payment proof
5. Submit order
6. Check "Pesanan Saya"

---

## ✅ STATUS IMPLEMENTASI

| Fitur | Status | Keterangan |
|-------|--------|------------|
| Login/Register | ✅ | Via Laravel Breeze |
| Role Middleware | ✅ | Admin vs Customer |
| Product Catalog | ✅ | Search + Category Filter |
| Shopping Cart | ✅ | Session-based |
| Checkout | ✅ | With payment proof upload |
| Order History | ✅ | Customer view only |
| Admin CRUD | ✅ | Categories & Products |
| Admin Order Management | ❌ | Manual via DB |

---

## 🎉 SELESAI!

Sekarang project Anda memiliki:
1. ✅ **Customer UI lengkap** (katalog, cart, checkout, orders)
2. ✅ **Admin UI** (CRUD categories & products)
3. ✅ **Role-based access control**
4. ✅ **Shopping cart dengan session**
5. ✅ **Order management dengan payment proof**

**Cara test:** Register customer baru → browse shop → add to cart → checkout → view orders
