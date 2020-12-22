<?php

namespace Helldar\ApiResponse\Services;

use Exception as BaseException;
use Helldar\ApiResponse\Support\Exception;
use Helldar\ApiResponse\Support\Response as ResponseSupport;
use Helldar\Support\Facades\Arr;
use Helldar\Support\Facades\Instance;
use Helldar\Support\Facades\Str;
use Helldar\Support\Traits\Makeable;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method static Response make(...$parameters)
 * @method static Response init(...$parameters)
 */
final class Response
{
    use Makeable;

    /** @var array */
    protected $with = [];

    /** @var array|int|object|string|null */
    protected $data = null;

    /** @var bool */
    protected $use_data = true;

    /** @var array */
    protected $headers = [];

    /** @var int */
    protected $status_code = 200;

    /** @var string|null */
    protected $status_type;

    public function exception(string $status_type = null): self
    {
        $this->status_type = $status_type;

        return $this;
    }

    /**
     * @param  mixed  $data
     * @param  int  $status_code
     * @param  bool  $use_data
     *
     * @throws \ReflectionException
     *
     * @return $this
     */
    public function data($data = null, int $status_code = 200, bool $use_data = true): self
    {
        $this->use_data    = $use_data;
        $this->status_code = $status_code;

        if (Exception::isError($data)) {
            $this->status_code = Exception::getCode($data, $status_code);
            $this->status_type = Exception::getType($data, $this->status_type);
            $this->data        = Exception::getData($data);
        } else {
            $this->data = ResponseSupport::get($data);
        }

        return $this;
    }

    /**
     * @param  array  $with
     *
     * @return $this
     */
    public function with(array $with = []): self
    {
        $this->with = array_merge($this->with, $with);

        return $this;
    }

    /**
     * @param  array  $headers
     *
     * @return $this
     */
    public function headers(array $headers = []): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function response(): JsonResponse
    {
        return new JsonResponse($this->getData(), $this->status_code, $this->headers);
    }

    protected function isError(): bool
    {
        return Exception::isErrorCode($this->status_code);
    }

    protected function e($value = null, $doubleEncode = true)
    {
        if (is_null($value) || ! is_string($value)) {
            return $value;
        }

        return ! empty($value) ? Str::e($value, $doubleEncode) : null;
    }

    protected function getStatusType(): string
    {
        return Instance::basename(
            $this->status_type ?: BaseException::class
        );
    }

    protected function getData()
    {
        $this->splitData();

        $data = $this->isError()
            ? $this->getErrorData()
            : $this->getSuccessData();

        return $this->mergeData($data);
    }

    protected function getErrorData(): array
    {
        return [
            'error' => [
                'type' => $this->getStatusType(),
                'data' => $this->e($this->data),
            ],
        ];
    }

    protected function getSuccessData()
    {
        $data = $this->e($this->data);

        if ($this->use_data || $this->with) {
            return isset($data['data']) ? $data : compact('data');
        }

        return $data;
    }

    protected function mergeData($data = [])
    {
        return is_array($data)
            ? array_merge($data, $this->with)
            : $data;
    }

    protected function splitData(): void
    {
        if (! is_array($this->data) && ! is_object($this->data)) {
            return;
        }

        $data = Arr::toArray($this->data);

        if (Arr::exists($data, 'data')) {
            $this->data(Arr::get($data, 'data'), $this->status_code);
            $this->with(Arr::except($data, 'data'));
        }
    }
}
