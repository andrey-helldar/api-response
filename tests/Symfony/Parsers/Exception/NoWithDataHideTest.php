<?php

namespace Tests\Symfony\Parsers\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tests\Fixtures\Exceptions\FooException;
use Tests\Fixtures\Exceptions\FooHttpException;
use Tests\Symfony\TestCase;

final class NoWithDataHideTest extends TestCase
{
    protected $with = false;

    public function testInstance()
    {
        $this->assertTrue($this->response(null, 0)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response('foo', 0)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response([], 0)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(0, 0)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooException('Foo'), 0)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooHttpException('Foo'), 0)->instance() instanceof JsonResponse);

        $this->assertTrue($this->response(null, 400)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response('foo', 400)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response([], 400)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(0, 400)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooException('Foo'), 400)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooHttpException('Foo'), 400)->instance() instanceof JsonResponse);

        $this->assertTrue($this->response(null, 404)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response('foo', 404)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response([], 404)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(0, 404)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooException('Foo'), 404)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooHttpException('Foo'), 404)->instance() instanceof JsonResponse);

        $this->assertTrue($this->response(null, 500)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response('foo', 500)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response([], 500)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(0, 500)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooException('Foo'), 500)->instance() instanceof JsonResponse);
        $this->assertTrue($this->response(new FooHttpException('Foo'), 500)->instance() instanceof JsonResponse);
    }

    public function testType()
    {
        $this->assertJson($this->response(null, 0)->getRaw());
        $this->assertJson($this->response('foo', 0)->getRaw());
        $this->assertJson($this->response([], 0)->getRaw());
        $this->assertJson($this->response(0, 0)->getRaw());
        $this->assertJson($this->response(new FooException('Foo'), 0)->getRaw());
        $this->assertJson($this->response(new FooHttpException('Foo'), 0)->getRaw());

        $this->assertJson($this->response(null, 400)->getRaw());
        $this->assertJson($this->response('foo', 400)->getRaw());
        $this->assertJson($this->response([], 400)->getRaw());
        $this->assertJson($this->response(0, 400)->getRaw());
        $this->assertJson($this->response(new FooException('Foo'), 400)->getRaw());
        $this->assertJson($this->response(new FooHttpException('Foo'), 400)->getRaw());

        $this->assertJson($this->response(null, 404)->getRaw());
        $this->assertJson($this->response('foo', 404)->getRaw());
        $this->assertJson($this->response([], 404)->getRaw());
        $this->assertJson($this->response(0, 404)->getRaw());
        $this->assertJson($this->response(new FooException('Foo'), 404)->getRaw());
        $this->assertJson($this->response(new FooHttpException('Foo'), 404)->getRaw());

        $this->assertJson($this->response(null, 500)->getRaw());
        $this->assertJson($this->response('foo', 500)->getRaw());
        $this->assertJson($this->response([], 500)->getRaw());
        $this->assertJson($this->response(0, 500)->getRaw());
        $this->assertJson($this->response(new FooException('Foo'), 500)->getRaw());
        $this->assertJson($this->response(new FooHttpException('Foo'), 500)->getRaw());
    }

    public function testStructureSuccess()
    {
        $this->assertSame(['data' => null], $this->response(null, null, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => 'foo'], $this->response('foo', null, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => []], $this->response([], null, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => 0], $this->response(0, null, ['bar' => 'Bar'])->getJson());

        $this->assertSame(['data' => null], $this->response(null, 200, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => 'foo'], $this->response('foo', 200, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => []], $this->response([], 200, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => 0], $this->response(0, 200, ['bar' => 'Bar'])->getJson());

        $this->assertSame(['data' => null], $this->response(null, 300, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => 'foo'], $this->response('foo', 300, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => []], $this->response([], 300, ['bar' => 'Bar'])->getJson());
        $this->assertSame(['data' => 0], $this->response(0, 300, ['bar' => 'Bar'])->getJson());
    }

    public function testStructureErrors()
    {
        /*
         * NULL
         */

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(null, 0, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(null, 400, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(null, 404, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(null, 500, ['bar' => 'Bar'])->getJson()
        );

        /*
         * EMPTY ARRAY
         */

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response([], 0, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response([], 400, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response([], 404, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response([], 500, ['bar' => 'Bar'])->getJson()
        );

        /*
         * STRING
         */

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response('foo', 0, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'foo']],
            $this->response('foo', 400, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'foo']],
            $this->response('foo', 404, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response('foo', 500, ['bar' => 'Bar'])->getJson()
        );

        /*
         * ARRAY
         */

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(['foo'], 0, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => ['foo']]],
            $this->response(['foo'], 400, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => ['foo']]],
            $this->response(['foo'], 404, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(['foo'], 500, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(['foo' => 'Foo'], 0, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => ['foo' => 'Foo']]],
            $this->response(['foo' => 'Foo'], 400, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => ['foo' => 'Foo']]],
            $this->response(['foo' => 'Foo'], 404, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(['foo' => 'Foo'], 500, ['bar' => 'Bar'])->getJson()
        );

        /*
         * NUMERIC
         */

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(0, 0, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(0, 400, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(0, 404, ['bar' => 'Bar'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'Exception', 'data' => 'Whoops! Something went wrong.']],
            $this->response(0, 500, ['bar' => 'Bar'])->getJson()
        );
    }

    public function testStructureExceptions()
    {
        /*
         * FooException
         */

        $this->assertSame(
            ['error' => ['type' => 'FooException', 'data' => 'Whoops! Something went wrong.']],
            $this->response(new FooException('Foo Bar'), null, ['baz' => 'Baz'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'FooException', 'data' => 'Whoops! Something went wrong.']],
            $this->response(new FooException('Foo Bar'), 0, ['baz' => 'Baz'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'FooException', 'data' => 'Foo Bar']],
            $this->response(new FooException('Foo Bar'), 400, ['baz' => 'Baz'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'FooException', 'data' => 'Whoops! Something went wrong.']],
            $this->response(new FooException('Foo Bar'), 500, ['baz' => 'Baz'])->getJson()
        );

        /*
         * FooHttpException
         */

        $this->assertSame(
            ['error' => ['type' => 'FooHttpException', 'data' => 'Foo Bar']],
            $this->response(new FooHttpException('Foo Bar'), null, ['baz' => 'Baz'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'FooHttpException', 'data' => 'Whoops! Something went wrong.']],
            $this->response(new FooHttpException('Foo Bar'), 0, ['baz' => 'Baz'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'FooHttpException', 'data' => 'Foo Bar']],
            $this->response(new FooHttpException('Foo Bar'), 400, ['baz' => 'Baz'])->getJson()
        );

        $this->assertSame(
            ['error' => ['type' => 'FooHttpException', 'data' => 'Whoops! Something went wrong.']],
            $this->response(new FooHttpException('Foo Bar'), 500, ['baz' => 'Baz'])->getJson()
        );
    }

    public function testStatusCode()
    {
        $this->assertSame(403, $this->response(new FooHttpException('Foo'))->getStatusCode());
        $this->assertSame(502, $this->response(new FooException('Foo'))->getStatusCode());

        $this->assertSame(500, $this->response(null, 0)->getStatusCode());
        $this->assertSame(500, $this->response('foo', 0)->getStatusCode());
        $this->assertSame(500, $this->response([], 0)->getStatusCode());
        $this->assertSame(500, $this->response(0, 0)->getStatusCode());
        $this->assertSame(500, $this->response(new FooHttpException('Foo'), 0)->getStatusCode());
        $this->assertSame(500, $this->response(new FooException('Foo'), 0)->getStatusCode());

        $this->assertSame(400, $this->response(null, 400)->getStatusCode());
        $this->assertSame(400, $this->response('foo', 400)->getStatusCode());
        $this->assertSame(400, $this->response([], 400)->getStatusCode());
        $this->assertSame(400, $this->response(0, 400)->getStatusCode());
        $this->assertSame(400, $this->response(new FooHttpException('Foo'), 400)->getStatusCode());
        $this->assertSame(400, $this->response(new FooException('Foo'), 400)->getStatusCode());

        $this->assertSame(404, $this->response(null, 404)->getStatusCode());
        $this->assertSame(404, $this->response('foo', 404)->getStatusCode());
        $this->assertSame(404, $this->response([], 404)->getStatusCode());
        $this->assertSame(404, $this->response(0, 404)->getStatusCode());
        $this->assertSame(404, $this->response(new FooHttpException('Foo'), 404)->getStatusCode());
        $this->assertSame(404, $this->response(new FooException('Foo'), 404)->getStatusCode());

        $this->assertSame(500, $this->response(null, 500)->getStatusCode());
        $this->assertSame(500, $this->response('foo', 500)->getStatusCode());
        $this->assertSame(500, $this->response([], 500)->getStatusCode());
        $this->assertSame(500, $this->response(0, 500)->getStatusCode());
        $this->assertSame(500, $this->response(new FooHttpException('Foo'), 500)->getStatusCode());
        $this->assertSame(500, $this->response(new FooException('Foo'), 500)->getStatusCode());
    }
}
