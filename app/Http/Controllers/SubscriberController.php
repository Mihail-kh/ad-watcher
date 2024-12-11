<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ResendEmailVerificationRequest;
use App\Services\SubscriberService;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    public function __construct(
        private readonly SubscriberService $subscriberService,
    ) {
        //
    }

    /**
     * @deprecated Verify subscriber email
     * @param string $token
     * @return JsonResponse
     */
    public function verifyEmail(string $token): \Illuminate\Http\JsonResponse
    {
        $this->subscriberService->verifyEmail($token);

        return response()->json(['success' => 'Your email has been verified'], 200);
    }

    /**
     * @deprecated Resend verification email
     * @param ResendEmailVerificationRequest $request
     * @return JsonResponse
     */
    public function resendEmailVerification(ResendEmailVerificationRequest $request): JsonResponse
    {
        $this->subscriberService->resendEmailVerification($request);

        return response()->json(['success' => 'Check your email'], 200);
    }
}
