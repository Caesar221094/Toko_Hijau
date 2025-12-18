# Entity Relationship Diagram (ERD) - Sistem E-Commerce

## 📚 Tujuan Pembelajaran

✅ **SEMUA TUJUAN PEMBELAJARAN SUDAH TERCAPAI!**

1. ✅ Menjelaskan konsep dasar Entity Relationship Diagram (ERD) dan perannya dalam perancangan basis data
2. ✅ Mengidentifikasi entitas, atribut, serta relasi yang relevan dalam sistem e-commerce
3. ✅ Memodelkan hubungan many-to-many menggunakan tabel penghubung (OrderProducts)
4. ✅ Menyusun ERD untuk sistem e-commerce dengan entitas Users, Orders, OrderProducts, Products, dan Categories
5. ✅ Mengonversi ERD menjadi struktur tabel database dalam bentuk SQL (Laravel Migration)

---

## 🗂️ Entitas dalam Sistem E-Commerce

### 1. **Users** (Pengguna)
Menyimpan informasi pengguna/customer yang dapat melakukan pemesanan.

**Atribut:**
- `id` (PK) - Primary Key
- `name` - Nama lengkap
- `email` - Email (unique)
- `password` - Password (hashed)
- `email_verified_at` - Waktu verifikasi email
- `remember_token` - Token untuk remember me
- `created_at` - Waktu dibuat
- `updated_at` - Waktu diupdate

### 2. **Categories** (Kategori Produk)
Menyimpan kategori untuk mengelompokkan produk.

**Atribut:**
- `id` (PK) - Primary Key
- `nama` - Nama kategori
- `created_at` - Waktu dibuat
- `updated_at` - Waktu diupdate

### 3. **Products** (Produk)
Menyimpan informasi produk yang dijual.

**Atribut:**
- `id` (PK) - Primary Key
- `nama` - Nama produk
- `deskripsi` - Deskripsi produk
- `foto` - Path foto produk
- `harga` - Harga produk (decimal 13,2)
- `stok` - Stok tersedia (unsigned integer)
- `category_id` (FK) - Foreign Key ke Categories
- `created_at` - Waktu dibuat
- `updated_at` - Waktu diupdate

### 4. **Orders** (Pesanan)
Menyimpan informasi pesanan yang dibuat oleh user.

**Atribut:**
- `id` (PK) - Primary Key
- `user_id` (FK) - Foreign Key ke Users
- `order_number` - Nomor order unik (format: ORD-YYYYMMDD-XXXXXX)
- `status` - Status pesanan (pending, processing, completed, cancelled)
- `total_amount` - Total harga pesanan (decimal 15,2)
- `shipping_address` - Alamat pengiriman
- `notes` - Catatan pesanan
- `created_at` - Waktu dibuat
- `updated_at` - Waktu diupdate

### 5. **OrderProducts** (Detail Pesanan - Tabel Penghubung)
Tabel pivot untuk relasi many-to-many antara Orders dan Products.
Menyimpan detail produk dalam setiap pesanan.

**Atribut:**
- `id` (PK) - Primary Key
- `order_id` (FK) - Foreign Key ke Orders
- `product_id` (FK) - Foreign Key ke Products
- `quantity` - Jumlah produk dipesan
- `price` - Harga per unit saat order dibuat (snapshot)
- `subtotal` - Subtotal (quantity × price)
- `created_at` - Waktu dibuat
- `updated_at` - Waktu diupdate

---

## 🔗 Relasi Antar Entitas

### 1. **Users → Orders** (One-to-Many)
- **Kardinalitas:** 1:N
- **Deskripsi:** Satu user dapat memiliki banyak pesanan
- **Foreign Key:** `orders.user_id` → `users.id`
- **On Delete:** CASCADE (jika user dihapus, semua pesanannya ikut terhapus)

```
User (1) ──────< Orders (N)
```

### 2. **Categories → Products** (One-to-Many)
- **Kardinalitas:** 1:N
- **Deskripsi:** Satu kategori dapat memiliki banyak produk
- **Foreign Key:** `products.category_id` → `categories.id`
- **On Delete:** CASCADE

```
Category (1) ──────< Products (N)
```

### 3. **Orders → OrderProducts** (One-to-Many)
- **Kardinalitas:** 1:N
- **Deskripsi:** Satu pesanan dapat memiliki banyak detail produk
- **Foreign Key:** `order_products.order_id` → `orders.id`
- **On Delete:** CASCADE

```
Order (1) ──────< OrderProducts (N)
```

### 4. **Products → OrderProducts** (One-to-Many)
- **Kardinalitas:** 1:N
- **Deskripsi:** Satu produk dapat muncul di banyak detail pesanan
- **Foreign Key:** `order_products.product_id` → `products.id`
- **On Delete:** CASCADE

```
Product (1) ──────< OrderProducts (N)
```

### 5. **Orders ↔ Products** (Many-to-Many)
- **Kardinalitas:** N:M
- **Deskripsi:** Satu pesanan dapat memiliki banyak produk, dan satu produk dapat ada di banyak pesanan
- **Tabel Penghubung:** `order_products`
- **Ini adalah relasi many-to-many yang paling penting dalam e-commerce**

```
Orders (N) ────< OrderProducts >──── Products (M)
```

---

## 📊 ERD Diagram (Tekstual)

```
┌─────────────────────┐
│      USERS          │
│─────────────────────│
│ PK  id              │
│     name            │
│     email (unique)  │
│     password        │
│     created_at      │
│     updated_at      │
└─────────────────────┘
          │
          │ 1:N (One User has Many Orders)
          ▼
┌─────────────────────┐
│      ORDERS         │
│─────────────────────│
│ PK  id              │
│ FK  user_id         │───────┐
│     order_number    │       │
│     status          │       │
│     total_amount    │       │
│     shipping_address│       │
│     notes           │       │
│     created_at      │       │
│     updated_at      │       │
└─────────────────────┘       │
          │                    │
          │ 1:N                │
          ▼                    │
┌─────────────────────┐       │
│  ORDER_PRODUCTS     │       │
│  (Tabel Pivot)      │       │
│─────────────────────│       │
│ PK  id              │       │
│ FK  order_id        │───────┘
│ FK  product_id      │───────┐
│     quantity        │       │
│     price (snapshot)│       │
│     subtotal        │       │
│     created_at      │       │
│     updated_at      │       │
└─────────────────────┘       │
                               │
          ┌────────────────────┘
          │ N:1
          ▼
┌─────────────────────┐
│     PRODUCTS        │
│─────────────────────│
│ PK  id              │
│     nama            │
│     deskripsi       │
│     foto            │
│     harga           │
│     stok            │
│ FK  category_id     │───────┐
│     created_at      │       │
│     updated_at      │       │
└─────────────────────┘       │
                               │ N:1
                               ▼
                    ┌─────────────────────┐
                    │    CATEGORIES       │
                    │─────────────────────│
                    │ PK  id              │
                    │     nama            │
                    │     created_at      │
                    │     updated_at      │
                    └─────────────────────┘
```

---

## 🎯 Penjelasan Relasi Many-to-Many

### Mengapa Perlu Tabel Penghubung (OrderProducts)?

**Masalah tanpa tabel penghubung:**
- ❌ Satu pesanan bisa punya banyak produk
- ❌ Satu produk bisa ada di banyak pesanan
- ❌ Tidak bisa langsung menyimpan relasi many-to-many di database relational

**Solusi: Tabel Penghubung (OrderProducts)**
- ✅ Memecah relasi many-to-many menjadi dua relasi one-to-many
- ✅ Dapat menyimpan atribut tambahan (quantity, price snapshot, subtotal)
- ✅ Memudahkan query dan maintain data integrity

**Contoh Kasus:**
```
Order #ORD-20251218-ABC123
├── Product "Laptop Asus" × 2 unit @ Rp 8.000.000 = Rp 16.000.000
├── Product "Mouse Logitech" × 1 unit @ Rp 150.000 = Rp 150.000
└── Product "Keyboard Mechanical" × 1 unit @ Rp 1.200.000 = Rp 1.200.000
    Total: Rp 17.350.000

Dalam tabel order_products akan ada 3 baris:
1. order_id=1, product_id=1, quantity=2, price=8000000, subtotal=16000000
2. order_id=1, product_id=2, quantity=1, price=150000, subtotal=150000
3. order_id=1, product_id=3, quantity=1, price=1200000, subtotal=1200000
```

---

## 💾 Struktur SQL (Laravel Migration)

### Migration Orders Table
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
          ->constrained('users')
          ->onDelete('cascade');
    $table->string('order_number')->unique();
    $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
          ->default('pending');
    $table->decimal('total_amount', 15, 2)->default(0);
    $table->text('shipping_address')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### Migration OrderProducts Table (Tabel Pivot)
```php
Schema::create('order_products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')
          ->constrained('orders')
          ->onDelete('cascade');
    $table->foreignId('product_id')
          ->constrained('products')
          ->onDelete('cascade');
    $table->unsignedInteger('quantity')->default(1);
    $table->decimal('price', 13, 2);
    $table->decimal('subtotal', 15, 2);
    $table->timestamps();
    $table->index(['order_id', 'product_id']);
});
```

---

## 🔧 Implementasi di Laravel Models

### Model Order
```php
class Order extends Model
{
    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke OrderProducts
    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }
    
    // Relasi Many-to-Many ke Products
    public function products() {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }
}
```

### Model OrderProduct (Tabel Pivot)
```php
class OrderProduct extends Model
{
    // Relasi ke Order
    public function order() {
        return $this->belongsTo(Order::class);
    }
    
    // Relasi ke Product
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
```

### Model User
```php
class User extends Authenticatable
{
    // Relasi ke Orders
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
```

### Model Product
```php
class Product extends Model
{
    // Relasi ke Category
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    // Relasi ke OrderProducts
    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }
    
    // Relasi Many-to-Many ke Orders
    public function orders() {
        return $this->belongsToMany(Order::class, 'order_products')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }
}
```

### Model Category
```php
class Category extends Model
{
    // Relasi ke Products
    public function products() {
        return $this->hasMany(Product::class);
    }
}
```

---

## 📝 Contoh Query dengan Relasi

### 1. Ambil semua pesanan user beserta produknya
```php
$user = User::find(1);
$orders = $user->orders()->with('products')->get();
```

### 2. Ambil detail produk dalam satu pesanan
```php
$order = Order::find(1);
foreach ($order->orderProducts as $item) {
    echo $item->product->nama . ' x ' . $item->quantity;
    echo ' @ Rp ' . number_format($item->price, 0);
    echo ' = Rp ' . number_format($item->subtotal, 0);
}
```

### 3. Ambil semua pesanan yang mengandung produk tertentu
```php
$product = Product::find(1);
$orders = $product->orders;
```

### 4. Hitung total penjualan produk
```php
$product = Product::find(1);
$totalSold = $product->orderProducts()->sum('quantity');
$totalRevenue = $product->orderProducts()->sum('subtotal');
```

---

## ✅ Status Implementasi

| Komponen | Status | File |
|----------|--------|------|
| **Migration Users** | ✅ | `0001_01_01_000000_create_users_table.php` |
| **Migration Categories** | ✅ | `2025_11_19_104008_create_categories_table.php` |
| **Migration Products** | ✅ | `2025_11_19_155636_create_products_table.php` |
| **Migration Orders** | ✅ | `2025_12_18_065554_create_orders_table.php` |
| **Migration OrderProducts** | ✅ | `2025_12_18_065559_create_order_products_table.php` |
| **Model User** | ✅ | `app/Models/User.php` |
| **Model Category** | ✅ | `app/Models/Category.php` |
| **Model Product** | ✅ | `app/Models/Product.php` |
| **Model Order** | ✅ | `app/Models/Order.php` |
| **Model OrderProduct** | ✅ | `app/Models/OrderProduct.php` |
| **Database Tables** | ✅ | Sudah dijalankan `php artisan migrate` |

---

## 🎓 Kesimpulan

### Tujuan Pembelajaran yang Dicapai:

1. ✅ **Konsep ERD** - ERD sudah dijelaskan dengan diagram lengkap
2. ✅ **Identifikasi Entitas** - 5 entitas utama sudah diidentifikasi dengan atribut lengkap
3. ✅ **Relasi Many-to-Many** - Implementasi menggunakan tabel OrderProducts sebagai pivot
4. ✅ **ERD E-Commerce** - ERD lengkap dengan Users, Orders, OrderProducts, Products, Categories
5. ✅ **Konversi ke SQL** - Sudah dikonversi menjadi Laravel Migration yang berfungsi

### Database E-Commerce Sudah Siap! 🎉

Struktur database untuk sistem e-commerce sudah lengkap dan siap digunakan untuk:
- Manajemen produk dan kategori ✅
- Sistem pemesanan (order management) ✅
- Tracking detail pesanan per produk ✅
- Relasi user dengan pesanan mereka ✅
- Query data penjualan dan laporan ✅
