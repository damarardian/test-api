<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
// use Symfony\Component\HttpFoundation\Response;

class Verified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where("email", $request->email)->first();
        if($user->email_verified_at == null) {
            return response()->json(["error" => "You must verify your email address to login"], 403);
        }
        return $next($request);
    }
}
