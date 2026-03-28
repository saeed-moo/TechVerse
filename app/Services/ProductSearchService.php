<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchService
{
    /**
     * Search products with advanced filters
     */
    public function search(?string $query = null, array $filters = [])
    {
        return Product::query()
            ->with('category')

            // Text search
            ->when($query, function (Builder $q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })

            // Price range filter
            ->when(isset($filters['min_price']), function (Builder $q) use ($filters) {
                $q->where('price', '>=', $filters['min_price']);
            })
            ->when(isset($filters['max_price']), function (Builder $q) use ($filters) {
                $q->where('price', '<=', $filters['max_price']);
            })

            // Category filter
            ->when(isset($filters['category']), function (Builder $q) use ($filters) {
                $q->whereHas('category', function($query) use ($filters) {
                    $query->where('slug', $filters['category']);
                });
            })

            // Stock status filter
            ->when(isset($filters['in_stock']), function (Builder $q) use ($filters) {
                if ($filters['in_stock'] === '1') {
                    $q->where('stock_quantity', '>', 0);
                } elseif ($filters['in_stock'] === '0') {
                    $q->where('stock_quantity', '=', 0);
                }
            })

            // Brand filter
            ->when(isset($filters['brand']), function (Builder $q) use ($filters) {
                $q->where('name', 'like', $filters['brand'] . '%');
            })

            // Rating filter (minimum rating)
            ->when(isset($filters['rating']), function (Builder $q) use ($filters) {
                // For now, we'll simulate ratings
                // Later, when reviews are implemented, this will use actual review data
                // $q->whereHas('reviews', function($query) use ($filters) {
                //     $query->havingRaw('AVG(rating) >= ?', [$filters['rating']]);
                // });

                // Placeholder: Filter products with "rating-like" behavior
                // Remove this when actual reviews are implemented
            })

            // Sorting
            ->when(isset($filters['sort']), function (Builder $q) use ($filters) {
                switch ($filters['sort']) {
                    case 'price_asc':
                        $q->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $q->orderBy('price', 'desc');
                        break;
                    case 'name_asc':
                        $q->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $q->orderBy('name', 'desc');
                        break;
                    case 'newest':
                        $q->orderBy('created_at', 'desc');
                        break;
                    default:
                        $q->orderBy('created_at', 'desc');
                }
            }, function (Builder $q) {
                $q->orderBy('created_at', 'desc');
            })

            ->paginate(12);
    }

    /**
     * Get price range for all products
     */
    public function getPriceRange(): array
    {
        return [
            'min' => (int) Product::min('price') ?? 0,
            'max' => (int) Product::max('price') ?? 2000,
        ];
    }

    /**
     * Get available brands (extract from product names)
     */
    public function getAvailableBrands(): array
    {
        $products = Product::all();
        $brands = [];

        foreach ($products as $product) {
            $brand = explode(' ', $product->name)[0];
            if (!in_array($brand, $brands)) {
                $brands[] = $brand;
            }
        }

        sort($brands);
        return $brands;
    }
}
