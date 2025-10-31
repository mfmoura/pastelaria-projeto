<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcarRespostaJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $content = $response->getContent();

        return response()->json([
            'success' => $response->isSuccessful(),
            'data'    => json_decode($content, true),
        ], $response->getStatusCode());
    }

}
