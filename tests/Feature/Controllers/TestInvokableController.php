<?php

namespace YOoSlim\RoutingByAttributes\Tests\Feature\Controllers;

use YOoSlim\RoutingByAttributes\Attributes\GetRoute;
use YOoSlim\RoutingByAttributes\Attributes\PostRoute;
use YOoSlim\RoutingByAttributes\Attributes\PutRoute;
use YOoSlim\RoutingByAttributes\Attributes\PatchRoute;
use YOoSlim\RoutingByAttributes\Attributes\DeleteRoute;

#[GetRoute(
    path: '/mytests/{param?}',
    name: 'mytests.index',
    defaults: ['param' => 'mydefault']
)]
class TestInvokableController
{
    public function __invoke(string $param)
    {
        return response()->json(['message' => 'Tests invoked : ' . $param]);
    }
}
