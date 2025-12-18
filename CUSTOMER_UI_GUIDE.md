# 🛒 UI CUSTOMER E-COMMERCE - PANDUAN LENGKAP

## 📋 Status Project Saat Ini

### ✅ **Yang Sudah Ada (Admin Area):**
- Login & Register (Laravel Breeze)
- Dashboard Admin
- CRUD Categories
- CRUD Products
- Database lengkap (Users, Orders, OrderProducts, Products, Categories)

### ❌ **Yang BELUM Ada (Customer Area):**
- **Katalog Produk** - Customer belum bisa lihat produk
- **Shopping Cart** - Belum ada keranjang belanja
- **Checkout & Pembayaran** - Belum bisa pesan & bayar
- **Daftar Pesanan Customer** - Belum bisa lihat history order
- **Pemisahan Role** - Belum ada middleware admin/customer

---

## 🎯 **SOLUSI: Membuat UI Customer Lengkap**

### Komponen yang Sudah Dibuat:

#### 1. **Middleware Role** ✅
**File:** `app/Http/Middleware/RoleMiddleware.php`
- Memisahkan akses admin dan customer
- Admin bisa akses admin area
- Customer bisa akses customer area

#### 2. **Controllers Customer** ✅
- `ShopController.php` - Katalog produk
- `CartController.php` - Shopping cart
- `CheckoutController.php` - Proses checkout
- `OrderController.php` - Daftar pesanan

---

## 🚀 **LANGKAH INSTALASI UI CUSTOMER**

### Step 1: Lengkapi Controllers

#### CheckoutController.php
```php
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Keranjang kosong!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('customer.checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            // Hitung total
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Upload bukti pembayaran
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            // Buat order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'status_pembayaran' => 'pending',
                'total_amount' => $total,
                'bukti_pembayaran' => $buktiPath,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            // Buat order products & kurangi stok
            foreach ($cart as $productId => $item) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Kurangi stok
                $product = Product::find($productId);
                $product->decrement('stok', $item['quantity']);
            }

            DB::commit();

            // Kosongkan cart
            session()->forget('cart');

            return redirect()->route('customer.orders.show', $order->id)
                           ->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
```

#### OrderController.php
```php
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->with('orderProducts.product')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderProducts.product');
        return view('customer.orders.show', compact('order'));
    }
}
```

---

### Step 2: Buat Routes

Tambahkan di `routes/web.php`:

```php
<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

// Redirect ke shop untuk customer atau dashboard untuk admin
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect('/dashboard') 
            : redirect('/shop');
    }
    return redirect('/shop');
});

// Route auth
require __DIR__.'/auth.php';

// CUSTOMER ROUTES (role: customer atau guest)
Route::group(['prefix' => 'shop'], function () {
    // Shop - bisa diakses tanpa login
    Route::get('/', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');

    // Cart - perlu login sebagai customer
    Route::middleware(['auth', 'role:customer'])->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

        // Customer Orders
        Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
        Route::get('/my-orders/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    });
});

// ADMIN ROUTES (role: admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // CRUD Category
    Route::resource('categories', CategoryController::class);

    // CRUD Product
    Route::resource('products', ProductController::class);
});
```

---

### Step 3: Update User Registration

Tambahkan default role customer saat register. Update `resources/views/livewire/pages/auth/register.blade.php`:

Di bagian Livewire component, tambahkan:
```php
$validated['role'] = 'customer'; // Set default role
```

---

### Step 4: Update Admin User

Set user admin yang sudah ada:
```bash
php artisan tinker
>>> App\Models\User::where('email', 'admin@admin.com')->update(['role' => 'admin']);
```

---

### Step 5: Buat Customer View Files

Struktur folder yang perlu dibuat:
```
resources/views/
├── customer/
│   ├── shop/
│   │   ├── index.blade.php (Katalog produk)
│   │   └── show.blade.php (Detail produk)
│   ├── cart/
│   │   └── index.blade.php (Shopping cart)
│   ├── checkout/
│   │   └── index.blade.php (Checkout form)
│   └── orders/
│       ├── index.blade.php (Daftar pesanan)
│       └── show.blade.php (Detail pesanan)
└── layouts/
    └── customer.blade.php (Layout customer)
```

---

## 📝 **Panduan Lengkap Ada di File Terpisah**

Karena ada **BANYAK file view** yang perlu dibuat (sekitar 6-8 files dengan code panjang), saya sudah:

1. ✅ Membuat semua controllers
2. ✅ Membuat middleware role
3. ✅ Membuat panduan routes
4. ✅ Membuat panduan lengkap

**NEXT STEPS:**

Saya bisa bantu buatkan **semua view files** jika Anda mau. Atau jika Anda mau saya jelaskan secara bertahap?

---

## 🎯 **Testing Plan**

### 1. **Register sebagai Customer**
```
1. Buka http://localhost:8000/register
2. Daftar dengan email: customer@test.com
3. Password: password123
4. Otomatis role = customer
```

### 2. **Test Shopping**
```
1. Login sebagai customer
2. Buka http://localhost:8000/shop
3. Lihat katalog produk
4. Klik produk → lihat detail
5. Add to cart
6. Buka cart → checkout
7. Upload bukti transfer
8. Lihat daftar pesanan
```

### 3. **Login Admin**
```
1. Login dengan admin@admin.com / admin123
2. Akses dashboard admin
3. Kelola produk, kategori
4. (Opsional) Lihat & approve pesanan
```

---

## ✅ **Status Implementasi**

| Komponen | Status | Keterangan |
|----------|--------|------------|
| Middleware Role | ✅ DONE | File sudah dibuat |
| ShopController | ✅ DONE | Katalog & detail produk |
| CartController | ✅ DONE | Shopping cart session-based |
| CheckoutController | 🔄 READY | Code sudah siap, tinggal copy |
| OrderController | 🔄 READY | Code sudah siap, tinggal copy |
| Routes | 📝 GUIDE | Panduan sudah ada |
| Views | ⏳ PENDING | Perlu dibuat 6-8 files |

---

## 💡 **Apakah Anda Ingin:**

1. **Saya buatkan semua view files sekarang?** (6-8 files dengan HTML lengkap)
2. **Saya buatkan bertahap?** (Satu per satu dengan penjelasan)
3. **Saya berikan template starter?** (Template dasar yang bisa Anda customize)

**Silakan beri tahu pilihan Anda!** 🚀
