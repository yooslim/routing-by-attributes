<?php

use \Illuminate\Support\Facades\Route;

test('All routes of TestGroupController are successfully loaded.', function () {
    $this->defineControllersLocation();

    // Define the expected routes
    $expectedRoutes = [
        ['GET', 'gtests/tests'],
        ['GET', 'gtests/tests/{id}'],
        ['POST', 'gtests/tests'],
        ['PUT', 'gtests/tests/{id}'],
        ['PATCH', 'gtests/tests/{id}'],
        ['DELETE', 'gtests/tests/{id}'],
    ];

    $foundRoutes = [];

    foreach (Route::getRoutes() as $route) {
        foreach ($route->methods as $method) {
            $foundRoutes[] = $method . ':' . $route->uri;
        }
    }

    foreach ($expectedRoutes as $route) {
        $this->assertContains($route[0] . ':' . $route[1], $foundRoutes);
    }    
});
