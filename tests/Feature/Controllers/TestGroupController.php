<?php

namespace YOoSlim\RoutingByAttributes\Tests\Feature\Controllers;

use YOoSlim\RoutingByAttributes\Attributes\Route;
use YOoSlim\RoutingByAttributes\Attributes\GetRoute;
use YOoSlim\RoutingByAttributes\Attributes\PostRoute;
use YOoSlim\RoutingByAttributes\Attributes\PutRoute;
use YOoSlim\RoutingByAttributes\Attributes\PatchRoute;
use YOoSlim\RoutingByAttributes\Attributes\DeleteRoute;
use YOoSlim\RoutingByAttributes\Attributes\RouteGroup;

#[RouteGroup(
    prefix: '/gtests'
)]
class TestGroupController
{
    #[Route('GET', '/tests')]
    public function index()
    {
        return response()->json(['message' => 'Tests listed']);
    }

    #[GetRoute('/tests/{id}')]
    public function show($id)
    {
        return response()->json(['message' => 'Test showed']);
    }

    #[PostRoute('/tests')]
    public function store()
    {
        return response()->json(['message' => 'Test stored'], 201);
    }

    #[PutRoute(
        path: '/tests/{id}',
        where: ['id' => '[0-9]+']
    )]
    public function replace($id)
    {
        return response()->json(['message' => 'Test fully updated'], 200);
    }

    #[PatchRoute('/tests/{id}')]
    public function update($id)
    {
        return response()->json(['message' => 'Test partialy updated'], 200);
    }

    #[DeleteRoute('/tests/{id}')]
    public function destroy($id)
    {
        return response()->noContent();
    }
}
