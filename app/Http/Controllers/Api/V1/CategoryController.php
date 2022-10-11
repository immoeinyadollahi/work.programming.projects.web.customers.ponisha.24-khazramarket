<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Category\CategoryCollection;
use App\Http\Resources\Api\V1\Category\CategoryResource;
use App\Http\Resources\Api\V1\Product\ProductCollection;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query("type", "productcat");

        $categories = Category::whereNull('category_id')
            ->where('type', $type)
            ->orderBy('ordering')
            ->get();

        return $this->respondWithResourceCollection(new CategoryCollection($categories));
    }
    public function show($category_id)
    {
        $category = Category::findOrFail($category_id);
        if (!$category->isPublished()) {
            abort(404);
        }

        return $this->respondWithResource(new CategoryResource($category));
    }
    public function products(Request $request, $category_id)
    {
        $category = Category::findOrFail($category_id);
        $per_page = $request->per_page ?: 20;
        $sort_field = $request->query("sort_field", "latest");
        $inventory_status = $request->query("inventory_status", "all");

        $ids = $category->allPublishedProducts()->pluck('id');
        $products = Product::orderByStock()->apiFilter($category->id)->whereIn('products.id', $ids);

        switch ($inventory_status) {
            case 'available': {
                    $products->available();
                    break;
                }
            case 'unavailable': {
                    $products->unavailable();
                    break;
                }
        }
        switch ($sort_field) {
            case 'latest': {
                    $products->latest();
                    break;
                }
            case 'sell': {
                    $products->orderBySale('desc');
                    break;
                }
            case 'view': {
                    $products->orderBy('view', 'desc');
                    break;
                }
            case 'cheapest': {
                    $products->orderByPrice('asc');
                    break;
                }
            case 'expensivest': {
                    $products->orderByPrice('desc');
                    break;
                }
        }

        $products = $products->paginate($per_page);
        return $this->respondWithResourceCollection(new ProductCollection($products));
    }
    public function filter($category_id)
    {
        $category = Category::findOrFail($category_id);
        //todo later changethis
        return $category->getFilter()->related;
    }
}
