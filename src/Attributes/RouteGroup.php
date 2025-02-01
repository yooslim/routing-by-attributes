<?php

namespace YOoSlim\RoutingByAttributes\Attributes;

use Attribute;

#[Attribute]
class RouteGroup
{
    public function __construct(
        public string|null $prefix = null,
        public string|null $as = null,
        public string|null $namespace = null,
        public array $parameters = [],
        public string|array $middleware = [],
    ) {
        //
    }
}