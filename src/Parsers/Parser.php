<?php

namespace Helldar\ApiResponse\Parsers;

use Exception as BaseException;
use Helldar\ApiResponse\Concerns\Errors;
use Helldar\ApiResponse\Contracts\Parseable;
use Helldar\ApiResponse\Services\Response;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Instance;
use Helldar\Support\Facades\Helpers\Is;

abstract class Parser implements Parseable
{
    use Makeable;
    use Errors;

    /** @var mixed */
    protected $data;

    /** @var array */
    protected $with = [];

    /** @var int|null */
    protected $status_code;

    public function setData($data): Parseable
    {
        $this->data = $data;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->isError(true)
            ? ($this->status_code ?: 400)
            : ($this->status_code ?: 200);
    }

    public function setStatusCode(int $code = null): Parseable
    {
        $this->status_code = $code;

        return $this;
    }

    public function getType(): ?string
    {
        if ($this->isError()) {
            return Is::error($this->data) ? Instance::basename($this->data) : BaseException::class;
        }

        return null;
    }

    public function getWith(): array
    {
        return $this->with;
    }

    public function setWith(array $with = []): Parseable
    {
        if ($this->allowWith()) {
            $this->with = array_merge($this->with, $with);
        }

        return $this;
    }

    protected function allowWith(): bool
    {
        return Response::$allow_with;
    }
}
