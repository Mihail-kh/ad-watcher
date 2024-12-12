<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\SubscribeToProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Subscriber;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * @param AdParserService $parserService
     */
    public function __construct(
        private readonly AdParserService $parserService
    ) {
        //
    }

    /**
     * @param SubscribeToProductRequest $request
     * @return JsonResponse|mixed
     */
    public function subscribe(SubscribeToProductRequest $request)
    {
        $data = $request->validated();
        $response = $this->parserService->getProductHtmlPage($data['product_url']);

        if (is_array($response) && isset($response['error'])) {
            return response()->json(['error' => $response['error']['reason']], $response['error']['status']);
        }

        $productExternalId = $this->parserService->getExternalIdFromProductHtmlPage($response);

        return DB::transaction(function () use ($data, $productExternalId) {
            $product = Product::where('external_id', $productExternalId)->first();

            if (!$product) {
                $responseFromApi = $this->parserService->getProductFromExternalApi($productExternalId);

                if (is_array($responseFromApi) && isset($responseFromApi['error'])) {
                    return response()->json(['error' => $responseFromApi['error']['reason']], $responseFromApi['error']['status']);
                }

                $productData = $this->parserService->getProcessedProductDataFromApi($responseFromApi);

                $product = Product::create([
                    'name' => $productData['title'],
                    'price' => $productData['price'],
                    'external_id' => $productExternalId,
                    'latest_price_checked_at' => now(),
                ]);

            }
            $product->load('subscribers');

            $subscriber = Subscriber::firstOrCreate([
                'email' => $data['email'],
            ]);

            if ($product->subscribers->contains($subscriber->id)) {
                return response()->json(['error' => 'You are already subscribed to updates for this product.'], 422);
            }

            $product->subscribers()->syncWithoutDetaching($subscriber);

            return response()->json(['success' => 'Don\'t forget to verify your email to receive notifications about product price changes.'], 200);
        });
    }

    /**
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return void
     */
    public function update(UpdateProductRequest $request, Product $product): void
    {
        $product->update($request->validated());
    }
}
