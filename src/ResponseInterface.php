<?php declare(strict_types=1);

namespace HadesArchitect\UnitedDomains;

interface ResponseInterface
{
    function getCode(): int;
    function getDescription(): string;
    function isSuccessful(): bool;
    function isFailed(): bool;
    function hasProperty(string $name): bool;
    function countProperty(string $name): int;
    function getProperty(string $name): ?array;
    function getSingleProperty(string $name);
    function getProperties(): array;
    function __toString(): string;
}
