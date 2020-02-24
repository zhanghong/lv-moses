<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Shop\Shop;

class CheckCurrentShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Shop::current()) {
            return response()->json(['error' => 'Unauthorized']);
        }

        return $next($request);
    }
}
