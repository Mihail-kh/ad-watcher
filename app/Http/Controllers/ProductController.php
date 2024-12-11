<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeToProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * @param ProductService $productService
     */
    public function __construct(
        private readonly ProductService $productService,
    ) {
        //
    }

    /**
     * @return AnonymousResourceCollection
     *
     * @deprecated Get list of products
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Product::all();

        return ProductResource::collection($products);
    }

    /**
     * @param SubscribeToProductRequest $request
     * @return mixed
     * @deprecated Subscribe to the product
     */
    public function subscribe(SubscribeToProductRequest $request): mixed
    {
        return $this->productService->subscribe($request);
    }

    /**
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return mixed*
     * @deprecated Update product
     */
    public function update(UpdateProductRequest $request, Product $product): mixed
    {
        $this->productService->update($request, $product);

        return response()->json(['success' => 'Product updated successfully.'], 200);
    }
}
