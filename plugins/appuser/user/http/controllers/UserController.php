<?php namespace AppUser\User\Http\Controllers;

use AppUser\User\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $data = $request->only(['email', 'password']);
            $data['password'] = Hash::make($data['password']);
            $user = new User($data);
            $user->token = Str::random(30);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'token' => $user->token
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to register user', 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->only(['email', 'password']);
            
            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user->token = Str::random(30);
            $user->save();

            return response()->json(['token' => $user->token]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to login', 'message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user;
            $user->token = null;
            $user->save();

            return response()->json(['message' => 'Logged out']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to logout', 'message' => $e->getMessage()], 500);
        }
    }
}
