<?php declare(strict_types=1);

namespace App\Ship\Utils;

use Rudra\Container\Facades\Rudra;
use Rudra\Router\RouterFacade as Router;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class RoutingCollector extends DataCollector implements Renderable
{
    #[\Override]
    public function getName(): string
    {
        return 'routing';
    }

    #[\Override]
    public function getWidgets(): array
    {
        return [
            "routes" => [
                "icon"    => "list",
                "widget"  => "PhpDebugBar.Widgets.HtmlVariableListWidget",
                "map"     => "routing.html",
                "default" => "[]",
            ],
            "routes:badge" => [
                "map"     => "routing.total",
                "default" => "null",
            ],
        ];
    }

    #[\Override]
    public function collect(): array
    {
        try {
            $containers = Rudra::config()->get('containers') ?? [];
            $attributes = (bool) (Rudra::config()->get('attributes') ?? false);
            $appPath = Rudra::config()->get('app.path');
            
            $routes = [];
            $total = 0;

            foreach ($containers as $container => $item) {
                $containerRoutes = $this->getRoutes($container, $attributes, $appPath);

                if (empty($containerRoutes)) {
                    continue;
                }

                foreach ($containerRoutes as $list) {
                    foreach ($list as $route) {
                        $routes[] = [
                            'container'  => ucfirst($container),
                            'method'     => strtoupper($route['method'] ?? 'GET'),
                            'url'        => $route['url'] ?? '?',
                            'controller' => $this->shortClass($route['controller'] ?? ''),
                            'action'     => $route['action'] ?? 'actionIndex',
                        ];
                        $total++;
                    }
                }
            }

            if (empty($routes)) {
                return [
                    'html'  => ['' => '<div style="color:#808080;font-style:italic;padding:10px;">No routes registered</div>'],
                    'total' => 0,
                ];
            }

            $html = $this->renderTable($routes);

            return [
                'html'  => ['' => $html],
                'total' => $total,
            ];

        } catch (\Throwable $e) {
            return [
                'html'  => ['' => '<div style="color:#f48771;padding:10px;">Error: ' . htmlspecialchars($e->getMessage()) . '</div>'],
                'total' => 0,
            ];
        }
    }

    private function renderTable(array $routes): string
    {
        $html = '<div style="background:#2d2d2d;border-left:3px solid #17a2b8;border-radius:4px;overflow:hidden;font-family:\'JetBrains Mono\',\'Fira Code\',Consolas,monospace;font-size:12px;line-height:1.6;">';
        
        $html .= '<div style="background:#1a1a1a;color:#d4d4d4;padding:8px 12px;border-bottom:1px solid rgba(255,255,255,0.1);">';
        $html .= '<span style="color:#17a2b8;font-weight:600;">' . count($routes) . '</span>';
        $html .= '<span style="color:#808080;margin:0 8px;">routes</span>';
        $html .= '</div>';
        
        $html .= '<table style="width:100%;border-collapse:collapse;">';
        
        $html .= '<thead><tr style="background:#1a1a1a;color:#808080;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">';
        $html .= '<th style="padding:6px 10px;text-align:left;font-weight:500;width:40px;">#</th>';
        $html .= '<th style="padding:6px 10px;text-align:left;font-weight:500;">Container</th>';
        $html .= '<th style="padding:6px 10px;text-align:left;font-weight:500;">Method</th>';
        $html .= '<th style="padding:6px 10px;text-align:left;font-weight:500;">URL</th>';
        $html .= '<th style="padding:6px 10px;text-align:left;font-weight:500;">Controller</th>';
        $html .= '<th style="padding:6px 10px;text-align:left;font-weight:500;">Action</th>';
        $html .= '</tr></thead><tbody>';

        $i = 1;
        foreach ($routes as $route) {
            $bg = ($i % 2 === 0) ? '#252525' : '#2d2d2d';
            $methodColor = $this->getMethodColor($route['method']);
            
            $html .= '<tr style="background:' . $bg . ';">';
            $html .= '<td style="padding:6px 10px;color:#808080;">' . $i . '</td>';
            $html .= '<td style="padding:6px 10px;color:#17a2b8;">' . htmlspecialchars($route['container']) . '</td>';
            $html .= '<td style="padding:6px 10px;"><span style="background:' . $methodColor['bg'] . ';color:' . $methodColor['color'] . ';padding:2px 8px;border-radius:3px;font-weight:600;font-size:10px;">' . htmlspecialchars($route['method']) . '</span></td>';
            $html .= '<td style="padding:6px 10px;color:#ce9178;">' . htmlspecialchars($route['url']) . '</td>';
            $html .= '<td style="padding:6px 10px;color:#9cdcfe;">' . htmlspecialchars($route['controller']) . '</td>';
            $html .= '<td style="padding:6px 10px;color:#dcdcaa;">' . htmlspecialchars($route['action']) . '</td>';
            $html .= '</tr>';
            $i++;
        }

        $html .= '</tbody></table></div>';
        
        return $html;
    }

    private function getMethodColor(string $method): array
    {
        return match ($method) {
            'GET'    => ['bg' => 'rgba(76,175,80,0.2)', 'color' => '#81c784'],
            'POST'   => ['bg' => 'rgba(33,150,243,0.2)', 'color' => '#64b5f6'],
            'PUT'    => ['bg' => 'rgba(255,152,0,0.2)', 'color' => '#ffb74d'],
            'PATCH'  => ['bg' => 'rgba(156,39,176,0.2)', 'color' => '#ba68c8'],
            'DELETE' => ['bg' => 'rgba(244,67,54,0.2)', 'color' => '#e57373'],
            default  => ['bg' => 'rgba(158,158,158,0.2)', 'color' => '#bdbdbd'],
        };
    }

    private function getRoutes(string $container, bool $attributes, ?string $appPath): array
    {
        $basePath = $appPath ? rtrim($appPath, '/') : '.';
        $path = $basePath . "/app/Containers/" . ucfirst($container) . "/routes";

        if (file_exists($path . ".php")) {
            $controllers = require $path . ".php";
            return Router::annotationCollector($controllers, true, $attributes);
        }

        return [];
    }

    private function shortClass(string $fqcn): string
    {
        if (empty($fqcn)) {
            return '';
        }
        $parts = explode('\\', $fqcn);
        return end($parts);
    }
}