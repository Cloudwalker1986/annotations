<?php
declare(strict_types=1);

namespace Request\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Route
{
    public const HTTP_METHOD_GET = 'GET';
    public const HTTP_METHOD_POST = 'POST';
    public const HTTP_METHOD_DELETE = 'DELETE';
    public const HTTP_METHOD_PATCH = 'PATCH';
    public const HTTP_METHOD_PUT = 'PUT';

    public function __construct(
        private string $path,
        private ?string $method = self::HTTP_METHOD_GET
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }
}
