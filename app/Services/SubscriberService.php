<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\ResendEmailVerificationRequest;
use App\Mail\VerifySubscriberEmail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubscriberService
{
    /**
     * @param string $token
     * @return void
     */
    public function verifyEmail(string $token): void
    {
        $subscriber = Subscriber::where('api_token', $token)->first();

        if ($subscriber && !$subscriber->api_token_expired_at->isPast()) {
            $subscriber->update([
                'email_verified_at' => now()
            ]);
        }
    }

    /**
     * @param ResendEmailVerificationRequest $request
     * @return void
     */
    public function resendEmailVerification(ResendEmailVerificationRequest $request): void
    {
        $subscriber = Subscriber::where('email', $request->safe()->email)->first();

        $subscriber->update([
            'api_token' => Str::random(60),
            'api_token_expired_at' => now()->addHours(2),
        ]);

        Mail::to($subscriber->email)->send(new VerifySubscriberEmail($subscriber));
    }
}
