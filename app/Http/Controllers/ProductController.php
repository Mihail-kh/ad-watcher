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
     * @deprecated Get list of products
     * @return AnonymousResourceCollection
     *
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Product::all();

        return ProductResource::collection($products);
    }

    /**
     * @deprecated Subscribe to the product
     * @param SubscribeToProductRequest $request
     * @return mixed
     */
    public function subscribe(SubscribeToProductRequest $request): mixed
    {
        return $this->productService->subscribe($request);
    }

    /**
     *
     * @deprecated Update product
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return mixed*
     */
    public function update(UpdateProductRequest $request, Product $product): mixed
    {
        $this->productService->update($request, $product);

        return response()->json(['success' => 'Product updated successfully.'], 200);
    }
}
