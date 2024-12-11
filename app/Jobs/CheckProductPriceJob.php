<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\UpdatedProductPrice;
use App\Models\Product;
use App\Services\AdParserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckProductPriceJob implements ShouldQueue
{
    use Queueable;

    private AdParserService $parserService;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Product $product,
    ) {
        $this->parserService = app(AdParserService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // actualPrice has type 'string'
        $actualPrice = $this->parserService->extractPriceFromResponse(
            $this->parserService->sendRequestToApi($this->product->external_id)
        );

        if ($actualPrice !== $this->product->price) {
            $oldPrice = $this->product->price;
            Log::info('Product 2 has changed price: from ' . $this->product->price . ' to ' . $actualPrice);
            $this->product->update(['price' => $actualPrice]);

            foreach ($this->product->subscribers as $subscriber) {
                Mail::to($subscriber->email)
                    ->send(
                        new UpdatedProductPrice($this->product, $actualPrice, $oldPrice)
                    );
            }
        }
    }
}
