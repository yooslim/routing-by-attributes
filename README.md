# Routing by Attributes

This package provides a convenient way to define routes using attributes in your Laravel application.

## Description

This package allows you to define routes directly in your controller classes using attributes. This approach simplifies route management and keeps your routing logic close to your controller actions. Also, the controllers location is set by default in app/Http/Controllers, but can be customized using the setControllersDirectoriesLocation helper method, see example.

## Installation

To install the package, you can use Composer:

```bash
composer require yooslim/routing-by-attributes
```

## Usage

### Route, GetRoute, PostRoute, PutRoute, PatchRoute, DeleteRoute

To define a route using the `Route` attribute, you can add it directly to your controller method:

```php
use YOoSlim\RoutingByAttributes\Attributes\Route;

class MyController
{
    #[Route(
        method: 'GET', // string, mandatory
        path: '/my-route', // string, mandatory
        name: '', // string, optional
        middleware: [], // array<key, value>|string, optional
        where: [], // array<key, value>, optional
        defaults: [] // array<key, value>, optional
    )]
    public function myAction()
    {
        // Your action logic
    }
}
```

When using the other verbal attributes, you just remove the first parameter "method".

### ResourceRoute

To define resource routes, you can use the `ResourceRoute` attribute:

```php
use YOoSlim\RoutingByAttributes\Attributes\ResourceRoute;

#[ResourceRoute(
    path: '/my-resource',
    only: [], // array<key, value>, optional
    except: [], // array<key, value>, optional
    names: [], // array<key, value>, optional
    parameters: [], // array<key, value>, optional
    middleware: [], // array<key, value>|string, optional
)]
class MyResourceController
{
    public function index()
    {
        // List resources
    }

    public function show($id)
    {
        // Show a single resource
    }

    public function store()
    {
        // Create a new resource
    }

    public function update($id)
    {
        // Update a resource
    }

    public function destroy($id)
    {
        // Delete a resource
    }
}
```

### RouteGroup

To group routes, you can use the `RouteGroup` attribute:

```php
use YOoSlim\RoutingByAttributes\Attributes\RouteGroup;
use YOoSlim\RoutingByAttributes\Attributes\GetRoute;
use YOoSlim\RoutingByAttributes\Attributes\PostRoute;

#[RouteGroup(
    prefix: '/group', // string
    as: '', // string
    namespace: '', // string
    parameters: [], // array<key, value>, optional
    middleware: [], // array<key, value>|string, optional
)]
class MyGroupedController
{
    #[GetRoute('/route1')]
    public function route1()
    {
        // Your action logic for route1
    }

    #[PostRoute('/route2')]
    public function route2()
    {
        // Your action logic for route2
    }
}
```
