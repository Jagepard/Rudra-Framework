<?php

namespace App\Ship;

use Rudra\Container\Facades\Rudra;
use Rudra\Exceptions\RouterException;
use Rudra\Router\RouterFacade as Router;

class Route
{
    /**
     * @throws RouterException
     */
    public function run()
    {
        if (config('environment') === 'development') {
            $timeCollector = Rudra::get("debugbar")['time'];

            if ($timeCollector->hasStartedMeasure('index')) {
                $timeCollector->stopMeasure('index');
            }

            if (!$timeCollector->hasStartedMeasure('routing')) {
                $timeCollector->startMeasure('routing');
            }
        }

        $this->collect(Rudra::config()->get('containers'));

        if (config('environment') !== 'test') {
            exit();
        }
    }

    protected function collect(array $namespaces): void
    {
        foreach ($namespaces as $container => $item) {
            $routes     = $this->getRoutes($container);
            $flatRoutes = array_merge(...$routes);

            foreach ($flatRoutes as $route) {
                Router::set($route);
            }
        }
    }

    protected function getRoutes(string $container): array
    {
        $cacheDir  = "../app/cache";
        $routesDir = $cacheDir . "/routes";

        $cacheFile = $routesDir . "/routes_" . $container . ".php";

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        if (!is_dir($routesDir)) {
            mkdir($routesDir, 0755, true);
        }

        if (file_exists($cacheFile)) {
            return include $cacheFile;
        }

        $path = "../app/Containers/" . ucfirst($container) . "/routes";

        if (!file_exists($path . ".php")) {
            throw new \RuntimeException("Routes file not found for container: " . $container);
        }

        $routes = Router::annotationCollector(require_once $path . ".php", true, Rudra::config()->get("attributes"));
        file_put_contents($cacheFile, "<?php\nreturn " . var_export($routes, true) . ";");

        return $routes;
    }
}
