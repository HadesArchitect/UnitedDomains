<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HadesArchitect\UnitedDomains\Response;

final class TraceableClientTest extends TestCase
{
    /**
     * @var \HadesArchitect\UnitedDomains\TraceableClient
     */
    protected $client;

    public function setUp(): void
    {
        $this->client = new \HadesArchitect\UnitedDomains\TraceableClient('user', 'password');
    }

    public function testClientDebugMode(): void
    {
        $this->assertEquals(false, $this->client->isDebugEnabled());
        $this->client->enableDebug();
        $this->assertEquals(true, $this->client->isDebugEnabled());
        $this->client->disableDebug();
        $this->assertEquals(false, $this->client->isDebugEnabled());
    }
}
