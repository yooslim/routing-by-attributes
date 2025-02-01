<?php

namespace YOoSlim\RoutingByAttributes\Tests\Feature\Controllers;

use YOoSlim\RoutingByAttributes\Attributes\ResourceRoute;

#[ResourceRoute(
    path: '/rtests',
    only: ['index', 'show', 'store', 'update'],
    parameters: [
        'rtests' => 'id'
    ]
)]
class TestResourcefulController
{
    public function index()
    {
        return response()->json(['message' => 'Tests listed']);
    }

    public function show($id)
    {
        return response()->json(['message' => 'Test showed']);
    }

    public function store()
    {
        return response()->json(['message' => 'Test stored'], 201);
    }

    public function update($id)
    {
        return response()->json(['message' => 'Test partialy updated'], 200);
    }
}
