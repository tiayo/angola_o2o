<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\MyException as Exception;

class ValidateKey
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
        if(! $request->has('key'))
            return response(['code' => 'api_key_needed', 'message' => 'api key needed', 'data' => ['status' => 401]],401);
        else if($request->get('key') !== config('APP.KEY'))
            return response(['code' => 'api_key_wrong', 'message' => 'api key wrong', 'data' => ['status' => 401]],401);
        return $next($request);
    }
}
