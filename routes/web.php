<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\BasketController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\Customer\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Products (Public viewing)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    // Basket
    Route::get('/basket', [BasketController::class, 'index'])->name('basket.index');
    Route::post('/basket/add/{product}', [BasketController::class, 'add'])->name('basket.add');
    Route::patch('/basket/update/{basket}', [BasketController::class, 'update'])->name('basket.update');
    Route::delete('/basket/remove/{basket}', [BasketController::class, 'destroy'])->name('basket.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventory Management
    Route::resource('inventory', InventoryController::class)->parameters([
        'inventory' => 'inventory'
    ]);
});

// Wishlist Routes (Protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/move-to-basket/{product}', [WishlistController::class, 'moveToBasket'])->name('wishlist.move-to-basket');
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
});

// Review Routes (Protected by auth)
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin Order Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Chatbot Routes
Route::post('/chatbot/message', [ChatBotController::class, 'sendMessage'])->name('chatbot.message');
Route::post('/chatbot/clear', [ChatBotController::class, 'clearHistory'])->name('chatbot.clear');


Route::get('/test-gemini', function() {
    $apiKey = config('services.gemini.api_key');

    if (!$apiKey || $apiKey === 'your-gemini-api-key-here') {
        return 'API Key NOT set! Please add GEMINI_API_KEY to .env';
    }

    return 'API Key found: ' . substr($apiKey, 0, 10) . '...';
});

// Payment Routes (inside auth middleware group)
Route::middleware('auth')->group(function () {
    // ... your existing routes ...

    // Payment Gateway Routes
    Route::post('/payment/checkout', [PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Order Tracking Routes
Route::get('/orders/{order}/track', [App\Http\Controllers\Customer\OrderController::class, 'track'])->name('orders.track');


Route::get('/list-models', function() {
    $apiKey = config('services.gemini.api_key');

    try {
        // Try v1
        $responseV1 = Http::withoutVerifying()->get('https://generativelanguage.googleapis.com/v1/models?key=' . $apiKey);

        // Try v1beta
        $responseV1Beta = Http::withoutVerifying()->get('https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey);

        echo "<h1>API Key Status</h1>";
        echo "<p>Key: " . substr($apiKey, 0, 20) . "...</p>";

        echo "<h2>V1 Models:</h2>";
        if ($responseV1->successful()) {
            $data = $responseV1->json();
            if (isset($data['models'])) {
                echo "<ul>";
                foreach ($data['models'] as $model) {
                    if (isset($model['name']) && strpos($model['name'], 'gemini') !== false) {
                        $supportedMethods = isset($model['supportedGenerationMethods']) ? implode(', ', $model['supportedGenerationMethods']) : 'N/A';
                        echo "<li><strong>" . $model['name'] . "</strong> - Methods: " . $supportedMethods . "</li>";
                    }
                }
                echo "</ul>";
            } else {
                echo "<p>No models found</p>";
            }
        } else {
            echo "<pre>Error: " . $responseV1->body() . "</pre>";
        }

        echo "<h2>V1Beta Models:</h2>";
        if ($responseV1Beta->successful()) {
            $data = $responseV1Beta->json();
            if (isset($data['models'])) {
                echo "<ul>";
                foreach ($data['models'] as $model) {
                    if (isset($model['name']) && strpos($model['name'], 'gemini') !== false) {
                        $supportedMethods = isset($model['supportedGenerationMethods']) ? implode(', ', $model['supportedGenerationMethods']) : 'N/A';
                        echo "<li><strong>" . $model['name'] . "</strong> - Methods: " . $supportedMethods . "</li>";
                    }
                }
                echo "</ul>";
            } else {
                echo "<p>No models found</p>";
            }
        } else {
            echo "<pre>Error: " . $responseV1Beta->body() . "</pre>";
        }

    } catch (\Exception $e) {
        echo "<h1>Error:</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
});



Route::get('/check-categories', function() {
    $categories = \App\Models\Category::withCount('products')->get();

    echo "<h1>Categories in Database:</h1><ul>";
    foreach ($categories as $cat) {
        echo "<li><strong>{$cat->name}</strong> - {$cat->products_count} products</li>";
    }
    echo "</ul>";
});
