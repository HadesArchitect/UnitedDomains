<?php

namespace HadesArchitect\UnitedDomains\Exception;

use HadesArchitect\UnitedDomains\ResponseInterface;
use Throwable;

class ServerException extends ApiException
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->response = $response;
        parent::__construct($response->getDescription(), $code, $previous);
    }

    public function getResponse()
    {
        return $this->response;
    }
}