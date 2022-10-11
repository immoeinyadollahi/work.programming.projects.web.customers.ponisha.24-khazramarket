<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Comment\CommentCollection;
use App\Http\Resources\Api\V1\Review\ReviewCollection;
use App\Http\Resources\Api\V1\Product\ProductCollection;
use App\Http\Resources\Api\V1\Product\ProductResource;
use Illuminate\Validation\Rule;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->per_page ?: 20;
        $sort_field = $request->query("sort_field", "latest");
        $inventory_status = $request->query("inventory_status", "all");

        $products = Product::with('category')
            ->published()
            ->orderByStock()
            ->apiFilter();

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

    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);
        if (!$product->isPublished()) {
            abort(404);
        }
        return $this->respondWithResource(new ProductResource($product));
    }

    public function comments(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $per_page = $request->per_page ?: 20;
        $comments = $product->comments()
            ->whereNull('comment_id')
            ->accepted()
            ->latest()
            ->paginate($per_page);
        return $this->respondWithResourceCollection(new CommentCollection($comments));
    }
    public function reviews(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $per_page = $request->per_page ?: 20;
        $reviews = $product->reviews()
            ->with('points')
            ->accepted()
            ->latest()
            ->paginate($per_page);
        return $this->respondWithResourceCollection(new ReviewCollection($reviews));
    }

    public function relatedProducts(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $per_page = $request->per_page ?: 20;

        $products = $product->relatedProducts()->with('category')
            ->published()
            ->orderByStock()
            ->latest()
            ->paginate($per_page);

        return $this->respondWithResourceCollection(new ProductCollection($products));
    }

    public function storeComment(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $request->validate([
            'body'       => 'required|string|max:1000',
            'comment_id' => [
                'nullable',
                Rule::exists('comments', 'id')->where(function ($query) {
                    $query->where('comment_id', null)->where('commentable_id', $this->product->id);
                }),
            ],
        ]);
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;

        if ($request->user()->isAdmin()) {
            $data['status'] = 'accepted';
        }

        $product->comments()->create($data);

        return $this->respondCreated(['message' => 'دیدگاه با موفقیت ثبت شد']);
    }
    public function storeReview(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);

        $data = $this->validate($request, [
            'title'       => 'required|string',
            'body'        => 'required|string|max:1000',
            'rating'      => 'required|between:1,5',
        ]);

        if ($request->user()->hasBoughtProduct($product)) {
            $request->validate([
                'suggest'     => 'required|in:yes,no,not_sure',
            ]);

            $data['suggest'] = $request->suggest;
        }

        $data['status'] = 'pending';

        $review = $product->reviews()->updateOrCreate(
            [
                'user_id' => auth()->user()->id
            ],
            $data
        );

        $review->points()->delete();

        $advantages = $request->input('review.advantages');

        if ($advantages) {
            foreach ($advantages as $advantage) {
                $review->points()->create([
                    'text' => $advantage,
                    'type' => 'positive',
                ]);
            }
        }

        $disadvantages = $request->input('review.disadvantages');

        if ($disadvantages) {
            foreach ($disadvantages as $disadvantage) {
                $review->points()->create([
                    'text' => $disadvantage,
                    'type' => 'negative',
                ]);
            }
        }
        return $this->respondCreated(['message' => 'دیدگاه با موفقیت ثبت شد']);
    }
}
