<?php

namespace YOoSlim\RoutingByAttributes\Attributes;

use Attribute;

#[Attribute]
class ResourceRoute
{
    public function __construct(
        public string $path,
        public array|null $only = null,
        public array|null $except = null,
        public array|null $names = null,
        public array|null $parameters = null,
        public string|array $middleware = [],
    ) {
        //
    }
}