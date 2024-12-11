<?php

use App\Console\Commands\CheckProductPrice;
use App\Enums\ProductStatuses;
use App\Jobs\CheckProductPriceJob;
use App\Models\Product;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('products list is available', function () {
    $response = $this->get('/api/products');

    $response->assertStatus(200);
});

test('product validation works', function () {
    $product = Product::factory()->create();

    $response = $this->put("/api/products/{$product->id}", [
        'name' => 111,
        'external_id' => 'test',
        'price' => 'test',
        'latest_price_checked_at' => '08:08:08 11-11-2011',
    ]);

    $response->assertStatus(302);
    $response->assertInvalid(['name', 'external_id', 'price', 'latest_price_checked_at']);
});

test('processes products with active status and verified subscribers', function () {
    Carbon::setTestNow('2024-01-01 12:00:00');
    $verifiedUser = Subscriber::factory()->create(['email_verified_at' => now()]);
    $unverifiedUser = Subscriber::factory()->create(['email_verified_at' => null]);

    $firstProduct = Product::factory()->create([
        'status' => ProductStatuses::Active->value,
        'latest_price_checked_at' => Carbon::now()->subMinutes(10),
    ]);
    $firstProduct->subscribers()->syncWithoutDetaching($verifiedUser);

    $secondProduct = Product::factory()->create([
        'status' => ProductStatuses::Active->value,
        'latest_price_checked_at' => Carbon::now()->subMinutes(20),
    ]);
    $secondProduct->subscribers()->syncWithoutDetaching($verifiedUser);

    $thirdProduct = Product::factory()->create([
        'status' => ProductStatuses::Inactive->value,
        'latest_price_checked_at' => Carbon::now()->subMinutes(20),
    ]);
    $thirdProduct->subscribers()->syncWithoutDetaching($verifiedUser);

    expect(Product::getActivelyTracked(CheckProductPrice::CHECK_INTERVAL)->count())->toBe(1);

    Queue::fake();

    $command = new CheckProductPrice();
    $command->handle();

    // Проверяем обновление
    $this->assertEquals(Carbon::now(), $secondProduct->refresh()->latest_price_checked_at);

    Queue::assertPushed(CheckProductPriceJob::class, function ($job) use ($secondProduct) {
        return $job->product->is($secondProduct);
    });

    Queue::assertNotPushed(CheckProductPriceJob::class, function ($job) use ($firstProduct, $thirdProduct) {
        return $job->product->is($firstProduct) || $job->product->is($thirdProduct);
    });
});





