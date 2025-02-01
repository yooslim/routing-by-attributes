<?php

namespace YOoSlim\RoutingByAttributes\Attributes;

use Attribute;

#[Attribute]
class Route
{
    public array $methods;

    public function __construct(
        string|array $methods,
        public string $path,
        public string | null $name = null,
        public string|array $middleware = [],
        public array $where = [],
        public array $defaults = [],
    ) {
        $this->methods = is_array($methods) ? $methods : explode('|', $methods);
    }
}