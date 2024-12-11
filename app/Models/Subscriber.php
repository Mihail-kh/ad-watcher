<?php

declare(strict_types=1);

namespace App\Models;

use App\Mail\VerifySubscriberEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'email', 'api_token', 'api_token_expired_at', 'email_verified_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'api_token_expired_at' => 'datetime',
    ];

    /**
     * @return void
     */
    protected static function booted(): void
    {
        self::created(static function (Subscriber $subscriber): void {
            $subscriber->update([
                'api_token' => Str::random(60),
                'api_token_expired_at' => now()->addHours(2),
            ]);

            Mail::to($subscriber->email)->send(new VerifySubscriberEmail($subscriber));
        });
    }
}
