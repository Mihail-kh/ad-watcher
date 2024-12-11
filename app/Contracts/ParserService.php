<?php

namespace App\Contracts;

interface ParserService
{
    public function getProductHtmlPage(string $productUrl);

    public function getExternalIdFromProductHtmlPage(string $response): int;

    public function sendRequestToApi(int $externalId);

    public function getProcessedProductDataFromApi(array $response): array;

    public function extractPriceFromResponse(array $rawData): mixed;

    public function extractTitleFromResponse(array $rawData): mixed;
}
