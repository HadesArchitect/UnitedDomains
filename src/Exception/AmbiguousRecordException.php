<?php declare(strict_types=1);

namespace HadesArchitect\UnitedDomains\Exception;

use Throwable;

class AmbiguousRecordException extends ApiException
{
    /**
     * @var array
     */
    protected $records = [];

    public function __construct(array $records, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->records = $records;

        parent::__construct($message, $code, $previous);
    }

    public function getRecords(): array
    {
        return $this->records;
    }
}
