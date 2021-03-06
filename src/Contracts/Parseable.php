<?php

namespace Helldar\ApiResponse\Contracts;

/** @mixin \Helldar\Support\Concerns\Makeable */
interface Parseable
{
    public function setData($data): self;

    public function setWith(array $with = []): self;

    public function setStatusCode(int $code = null): self;

    public function getStatusCode(): int;

    public function getData();

    public function getWith(): array;

    public function getType(): ?string;

    public function isError(): bool;
}
