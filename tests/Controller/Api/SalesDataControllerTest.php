<?php

namespace App\Tests\Controller\Api;

use App\Service\SettingsManager;
use App\Repository\SaleRepository;
use App\Repository\ReturnDataRepository;
use App\Repository\StockRepository;
use App\Tests\DataFixtures\TestFixtures;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class SalesDataControllerTest extends WebTestCase
{
    private $client;
    private $settingsManager;
    private $validSecret;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->settingsManager = static::getContainer()->get(SettingsManager::class);
        $this->validSecret = md5('myApiSecret01');

        // Purge database
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        // Load fixtures
        $fixtures = new TestFixtures();
        $fixtures->load($this->entityManager);
    }

    public function testSalesWithoutSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/sales');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            '{"error":"API secret is required"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testSalesWithInvalidSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/sales?secret=invalid_secret');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertJsonStringEqualsJsonString(
            '{"error":"Invalid API secret"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testSalesWithValidSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/sales?secret=' . $this->validSecret);
        
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('total', $response['pagination']);
        $this->assertArrayHasKey('per_page', $response['pagination']);
        $this->assertArrayHasKey('current_page', $response['pagination']);
        $this->assertArrayHasKey('last_page', $response['pagination']);

        // Check data structure
        $this->assertCount(10, $response['data']); // First page should have 10 items
        $firstItem = $response['data'][0];
        $this->assertArrayHasKey('taxId', $firstItem);
        $this->assertArrayHasKey('brand', $firstItem);
        $this->assertArrayHasKey('sku', $firstItem);
        $this->assertArrayHasKey('saleDate', $firstItem);
        $this->assertArrayHasKey('quantity', $firstItem);
    }

    public function testReturnsWithoutSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/returns');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            '{"error":"API secret is required"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testReturnsWithInvalidSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/returns?secret=invalid_secret');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertJsonStringEqualsJsonString(
            '{"error":"Invalid API secret"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testReturnsWithValidSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/returns?secret=' . $this->validSecret);
        
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('total', $response['pagination']);
        $this->assertArrayHasKey('per_page', $response['pagination']);
        $this->assertArrayHasKey('current_page', $response['pagination']);
        $this->assertArrayHasKey('last_page', $response['pagination']);

        // Check data structure
        $this->assertCount(10, $response['data']); // First page should have 10 items
        $firstItem = $response['data'][0];
        $this->assertArrayHasKey('taxId', $firstItem);
        $this->assertArrayHasKey('brand', $firstItem);
        $this->assertArrayHasKey('sku', $firstItem);
        $this->assertArrayHasKey('salesDate', $firstItem);
        $this->assertArrayHasKey('returnDate', $firstItem);
        $this->assertArrayHasKey('quantity', $firstItem);
    }

    public function testStocksWithoutSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/stocks');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            '{"error":"API secret is required"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testStocksWithInvalidSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/stocks?secret=invalid_secret');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertJsonStringEqualsJsonString(
            '{"error":"Invalid API secret"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testStocksWithValidSecret(): void
    {
        $this->client->request('GET', '/api/salesdata/stocks?secret=' . $this->validSecret);
        
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('total', $response['pagination']);
        $this->assertArrayHasKey('per_page', $response['pagination']);
        $this->assertArrayHasKey('current_page', $response['pagination']);
        $this->assertArrayHasKey('last_page', $response['pagination']);

        // Check data structure
        $this->assertCount(10, $response['data']); // First page should have 10 items
        $firstItem = $response['data'][0];
        $this->assertArrayHasKey('brand', $firstItem);
        $this->assertArrayHasKey('sku', $firstItem);
        $this->assertArrayHasKey('stockDate', $firstItem);
        $this->assertArrayHasKey('quantity', $firstItem);
    }

    public function testPagination(): void
    {
        $this->client->request('GET', '/api/salesdata/sales?secret=' . $this->validSecret . '&page=2');
        
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertEquals(2, $response['pagination']['current_page']);
        $this->assertEquals(10, $response['pagination']['per_page']);
        $this->assertEquals(15, $response['pagination']['total']);
        $this->assertEquals(2, $response['pagination']['last_page']);
        $this->assertCount(5, $response['data']); // Second page should have 5 items (15 total - 10 from first page)
    }
} 