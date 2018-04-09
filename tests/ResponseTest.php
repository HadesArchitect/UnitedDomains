<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HadesArchitect\UnitedDomains\Response;

final class ResponseTest extends TestCase
{
    /**
     * @var \HadesArchitect\UnitedDomains\ResponseInterface
     */
    protected $response;

    public function setUp(): void
    {
        $this->response = new Response(200, 'test', ['a' => ['b'], 'c' => ['d', 'e']]);
    }

    public function testResponseCodeValid(): void
    {
        $this->assertEquals(
            200,
            $this->response->getCode()
        );
    }

    public function testResponseDescriptionValid(): void
    {
        $this->assertEquals(
            'test',
            $this->response->getDescription()
        );
    }

    public function testResponseHasProperty(): void
    {
        $this->assertEquals(
            true,
            $this->response->hasProperty('a')
        );
    }

    public function testResponseHasNotProperty(): void
    {
        $this->assertEquals(
            false,
            $this->response->hasProperty('z')
        );
    }

    public function testResponseGetProperty(): void
    {
        $this->assertEquals(
            ['b'],
            $this->response->getProperty('a')
        );
    }

    public function testResponseGetMissingProperty(): void
    {
        $this->assertEquals(
            null,
            $this->response->getProperty('z')
        );
    }

    public function testResponseGetMissingSingleProperty(): void
    {
        $this->assertEquals(
            null,
            $this->response->getSingleProperty('z')
        );
    }

    public function testResponseGetProperties(): void
    {
        $this->assertEquals(
            ['a' => ['b'], 'c' => ['d', 'e']],
            $this->response->getProperties()
        );
    }

    public function testResponseGetSingleProperty(): void
    {
        $this->assertEquals(
            'b',
            $this->response->getSingleProperty('a')
        );
    }

    public function testResponseCanCountProperties(): void
    {
        $this->assertEquals(
            2,
            $this->response->countProperty('c')
        );
    }

    public function testResponseCountZeroOnEmptyProperty(): void
    {
        $this->assertEquals(
            0,
            $this->response->countProperty('z')
        );
    }

    public function testResponseIsSuccessful(): void
    {
        $this->assertEquals(
            true,
            $this->response->isSuccessful()
        );
    }

    public function testResponseIsNotFailed(): void
    {
        $this->assertEquals(
            false,
            $this->response->isFailed()
        );
    }

    public function testResponseCanBeUsedAsString(): void
    {
        $this->assertEquals(
            '200: test',
            (string) $this->response
        );
    }
}

