<?php

namespace YOoSlim\RoutingByAttributes\Tests;
 
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use YOoSlim\RoutingByAttributes\Providers\RoutingByAttributesProvider;
 
class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        RoutingByAttributesProvider::setControllersDirectoriesLocation(function () {
            return [
                __DIR__ . '/Feature/Controllers' => 'YOoSlim\RoutingByAttributes\Tests\Feature\Controllers',
            ];
        });

        return [
            RoutingByAttributesProvider::class,
        ];
    }

    protected function defineControllersLocation(): void
    {
        RoutingByAttributesProvider::setControllersDirectoriesLocation(function () {
            return [__DIR__ . '/Feature/Controllers'];
        });
    }
}
