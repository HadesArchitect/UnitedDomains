<?php

namespace HadesArchitect\UnitedDomains;

use HadesArchitect\UnitedDomains\Exception\ApiException;

interface BaseClientInterface
{
    /**
     * @param $method
     * @param array $properties
     * @throws ApiException
     * @return ResponseInterface
     */
    public function call($method, array $properties = []): ResponseInterface;
}