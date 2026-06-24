<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsDosen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'dosen') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya dosen yang dapat mengakses.',
            ], 403);
        }

        return $next($request);
    }
}
