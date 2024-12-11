<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductStatuses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'external_id', 'price', 'latest_price_checked_at', 'status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'price' => 'float',
        'status' => ProductStatuses::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscriber::class);
    }

    /**
     * @param Builder $query
     * @param int $checkInterval
     * @return Builder
     */
    public function scopeGetActivelyTracked(Builder $query, int $checkInterval): Builder
    {
        return $query->where('status', ProductStatuses::Active->value)
            ->where('latest_price_checked_at', '<=', Carbon::now()->subMinutes($checkInterval))
            ->whereHas('subscribers', function ($query) {
                $query->whereNotNull('email_verified_at');
            });
    }
}
