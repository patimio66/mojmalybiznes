<?php

namespace App\Http\Middleware;

use Closure;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TransactionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        DB::beginTransaction();
        try {
            $response = $next($request);
            if (isset($response->exception)) {
                throw $response->exception;
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            throw new Error($e->getMessage(), $e->getCode());
        }
    }
}
