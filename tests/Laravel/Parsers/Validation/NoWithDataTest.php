<?php

namespace Tests\Laravel\Parsers\Validation;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tests\Fixtures\Concerns\Validationable;
use Tests\Laravel\TestCase;

final class NoWithDataTest extends TestCase
{
    use Validationable;

    protected $allow_with = false;

    public function testResponse()
    {
        $this->assertTrue($this->validationResponse([])->instance() instanceof JsonResponse);
    }

    public function testJson()
    {
        $this->assertJson($this->validationResponse([])->getRaw());
    }

    public function testStructure()
    {
        $this->assertSame(['data' => ['foo' => 'Foo', 'bar' => 123]], $this->validationResponse(['foo' => 'Foo', 'bar' => 123])->getJson());
        $this->assertSame(['data' => ['foo' => 456, 'bar' => 123]], $this->validationResponse(['foo' => 456, 'bar' => 123])->getJson());

        $this->assertSame(
            ['data' => ['foo' => 'Foo', 'bar' => 123, 'baz' => 'http://foo.bar']],
            $this->validationResponse(['foo' => 'Foo', 'bar' => 123, 'baz' => 'http://foo.bar'])->getJson()
        );

        $this->assertSame(
            ['data' => ['foo' => 456, 'bar' => 123, 'baz' => 'http://foo.bar']],
            $this->validationResponse(['foo' => 456, 'bar' => 123, 'baz' => 'http://foo.bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'ValidationException', 'data' => ['foo' => ['The foo field is required.']]]],
            $this->validationResponse([])->getJson()
        );

        $this->assertSame(
            [
                'error' => [
                    'type' => 'ValidationException',
                    'data' => [
                        'foo' => ['The foo field is required.'],
                        'baz' => ['The baz format is invalid.'],
                    ],
                ],
            ],
            $this->validationResponse(['baz' => 0])->getJson()
        );

        $this->assertSame(
            [
                'error' => [
                    'type' => 'ValidationException',
                    'data' => [
                        'foo' => ['The foo field is required.'],
                        'bar' => ['The bar must be an integer.'],
                        'baz' => ['The baz format is invalid.'],
                    ],
                ],
            ],
            $this->validationResponse(['bar' => 'Bar', 'baz' => 0])->getJson()
        );
    }

    public function testStatusCode()
    {
        $this->assertSame(200, $this->validationResponse(['foo' => 'Foo', 'bar' => 123])->getStatusCode());
        $this->assertSame(200, $this->validationResponse(['foo' => 456, 'bar' => 123])->getStatusCode());
        $this->assertSame(422, $this->validationResponse([])->getStatusCode());

        $this->assertSame(202, $this->validationResponse(['foo' => 'Foo', 'bar' => 123], 202)->getStatusCode());
        $this->assertSame(203, $this->validationResponse(['foo' => 456, 'bar' => 123], 203)->getStatusCode());
        $this->assertSame(401, $this->validationResponse([], 401)->getStatusCode());
    }
}
