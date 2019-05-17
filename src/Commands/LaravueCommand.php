<?php

namespace Fontenele\Laravue\Commands;

use App\Providers\AuthServiceProvider;
use File;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Laravel\Passport\Passport;
use Symfony\Component\Process\Process;

class LaravueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravue:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laravue';

    public function handle()
    {
        $this->migrate();

        $this->info("Dumping the composer autoload");
        (new Process('composer dump-autoload'))->run();

        $this->info('Publishing all files ...');
        $this->call('vendor:publish', ['--provider' => 'Fontenele\Laravue\Providers\LaravueServiceProvider', '--force' => true]);
        if (!File::exists(base_path('.babelrc'))) {
            File::put(base_path('.babelrc'), File::get(__DIR__ . '/../../.babelrc'));
        }

        $this->migrate();

        $this->changeBackend();
        $this->changeFrontend();
        $this->changeRoutes();

        $this->info("Dumping the composer autoload");
        (new Process('composer dump-autoload'))->run();
    }

    protected function changeBackend(): void
    {
        $reflectionHandler = new \ReflectionClass('App\Providers\AuthServiceProvider');
        $endLine = $reflectionHandler->getMethod('boot')->getEndLine() - 2;

        $classContent = explode("\n", File::get($reflectionHandler->getFileName()));
        \array_insert($classContent, $endLine, '        \Laravel\Passport\Passport::routes(function ($router) {');
        \array_insert($classContent, $endLine + 1, '            $router->forAccessTokens();');
        \array_insert($classContent, $endLine + 2, '        });');
        \array_insert($classContent, $endLine + 3, '        \Laravel\Passport\Passport::tokensExpireIn(now()->addDays(15));');
        \array_insert($classContent, $endLine + 4, '        \Laravel\Passport\Passport::refreshTokensExpireIn(now()->addDays(30));');
        \array_insert($classContent, $endLine + 5, '        \Laravel\Passport\Passport::personalAccessTokensExpireIn(now()->addMonths(6));');

        File::put($reflectionHandler->getFileName(), implode("\n", $classContent));

        $classContent = collect(explode("\n", File::get(app_path('Http/Kernel.php'))));
        $startRouteMiddlewareLine = false;
        $endRouteMiddlewareLine = false;
        $classContent->each(function ($line, $key) use (&$startRouteMiddlewareLine, &$endRouteMiddlewareLine) {
            if ($startRouteMiddlewareLine === false && $endRouteMiddlewareLine === false && strpos($line, '$routeMiddleware') !== false) {
                $startRouteMiddlewareLine = $key + 2;
            }
            if ($startRouteMiddlewareLine !== false && $endRouteMiddlewareLine === false) {
                if (strpos($line, "];") !== false) {
                    $endRouteMiddlewareLine = $key;
                    return;
                }
            }
        });
        $classContent = $classContent->toArray();
        array_insert($classContent, $endRouteMiddlewareLine, "        'json.response' => \App\Http\Middleware\ForceJsonResponse::class,");
        File::put(app_path('Http/Kernel.php'), implode("\n", $classContent));
    }

    protected function changeFrontend(): void
    {
        $this->info('Changing laravel scaffolding to Vue.js ...');
        $this->call('preset', ['type' => 'none']);
        $this->call('preset', ['type' => 'vue']);

        $packagesYarn = [
            '@babel/plugin-proposal-class-properties',
            '@babel/plugin-syntax-dynamic-import',
            'axios',
            'cross-env',
            'date-fns',
            'dayjs',
            'echarts',
            'echarts-gl',
            'font-awesome',
            'laravel-mix',
            'lodash',
            'moment',
            'resolve-url-loader',
            'sass',
            'sass-loader',
            'vue',
            'vue-axios',
            'vue-dayjs',
            'vue-echarts',
            'vue-i18n',
            'vue-moment',
            'vue-notification',
            'vue-router',
            'vue-template-compiler',
            'vue-the-mask',
            'vue-toasted',
            'vuetify',
            'vuex',
            'vuex-persistedstate',
        ];

        $this->info('Installing npm packages...');
        (new Process('yarn add -D ' . implode(' ', $packagesYarn)))->run();

        File::put(base_path('resources/js/app.js'), File::get(__DIR__ . '/../../resources/js/app.js'));
        File::put(base_path('resources/js/bootstrap.js'), File::get(__DIR__ . '/../../resources/js/bootstrap.js'));
        File::put(base_path('resources/sass/app.scss'), File::get(__DIR__ . '/../../resources/sass/app.scss'));

        $this->info('Building frontend ...');
        (new Process('yarn dev'))->run();
    }

    protected function migrate(): void
    {
        try {
            $this->call('migrate');
        } catch (QueryException $e) {
            $this->error($e->getMessage());
            exit();
        }
    }

    protected function changeRoutes(): void
    {
        // WEB
        $reflectRoute = collect(explode("\n", File::get(base_path('routes/web.php'))));
        $startBaseRouteLine = false;
        $endBaseRouteLine = false;
        $commentRoute = true;

        $reflectRoute->each(function ($line, $key) use (&$startBaseRouteLine, &$endBaseRouteLine, &$reflectRoute, &$commentRoute) {
            if ($commentRoute && $startBaseRouteLine === false && strpos($line, "Route::get('/'") !== false) {
                if (substr($line, 0, 2) === '//') {
                    $commentRoute = false;
                    return;
                }
                $startBaseRouteLine = $key;
                $reflectRoute[$key] = '//' . $line;
            }

            if ($commentRoute && $startBaseRouteLine !== false && $endBaseRouteLine === false) {
                $reflectRoute[$key] = '//' . $line;
                if (strpos($line, "});") !== false) {
                    $endBaseRouteLine = $key;
                }
            }
        });

        if ($commentRoute) {
            $webRoutes = $reflectRoute->join("\n");
            $webRoutes .= "\n" . File::get(__DIR__ . '/../../routes/web.php');
            $routeFile = base_path('routes/web.php');
            File::put($routeFile, $webRoutes);
        }

        // API
        $reflectRoute = collect(explode("\n", File::get(base_path('routes/api.php'))));
        $startBaseRouteLine = false;
        $endBaseRouteLine = false;
        $commentRoute = true;

        $reflectRoute->each(function (&$line, $key) use (&$startBaseRouteLine, &$endBaseRouteLine, &$reflectRoute, &$commentRoute) {
            if ($commentRoute && $startBaseRouteLine === false && strpos($line, "Route::middleware('auth:api')->get('/user'") !== false) {
                if (substr($line, 0, 2) === '//') {
                    $commentRoute = false;
                    return;
                }
                $startBaseRouteLine = $key;
                $reflectRoute[$key] = '//' . $line;
            }

            if ($commentRoute && $startBaseRouteLine !== false && $endBaseRouteLine === false) {
                $reflectRoute[$key] = '//' . $line;
                if (strpos($line, "});") !== false) {
                    $endBaseRouteLine = $key;
                }
            }
        });

        if ($commentRoute) {
            $apiRoutes = $reflectRoute->join("\n");
            $apiRoutes .= "\n" . File::get(__DIR__ . '/../../routes/api.php');
            $routeFile = base_path('routes/api.php');
            File::put($routeFile, $apiRoutes);
        }
    }
}
