<?php

namespace YOoSlim\RoutingByAttributes\Attributes;

use Attribute;

#[Attribute]
class PutRoute extends Route
{
    public array $methods;

    public function __construct(
        public string $path,
        public string | null $name = null,
        public string|array $middleware = [],
        public array $where = [],
        public array $defaults = [],
    ) {
        parent::__construct('PUT', $path, $name, $middleware, $where, $defaults);
    }
}