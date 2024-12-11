<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ProductStatuses;
use App\Models\Product;
use Illuminate\Console\Command;
use App\Jobs\CheckProductPriceJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CheckProductPrice extends Command
{
    const CHECK_INTERVAL = 15;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-product-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check product price';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Product::getActivelyTracked(self::CHECK_INTERVAL)
            ->each(function (Product $product) {
                $product->update(['latest_price_checked_at' => now()]);
                CheckProductPriceJob::dispatch($product);
            });
    }
}
