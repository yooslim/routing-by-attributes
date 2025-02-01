<?php

use \Illuminate\Support\Facades\Route;

test('All routes of TestResourcefulController are successfully loaded.', function () {
    $this->defineControllersLocation();

    // Define the expected routes
    $expectedRoutes = [
        ['GET', 'rtests'],
        ['GET', 'rtests/{id}'],
        ['POST', 'rtests'],
        ['PUT', 'rtests/{id}'],
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
