<?php declare(strict_types=1);

namespace HadesArchitect\UnitedDomains;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use HadesArchitect\UnitedDomains\Exception\InvalidResponseFormatException;
use HadesArchitect\UnitedDomains\Exception\ServerException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Client
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var UriInterface
     */
    protected $uri;

    public function __construct($username, $password)
    {
        $this->uri = Uri::fromParts([
            'scheme' => 'https',
            'host' => 'api.domainreselling.de',
            'path' => '/api/call.cgi',
            'query' => sprintf('s_login=%s&s_pw=%s', $username, $password)
        ]);

        $this->httpClient = new HTTPClient();
    }

    public function call($method, array $properties = []): ResponseInterface
    {
        $query = sprintf(
            '%s&command=%s&%s',
            $this->uri->getQuery(),
            $method,
            http_build_query($properties)
        );

        $body = $this->doCall(new Request('get', $this->uri->withQuery($query)));

        $this->validateResponse($body);

        $response = $this->parseResponse($body);

        if ($response->getCode() > 299) {
            throw new ServerException($response);
        }

        return $response;
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    protected function doCall(RequestInterface $request): string
    {
        return (string) $this->httpClient->send($request)->getBody();
    }

    /**
     * @param string $body
     */
    protected function validateResponse(string $body): void
    {
        if (substr($body, 0, 10) != '[RESPONSE]' && substr($body, 0, 7) != 'code = ') {
            echo substr($body, 0, 7);
            throw new InvalidResponseFormatException('Response body begins neither with "[RESPONSE]" header nor code property');
        }


        if (substr($body, 0, 10) === '[RESPONSE]' && substr($body, -4, 3) != 'EOF') {
            throw new InvalidResponseFormatException('Response body does not ends with "EOF" line');
        }
    }

    /**
     * @param string $body
     * @return ResponseInterface
     */
    protected function parseResponse(string $body): ResponseInterface
    {
        $code = 0;
        $description = '';
        $properties = [];


        foreach (explode(PHP_EOL, (string) $body) as $line) {
            if (empty($line) || '[RESPONSE]' === $line || 'EOF' === $line) {
                continue;
            }

            switch (substr($line, 0, 4)) {
                case 'code':
                    $code = (int)substr($line, 7);
                    break;
                case 'desc':
                    $description = substr($line, 14);
                    break;
                case 'prop':
                    preg_match_all("/property\[(.*)\]\[(\d+)\] = (.*)/", $line, $matches);
                    $properties[$matches[1][0]][$matches[2][0]] = $matches[3][0];
                    break;
            }
        }

        return new Response($code, $description, $properties);
    }
}
