<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class XSS
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {
            $migrations             = $this->getMigrations();
            $dbMigrations           = $this->getExecutedMigrations();
            $numberOfUpdatesPending = count($migrations) - count($dbMigrations);

            if($numberOfUpdatesPending > 0)
            {
                return redirect()->route('LaravelUpdater::welcome');
            }
        }

        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = htmlspecialchars_decode($value);
                $value = preg_replace('/<\s*script\b[^>]*>(.*?)<\s*\/\s*script\s*>/is', '', $value);
                $value = str_replace(['&lt;', '&gt;', 'javascript','alert'], '', $value);
            }
        });
        $request->merge($input);
        return $next($request);
    }
}

