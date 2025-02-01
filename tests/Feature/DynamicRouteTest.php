<?php

use \Illuminate\Support\Facades\Route;

test('All routes of TestController are successfully loaded.', function () {
    $this->defineControllersLocation();

    // Define the expected routes
    $expectedRoutes = [
        ['GET', 'tests'],
        ['GET', 'tests/{id}'],
        ['POST', 'tests'],
        ['PUT', 'tests/{id}'],
        ['PATCH', 'tests/{id}'],
        ['DELETE', 'tests/{id}'],
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

test('Route of TestInvokableController is successfully loaded.', function () {
    $this->defineControllersLocation();

    // Define the expected routes
    $expectedRoutes = [
        ['GET', 'tests'],
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

test('Routes count is as expected.', function () {
    $foundRoutes = [];

    foreach (Route::getRoutes() as $route) {
        foreach ($route->methods as $method) {
            if ($method === 'HEAD' || !str($route->uri)->startsWith('tests')) {
                continue;
            }

            $foundRoutes[] = $method . ':' . $route->uri;
        }
    }

    $this->assertEquals(count($foundRoutes), 6);
});

test('Route GET /tests is returning the right response.', function () {
    $response = $this->get('/tests');

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Tests listed',
        ]);
});

test('Route POST /tests is returning the right response.', function () {
    $response = $this->post('/tests');

    $response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Test stored',
        ]);
});

test('Route PUT /tests/{id} is returning the right response.', function () {
    $response = $this->put('/tests/1');

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Test fully updated',
        ]);
});

test('Route PATCH /tests/{id} is returning the right response.', function () {
    $response = $this->patch('/tests/1');

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Test partialy updated',
        ]);
});

test('Route DELETE /tests/{id} is returning the right response.', function () {
    $response = $this->delete('/tests/1');

    $response->assertStatus(204);
});

test('Route GET /mytests is resolvable using the defined route\'s name.', function () {
    $this->assertEquals(route('mytests.index'), url('mytests'));
});

test('Where regex validation works correctly.', function () {
    $response = $this->put('/tests/1string');

    $response->assertStatus(405);
});

test('Defaults parameters work correctly.', function () {
    $response = $this->get('/mytests');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Tests invoked : mydefault',
        ]);
});