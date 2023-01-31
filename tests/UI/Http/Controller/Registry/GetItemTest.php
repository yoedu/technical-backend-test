<?php

namespace App\Tests\UI\Http\Controller\Registry;

use App\TechTest\Registry\Application\Query\GetItemQuery;
use App\TechTest\Registry\Application\Query\GetItemQueryHandler;
use App\TechTest\Registry\Application\Service\InvertedGetItemService;
use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Registry\Domain\Item;
use App\TechTest\Registry\Domain\ItemValue;
use App\TechTest\Registry\Infrastructure\InSessionInvertService;
use App\TechTest\Registry\Infrastructure\InSessionItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class GetItemTest extends WebTestCase
{
    public function testNormalAndInvertedResponses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/items/blue');
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('Ko',$responseData['status']);

        $body = $this->prepareJsonPostBody('blue');
        $client->request('POST', '/items',[],[],['CONTENT_TYPE' => 'application/json'],$body);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $client->request('GET', '/items/blue');
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('Ok',$responseData['status']);

        $client->request('GET', '/items/green');
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('Ko',$responseData['status']);

        $client->request('POST', '/items/invert/toggle');
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request('GET', '/items/green');
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('Ok',$responseData['status']);

        $client->request('GET', '/items/blue');
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('Ko',$responseData['status']);
    }
    private function prepareJsonPostBody($value): string{
        return json_encode(['value' => $value], JSON_THROW_ON_ERROR);
    }
}

