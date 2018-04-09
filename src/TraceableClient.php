<?php declare(strict_types=1);

namespace HadesArchitect\UnitedDomains;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class TraceableClient extends Client
{
    use LoggerAwareTrait;

    /**
     * @var boolean
     */
    protected $debug = false;

    public function __construct($username, $password)
    {
        $this->logger = new NullLogger();

        parent::__construct($username, $password);
    }

    /**
     * @inheritdoc
     */
    protected function doCall(RequestInterface $request): string
    {
        $this->logger->debug('Executing API call: {uri}', ['uri' => $request->getUri()]);

        $response = $this->httpClient->send($request, ['debug' => $this->isDebugEnabled()]);

        $this->logger->debug('Received response', ['headers' => $response->getHeaders(), 'body' => $response->getBody()]);

        return (string) $response->getBody();
    }

    public function enableDebug(): void
    {
        $this->debug = true;
    }

    public function disableDebug(): void
    {
        $this->debug = false;
    }

    public function isDebugEnabled(): bool
    {
        return $this->debug;
    }
}
