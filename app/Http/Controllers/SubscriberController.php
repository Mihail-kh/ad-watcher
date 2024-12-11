<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ResendEmailVerificationRequest;
use App\Services\SubscriberService;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    /**
     * @param SubscriberService $subscriberService
     */
    public function __construct(
        private readonly SubscriberService $subscriberService,
    ) {
        //
    }

    /**
     * @param string $token
     * @return JsonResponse
     * @deprecated Verify subscriber email
     */
    public function verifyEmail(string $token): JsonResponse
    {
        $this->subscriberService->verifyEmail($token);

        return response()->json(['success' => 'Your email has been verified'], 200);
    }

    /**
     * @param ResendEmailVerificationRequest $request
     * @return JsonResponse
     * @deprecated Resend verification email
     */
    public function resendEmailVerification(ResendEmailVerificationRequest $request): JsonResponse
    {
        $this->subscriberService->resendEmailVerification($request);

        return response()->json(['success' => 'Check your email'], 200);
    }
}
