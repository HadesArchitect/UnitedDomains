<?php

namespace HadesArchitect\UnitedDomains\Exception;

class InvalidResponseFormatException extends ApiException
{
    protected $message = 'The response has invalid format';
}