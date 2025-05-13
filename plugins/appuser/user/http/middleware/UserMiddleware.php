<?php namespace AppUser\User\Http\Middleware;

use Closure;
use AppUser\User\Models\User;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the bearer token from the request
        $token = $request->bearerToken();

        // Check if a token is provided in the request
        if (!$token) {
            // Return a JSON response with an error if no token is provided
            return response()->json([
                'staus' => 'error',
                'message' => 'No token provided',
            ], 500);
        }

        // Find the user associated with the provided token
        $user = User::where('token', $token)->first();

        // Check if the token is invalid (no user found)
        if (!$user) {
            // Return a JSON response with an error if the token is invalid
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token',
            ], 500);
        }

        // Merge the user data into the request for further processing
        $request->merge(['user' => $user]);

        // Proceed to the next middleware or request handler
        return $next($request);
    }
}
