<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;

class ChatBotService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Send message to Gemini AI and get response
     */
    public function sendMessage(string $message, array $conversationHistory = []): string
    {
        // Check for database queries first
        $dbResponse = $this->checkDatabaseQuery($message);
        if ($dbResponse) {
            return $dbResponse;
        }

        // If no API key, return mock response
        if (!$this->apiKey || $this->apiKey === 'mock-key-for-testing') {
            return $this->getMockResponse($message);
        }

        try {
            // Get database context for AI
            $dbContext = $this->getDatabaseContext($message);

            // Build conversation context
            $fullPrompt = $this->buildPrompt($conversationHistory, $message, $dbContext);

            // Call Gemini API with SSL verification disabled
            $response = Http::withoutVerifying()
                ->timeout(60)
                ->post($this->apiUrl . '?key=' . $this->apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1024,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }

                Log::warning('Gemini response format unexpected: ' . json_encode($data));
                return 'Sorry, I could not generate a response.';
            }

            Log::error('Gemini API Error: ' . $response->body());
            return 'Sorry, I am experiencing technical difficulties. Please try again later.';

        } catch (\Exception $e) {
            Log::error('ChatBot Error: ' . $e->getMessage());
            return $this->getMockResponse($message);
        }
    }

/**
 * Check if message requires database query
 */
protected function checkDatabaseQuery(string $message): ?string
{
    $message = strtolower($message);

    // Budget + Category queries (MUST BE FIRST!)
    if (preg_match('/(under|below|less than|budget).*£?(\d+)/i', $message, $matches)) {
        $budget = isset($matches[2]) ? (int)$matches[2] : 1000;

        // Check if message also contains category keywords
        if (preg_match('/\b(laptops?|computers?|macbooks?)\b/', $message)) {
            return $this->getProductsByBudget($budget, $message);
        } elseif (preg_match('/\b(phones?|smartphones?|iphones?)\b/', $message)) {
            return $this->getProductsByBudget($budget, $message);
        } elseif (preg_match('/\b(tablets?|ipads?)\b/', $message)) {
            return $this->getProductsByBudget($budget, $message);
        } elseif (preg_match('/\b(headphones?|earbuds?|audio)\b/', $message)) {
            return $this->getProductsByBudget($budget, $message);
        } elseif (preg_match('/\b(gaming|playstations?|xboxes?)\b/', $message)) {
            return $this->getProductsByBudget($budget, $message);
        } elseif (preg_match('/\b(watch|watches|smartwatches?|wearables?)\b/', $message)) {
            return $this->getProductsByBudget($budget, $message);
        } elseif (preg_match('/\b(monitors?|keyboards?|mice|mouse|accessory|accessories)\b/', $message)) {
            return $this->getProductsByBudget($budget, $message);
        } else {
            // No category specified, show all products
            return $this->getProductsByBudget($budget, $message);
        }
    }

    // Direct category searches (show me X, find X, etc.)
    if (preg_match('/\b(laptops?|computers?|macbooks?|phones?|smartphones?|tablets?|headphones?|gaming|watches?|monitors?|accessories)\b/', $message)) {
        return $this->searchProducts($message);
    }

    // Check stock for specific product
    if (preg_match('/\b(stock|available|availability|in stock)\b/i', $message)) {
        return $this->checkStock($message);
    }

    // Price queries
    if (preg_match('/\b(price|cost|how much)\b/i', $message)) {
        return $this->getPriceInfo($message);
    }

    return null;
}

/**
 * Search products in database
 */
protected function searchProducts(string $message): string
{
    $message = strtolower($message);

    // Determine category - with PLURAL support
    $categoryName = null;
    $displayName = null;
    $emoji = '📱';

    if (preg_match('/\b(laptops?|computers?|macbooks?|dell|hp|lenovo)\b/', $message)) {
        $categoryName = 'Laptops & Computers';
        $displayName = 'Laptops';
        $emoji = '💻';
    } elseif (preg_match('/\b(tablets?|ipads?|tables)\b/', $message)) {
        $categoryName = 'Smartphones & Tablets';
        $displayName = 'Tablets';
        $emoji = '📱';
        $filterTablets = true;
    } elseif (preg_match('/\b(phones?|smartphones?|iphones?)\b/', $message)) {
        $categoryName = 'Smartphones & Tablets';
        $displayName = 'Smartphones';
        $emoji = '📱';
        $filterPhones = true;
    } elseif (preg_match('/\b(headphones?|earbuds?|audio|airpods?|sony|speakers?)\b/', $message)) {
        $categoryName = 'Audio Equipment';
        $displayName = 'Audio Products';
        $emoji = '🎧';
    } elseif (preg_match('/\b(gaming|playstations?|xboxes?|controllers?|games?)\b/', $message)) {
        $categoryName = 'Gaming & Accessories';
        $displayName = 'Gaming Consoles';
        $emoji = '🎮';
        $filterGaming = true;
    } elseif (preg_match('/\b(watch|watches|smartwatches?|apple watch|wearables?|smart home)\b/', $message)) {
        $categoryName = 'Smart Home & Wearables';
        $displayName = 'Wearables';
        $emoji = '⌚';
    } elseif (preg_match('/\b(monitors?|keyboards?|mice|mouse|webcams?|accessory|accessories)\b/', $message)) {
        $categoryName = 'Computer Accessories';
        $displayName = 'Accessories';
        $emoji = '⌨️';
    }

    $query = Product::with('category')->where('stock_quantity', '>', 0);

    if ($categoryName) {
        $category = Category::where('name', $categoryName)->first();
        if ($category) {
            $query->where('category_id', $category->id);
        }
    }

    // Apply specific filters
    if (isset($filterTablets)) {
        $query->where(function($q) {
            $q->where('name', 'LIKE', '%tablet%')
              ->orWhere('name', 'LIKE', '%iPad%')
              ->orWhere('name', 'LIKE', '%ipad%')
              ->orWhere('name', 'LIKE', '%IPAD%')
              ->orWhere('name', 'LIKE', '%Tab S%');
        });
    } elseif (isset($filterPhones)) {
        $query->where(function($q) {
            $q->where('name', 'LIKE', '%phone%')
              ->orWhere('name', 'LIKE', '%iPhone%')
              ->orWhere('name', 'LIKE', '%iphone%')
              ->orWhere('name', 'LIKE', '%Pixel%')
              ->orWhere('name', 'LIKE', '%Galaxy S%');
        });
    } elseif (isset($filterGaming)) {
        $query->where(function($q) {
            $q->where('name', 'LIKE', '%PlayStation%')
              ->orWhere('name', 'LIKE', '%Xbox%')
              ->orWhere('name', 'LIKE', '%Nintendo%')
              ->orWhere('name', 'LIKE', '%PS5%')
              ->orWhere('name', 'LIKE', '%Switch%');
        });
    }

    // Get products - show ALL for filtered categories
    $products = $query->orderBy('price', 'asc')->get();

    if ($products->isEmpty()) {
        $availableCategories = Category::withCount(['products' => function($q) {
            $q->where('stock_quantity', '>', 0);
        }])->get();

        $categoryList = $availableCategories->map(function($cat) {
            return "{$cat->name} ({$cat->products_count})";
        })->implode(', ');

        return "I couldn't find any " . ($displayName ?: 'products') . " matching your search. 😕\n\n📂 Available categories:\n{$categoryList}\n\nTry asking about a specific category!";
    }

    $categoryDisplay = $displayName ?: $categoryName ?: 'Products';
    $response = "{$emoji} **{$categoryDisplay} Available:**\n\n";

    foreach ($products as $product) {
        $stockStatus = $product->stock_quantity > 10 ? '✅ In Stock' : '⚠️ Low Stock (' . $product->stock_quantity . ' left)';
        $response .= "**{$product->name}**\n";
        $response .= "💰 £" . number_format($product->price, 2) . " | {$stockStatus}\n\n";
    }

    // Better result count message
    if ($products->count() == 1) {
        $response .= "Found 1 product. Need more options? Try a different category! 😊";
    } elseif ($products->count() <= 5) {
        $response .= "Showing all {$products->count()} results. Need help choosing? Just ask! 😊";
    } else {
        $response .= "Showing {$products->count()} results. Need help narrowing down? Just ask! 😊";
    }

    return $response;
}


    /**
     * Check stock availability
     */
    protected function checkStock(string $message): string
    {
        // Try to extract product name
        $products = Product::where('stock_quantity', '>', 0)->get();

        if ($products->isEmpty()) {
            return "We currently don't have any products in stock. Please check back later! 📦";
        }

        $response = "📊 **Current Stock Status:**\n\n";

        $inStock = $products->where('stock_quantity', '>', 10)->count();
        $lowStock = $products->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();

        $response .= "✅ In Stock: {$inStock} products\n";
        $response .= "⚠️ Low Stock: {$lowStock} products\n\n";
        $response .= "Want to see specific products? Ask me about laptops, phones, or any category!";

        return $response;
    }

    /**
     * Get price information
     */
    protected function getPriceInfo(string $message): string
    {
        $message = strtolower($message);

        $categoryName = null;
        if (preg_match('/\b(laptop|computer)\b/', $message)) {
            $categoryName = 'Laptops & Computers';
        } elseif (preg_match('/\b(phone|smartphone)\b/', $message)) {
            $categoryName = 'Smartphones & Tablets';
        } elseif (preg_match('/\b(tablet|ipad)\b/', $message)) {
            $categoryName = 'Smartphones & Tablets';
        } elseif (preg_match('/\b(audio|headphone)\b/', $message)) {
            $categoryName = 'Audio Equipment';
        } elseif (preg_match('/\b(gaming)\b/', $message)) {
            $categoryName = 'Gaming & Accessories';
        }

        if (!$categoryName) {
            return "I can help you find prices! 💰\n\nWhat are you interested in?\n• Laptops & Computers\n• Smartphones & Tablets\n• Audio Equipment\n• Gaming & Accessories\n• Smart Home & Wearables\n• Computer Accessories";
        }

        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return "Sorry, I couldn't find that category. 😕";
        }

        $products = Product::where('category_id', $category->id)
                           ->where('stock_quantity', '>', 0)
                           ->orderBy('price', 'asc')
                           ->take(5)
                           ->get();

        if ($products->isEmpty()) {
            return "Sorry, we don't have any {$categoryName} in stock right now. 😕";
        }

        $response = "💰 **{$categoryName} Prices:**\n\n";
        foreach ($products as $product) {
            $response .= "• {$product->name}: £" . number_format($product->price, 2) . "\n";
        }

        $minPrice = $products->min('price');
        $maxPrice = $products->max('price');
        $response .= "\n📊 Range: £" . number_format($minPrice, 2) . " - £" . number_format($maxPrice, 2);

        return $response;
    }

/**
 * Get products within budget
 */
protected function getProductsByBudget(int $budget, string $message): string
{
    $message = strtolower($message);

    // Determine category - match your actual DB category names (with PLURAL support!)
    $categoryName = null;
    if (preg_match('/\b(laptops?|computers?|macbooks?)\b/', $message)) {
        $categoryName = 'Laptops & Computers';
    } elseif (preg_match('/\b(phones?|smartphones?|iphones?)\b/', $message)) {
        $categoryName = 'Smartphones & Tablets';
    } elseif (preg_match('/\b(tablets?|ipads?)\b/', $message) && !preg_match('/\b(phone)\b/', $message)) {
        $categoryName = 'Smartphones & Tablets';
    } elseif (preg_match('/\b(headphones?|earbuds?|audio)\b/', $message)) {
        $categoryName = 'Audio Equipment';
    } elseif (preg_match('/\b(gaming|playstations?|xboxes?)\b/', $message)) {
        $categoryName = 'Gaming & Accessories';
    } elseif (preg_match('/\b(watch|watches|smartwatches?|wearables?)\b/', $message)) {
        $categoryName = 'Smart Home & Wearables';
    } elseif (preg_match('/\b(monitors?|keyboards?|mice|mouse|accessory|accessories)\b/', $message)) {
        $categoryName = 'Computer Accessories';
    }

    $query = Product::with('category')
                    ->where('price', '<=', $budget)
                    ->where('stock_quantity', '>', 0);

    if ($categoryName) {
        $category = Category::where('name', $categoryName)->first();

        if ($category) {
            $query->where('category_id', $category->id);
        }
    }

    $products = $query->orderBy('price', 'desc')->take(5)->get();

    if ($products->isEmpty()) {
        if ($categoryName) {
            return "I couldn't find any {$categoryName} under £{$budget}. 😕\n\nTry:\n• Increasing your budget\n• Asking 'show all products under £{$budget}'\n• Browse a different category";
        }
        return "I couldn't find any products under £{$budget}. 😕\n\nOur lowest priced item is £" . number_format(Product::min('price'), 2);
    }

    $categoryDisplay = $categoryName ?: 'Products';
    $response = "💰 **{$categoryDisplay} Under £{$budget}:**\n\n";

    foreach ($products as $product) {
        $stockIcon = $product->stock_quantity > 10 ? '✅' : '⚠️';
        $response .= "{$stockIcon} **{$product->name}**\n";
        $response .= "   💵 £" . number_format($product->price, 2) . "\n";
        $response .= "   📦 {$product->stock_quantity} in stock\n\n";
    }

    $response .= "Found {$products->count()} options in your budget! 😊";

    return $response;
}


    /**
     * Get database context for AI
     */
    protected function getDatabaseContext(string $message): string
    {
        $context = "\n\n**REAL-TIME DATABASE INFO:**\n";

        // Get total products
        $totalProducts = Product::count();
        $inStockProducts = Product::where('stock_quantity', '>', 0)->count();

        $context .= "- Total products in store: {$totalProducts}\n";
        $context .= "- Products in stock: {$inStockProducts}\n";

        // Get categories
        $categories = Category::withCount('products')->get();
        $context .= "- Categories: ";
        foreach ($categories as $cat) {
            $context .= "{$cat->name} ({$cat->products_count}), ";
        }
        $context = rtrim($context, ', ') . "\n";

        // Get price ranges
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');
        $context .= "- Price range: £" . number_format($minPrice, 2) . " - £" . number_format($maxPrice, 2) . "\n";

        return $context;
    }

    /**
     * Build prompt with conversation history
     */
    protected function buildPrompt(array $history, string $newMessage, string $dbContext = ''): string
    {
        $systemPrompt = $this->getSystemPrompt();
        $prompt = $systemPrompt . $dbContext . "\n\n";

        // Add conversation history
        foreach ($history as $msg) {
            $role = $msg['role'] === 'user' ? 'Customer' : 'Assistant';
            $prompt .= "{$role}: {$msg['content']}\n\n";
        }

        // Add new message
        $prompt .= "Customer: {$newMessage}\n\nAssistant:";

        return $prompt;
    }

    /**
     * Get system prompt for the chatbot
     */
    protected function getSystemPrompt(): string
    {
        return "You are a helpful customer support assistant for TechVerse, an online technology store.

Your role:
- Help customers find products FROM OUR DATABASE
- Answer questions about orders, shipping, and returns
- Provide product recommendations based on REAL PRICES and STOCK
- Answer FAQs about the store
- Be friendly, professional, and concise

Store Information:
- We sell laptops, smartphones, tablets, smartwatches, headphones, and cameras
- Free shipping on orders over £50
- 30-day return policy on all products
- Payment methods: Credit/Debit cards, PayPal
- Standard delivery takes 2-5 business days
- Express shipping available (1-2 days)
- Customer support email: support@techverse.com
- All products come with manufacturer warranty

IMPORTANT: Use the REAL-TIME DATABASE INFO provided to give accurate product prices and availability.

Be honest if you don't know something. Keep responses under 100 words. Use emojis sparingly (1-2 per message).

Now respond to the customer:";
    }

    /**
     * Get mock response when API key is not available
     */
    protected function getMockResponse(string $message): string
    {
        // First check database
        $dbResponse = $this->checkDatabaseQuery($message);
        if ($dbResponse) {
            return $dbResponse;
        }

        $message = strtolower($message);

        if (preg_match('/\b(hi|hello|hey|greetings)\b/', $message)) {
            return "Hello! 👋 Welcome to TechVerse! I'm your AI shopping assistant. How can I help you today?";
        }

        return "I'm here to help! 😊 Try asking me:\n• 'Show me laptops'\n• 'Products under £500'\n• 'What phones are in stock?'\n• 'Price of tablets'\n\nWhat would you like to know?";
    }
}
