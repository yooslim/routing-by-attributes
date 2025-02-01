<?php

namespace YOoSlim\RoutingByAttributes\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionAttribute;

class RoutingByAttributesProvider extends ServiceProvider
{
    /**
     * Callback to get list of controllers directories location
     * 
     * @var mixed
     */
    private static mixed $controllersDirectoriesCallabck = null;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutesBasedOnControllersAttributes();
    }

    /**
     * Resolve routes based on special attributes present within targeted controllers.
     *
     * @return void
     */
    protected function registerRoutesBasedOnControllersAttributes(): void
    {
        foreach ($this->resolveControllersClasses() as $className) {
            $reflectionClass = new ReflectionClass($className);

            // Look for class attributes
            $attributes = $reflectionClass->getAttributes();

            foreach ($attributes as $attribute) {
                $this->registerResourceIfAdequate($reflectionClass, $attribute);

                $this->registerRouteIfAdequate($reflectionClass, '__invoke', $attribute);
            }

            // Look for method attributes
            $methods = $reflectionClass->getMethods();

            foreach ($methods as $method) {
                $attributes = $method->getAttributes();

                foreach ($attributes as $attribute) {
                    $this->registerRouteIfAdequate($reflectionClass, $method->getName(), $attribute);
                }
            }
        }
    }

    /**
     * Resolve the controllers classes from the files path
     * 
     * @return array<string>
     */
    public function resolveControllersClasses(): array
    {
        // Ignore cache if in dev env or key doesnt exist, otherwise, use cache for better performance
        if (!in_array(config('app.env'), ['local', 'testing']) || cache()->has('resolved_controller_classes')) {
            return cache()->get('resolved_controller_classes', []);
        }
        
        $files = $this->resolveControllersPaths();

        $classes = [];

        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);

            if (class_exists($className)) {
                $classes[] = $className;
            }
        }

        if (config('app.env') !== 'local') {
            cache()->put('resolved_controller_classes', $classes);
        }


        return $classes;
    }

    /**
     * Resolve the files path in the controllers directories
     * 
     * @return array<string>
     */
    public function resolveControllersPaths(): array
    {
        $paths = [];

        foreach ($this->controllersDirectoriesList() as $directory => $namespace) {
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                $paths[] = $file->getRealPath();
            }
        }

        return $paths;
    }

    /**
     * Get the fully qualified class name from a file.
     *
     * @param  string $file
     * @return ?string
     */
    protected function getClassNameFromFile(string $file): ?string
    {
        $namespace = null;

        foreach ($this->controllersDirectoriesList() as $directory => $dirNamespace) {
            if (str($file)->startsWith($directory)) {
                $namespace = $dirNamespace;
                $relativePath = str_replace($directory, '', $file);
                break;
            }
        }

        if ($namespace === null) {
            return null;
        }

        $className = str_replace(['/', '.php'], ['\\', ''], $relativePath);

        return $namespace . $className;
    }

    /**
     * Register a route
     * 
     * @param  ReflectionClass $class
     * @param  string $methodName
     * @param  ReflectionAttribute $attribute
     * @return void
     */
    public function registerRouteIfAdequate(ReflectionClass $class, string $methodName, ReflectionAttribute $attribute): void
    {
        if (in_array(
            $attribute->getName(), 
            [
                'YOoSlim\RoutingByAttributes\Attributes\Route',
                'YOoSlim\RoutingByAttributes\Attributes\GetRoute',
                'YOoSlim\RoutingByAttributes\Attributes\PostRoute',
                'YOoSlim\RoutingByAttributes\Attributes\PutRoute',
                'YOoSlim\RoutingByAttributes\Attributes\PatchRoute',
                'YOoSlim\RoutingByAttributes\Attributes\DeleteRoute'
            ]
        ) && $class->hasMethod($methodName) && $class->getMethod($methodName)->isPublic()) {
            $newInstance = $attribute->newInstance();

            // Check if class has a RouteGroup attribute
            $groupAttributes = $class->getAttributes('YOoSlim\RoutingByAttributes\Attributes\RouteGroup');

            $addRouteCallback = function() use ($newInstance, $class, $methodName) {
                $route = Route::addRoute(
                    $newInstance->methods,
                    $newInstance->path,
                    $class->name . '@' . $methodName
                );
    
                // Define route name
                if (!empty($newInstance->name)) {
                    $route->name($newInstance->name);
                }
    
                // Define middleware
                if (!empty($newInstance->middleware)) {
                    $route->middleware($newInstance->middleware);
                }
    
                // Define where regex
                if (!empty($newInstance->where)) {
                    foreach ($newInstance->where as $key => $value) {
                        $route->where($key, $value);
                    }
                }
    
                // Define defaults parameters
                if (!empty($newInstance->defaults)) {
                    foreach ($newInstance->defaults as $key => $value) {
                        $route->defaults($key, $value);
                    }
                }
            };

            if (count($groupAttributes) > 0) {
                $newGroupInstance = $groupAttributes[0]->newInstance();

                Route::group([
                    'prefix' => $newGroupInstance->prefix,
                    'as' => $newGroupInstance->as,
                    'namespace' => $newGroupInstance->namespace,
                    'middleware' => $newGroupInstance->middleware,
                    'parameters' => $newGroupInstance->parameters,
                ], $addRouteCallback);
            } else {
                $addRouteCallback();
            }
        }
    }

    /**
     * Register a resource route
     * 
     * @param  ReflectionClass $class
     * @param  ReflectionAttribute $attribute
     * @return void
     */
    public function registerResourceIfAdequate(ReflectionClass $class, ReflectionAttribute $attribute): void
    {
        if ($attribute->getName() === 'YOoSlim\RoutingByAttributes\Attributes\ResourceRoute') {
            $newInstance = $attribute->newInstance();

            $options = [];

            // Define only methods
            if (!empty($newInstance->only)) {
                $options['only'] = $newInstance->only;
            }

            // Define except methods
            if (!empty($newInstance->only)) {
                $options['except'] = $newInstance->except;
            }

            // Define route names
            if (!empty($newInstance->names)) {
                $options['names'] = $newInstance->names;
            }

            // Define route parameters
            if (!empty($newInstance->parameters)) {
                $options['parameters'] = $newInstance->parameters;
            }

            // Define middleware
            if (!empty($newInstance->middleware)) {
                $options['middleware'] = $newInstance->middleware;
            }

            Route::resource(
                $newInstance->path,
                $class->name,
                $options
            );
        }
    }

    /**
     * Define the directories to scan for controllers, each one with its namespace.
     * 
     * @return array<string, string>
     */
    public static function controllersDirectoriesList(): array
    {
        // Set default list
        $list = [
            app_path('Http/Controllers') => 'App\Http\Controllers',
        ];

        // If callback has been defined, then override the default list
        if (is_callable(static::$controllersDirectoriesCallabck)) {
            $list = call_user_func(static::$controllersDirectoriesCallabck);
        }

        return $list;
    }

    /**
     * Set the callback to get custom list of controllers directories location
     * 
     * @param  callable $callback
     * @return void
     */
    public static function setControllersDirectoriesLocation(callable $callback): void
    {
        static::$controllersDirectoriesCallabck = $callback;
    }
}
