<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ParserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdParserService implements ParserService
{
    const BASE_URL = 'https://www.olx.ua/api/v2/';

    /**
     * @param string $productUrl
     * @return array|array[]|string|void
     */
    public function getProductHtmlPage(string $productUrl)
    {
        try {
            $response = Http::get($productUrl);

            if ($response->getStatusCode() !== 200) {
                return $this->handleErrors(
                    $this->extractReasonForTheErrorFromHtmlPage($response->getBody()->getContents()),
                    $response->getStatusCode()
                );
            }

            return $response->getBody()->getContents();
        } catch (\Exception $exception) {
            Log::error('Error occurred while receiving product HTML page: ' . $exception->getMessage());
        }
    }

    /**
     * @param string $reason
     * @param int $statusCode
     * @return array[]
     */
    private function handleErrors(string $reason, int $statusCode): array {
        return [
            'error' => [
                'reason' => $reason,
                'status' => $statusCode,
            ]
        ];
    }

    /**
     * @param string $htmlPage
     * @return string
     */
    private function extractReasonForTheErrorFromHtmlPage(string $htmlPage): string
    {
        $reason = Str::match('/<h3 class="c-container__title">(.*?)<\/h3>/', $htmlPage);

        return strlen($reason) ? $reason : 'Undefined error';
    }

    /**
     * @param string $response
     * @return int
     */
    public function getExternalIdFromProductHtmlPage(string $response): int
    {
        return (int) Str::match('/ad-id=(\d+)/', $response);
    }

    /**
     * @param int $externalId
     * @return mixed|void
     */
    public function sendRequestToApi(int $externalId)
    {
        try {
            $response = Http::get(self::BASE_URL . 'offers/' . $externalId);

            if (isset($response['error']) && count($response['error'])) {
                return $this->handleErrors($response['error']['detail'], $response['error']['status']);
            }

            return $response['data'];
        } catch (\Exception $exception) {
            Log::error('Error occurred while retrieving product data from the API: ' . $exception->getMessage());
        }
    }

    /**
     * @param array $response
     * @return array
     */
    public function getProcessedProductDataFromApi(array $response): array
    {
        return [
            'price' => $this->extractPriceFromResponse($response),
            'title' => $this->extractTitleFromResponse($response),
        ];
    }

    /**
     * @param array $rawData
     * @return float|null
     */
    public function extractPriceFromResponse(array $rawData): ?float
    {
        $parameterItem = Arr::first($rawData['params'], function ($item) {
            return $item['key'] === 'price' && $item['type'] === 'price';
        });

        $price = Arr::get($parameterItem, 'value.value');

        return !is_null($price) ? (float) $price : null;
    }

    /**
     * @param array $rawData
     * @return array|\ArrayAccess|mixed
     */
    public function extractTitleFromResponse(array $rawData): mixed
    {
        return Arr::get($rawData, 'title');
    }

}
