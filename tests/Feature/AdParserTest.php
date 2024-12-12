<?php

use App\Services\AdParserService;

beforeEach(function () {
    $this->addParserService = new AdParserService();
});

test('getProductHtmlPage returns 404 for non existent ad', function () {
    $productUrl = 'https://www.olx.ua/d/uk/obyavlenie/test_ad';
    $response = $this->addParserService->getProductHtmlPage($productUrl);

    $this->assertIsArray($response);
    $this->assertArrayHasKey('error', $response);
    $this->assertArrayHasKey('reason', $response['error']);
    $this->assertArrayHasKey('status', $response['error']);
    $this->assertSame(404, $response['error']['status']);
    $this->assertSame('Сторінку не знайдено', $response['error']['reason']);
});

test('getProductFromExternalApi returns 404 for wrong external ad id', function () {
    $externalId = 11111;
    $response = $this->addParserService->getProductFromExternalApi($externalId);

    $this->assertIsArray($response);
    $this->assertArrayHasKey('error', $response);
    $this->assertArrayHasKey('reason', $response['error']);
    $this->assertArrayHasKey('status', $response['error']);
    $this->assertSame(404, $response['error']['status']);
    $this->assertSame('Ad not found.', $response['error']['reason']);
});



