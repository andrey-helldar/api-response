<?php

namespace Tests\Fixtures\Concerns\Laravel;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;

/** @mixin \Tests\Laravel\TestCase */
trait Maintenance
{
    protected function makeDownFile(): void
    {
        $filename = storage_path('framework/down');

        if (! file_exists($filename)) {
            file_put_contents(storage_path('framework/down'), json_encode([
                'retry' => 60,
            ]));
        }
    }

    protected function removeDownFile(): void
    {
        @unlink(storage_path('framework/down'));
    }

    protected function setRoutes($app): void
    {
        $app['router']->get('/', static function () {
            return api_response('ok');
        })->middleware(PreventRequestsDuringMaintenance::class);
    }
}
