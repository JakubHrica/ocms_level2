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
            // Retrieve all posted data from the request
            $data = $request->post();

            // Hash the user's password for secure storage
            $data['password'] = Hash::make($data['password']);

            // Create a new User instance with the provided data
            $user = new User($data);

            // Generate a random token for the user
            $user->token = Str::random(30);

            // Save the user to the database
            $user->save();

            // Return a success response with the generated token
            return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'token' => $user->token
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to register user');
        }
    }

    public function login(Request $request)
    {
        try {
            // Retrieve all posted data from the request
            $data = $request->post();
            
            // Find the user by their email address
            $user = User::where('email', $data['email'])->first();

            // Check if the user exists and the provided password matches the stored hashed password
            if (!$user || !Hash::check($data['password'], $user->password)) {
            // Return an error response if credentials are invalid
            return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Generate a new random token for the user
            $user->token = Str::random(30);

            // Save the updated user record with the new token
            $user->save();

            // Return a success response with the generated token
            return response()->json(['token' => $user->token]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to login');
        }
    }

    public function logout(Request $request)
    {
        try {
            // Retrieve the authenticated user from the request
            $user = $request->user;

            // Invalidate the user's token by setting it to null
            $user->token = null;

            // Save the updated user record to the database
            $user->save();

            // Return a success response indicating the user has been logged out
            return response()->json([
            'status' => 'success',
            'message' => 'Logged out'
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to logout');
        }
    }

    private function handleException(Exception $e, $defaultMessage)
    {
        return response()->json([
            'error' => $defaultMessage,
            'message' => $e->getMessage()
        ], 500);
    }
}
