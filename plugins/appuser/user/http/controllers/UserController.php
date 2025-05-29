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
        // Create a new user
        $user = new User();
        $user->email = $request->input('email');
        $user->password = $request->input('password');

        // Generate a random token for the user
        $user->token = Str::random(30);

        // Save the user to the database
        $user->save();

        // Return a success response with the generated token
        return[
            'token' => $user->token
        ];
    }

    public function login(Request $request)
    {
        // Retrieve all posted data from the request
        $data = $request->post(); // REVIEW - Tip - stačí post() namiesto $request->post()

        // Find the user by their email address
        $user = User::where('email', $data['email'])->first(); // REVIEW - Tip - taktiež ->firstOrFail() je trochu kratšie

        // Check if the user exists
        if (!$user) {
            throw new Exception('User not found', 404);
        }

        // Check the provided password matches the stored hashed password
        if (!Hash::check($data['password'], $user->password)) {
            throw new Exception('Wrong password', 401);
        }

        // Generate a new random token for the user
        $user->token = Str::random(30);

        // Save the updated user record with the new token
        $user->save();

        // Return a success response with the generated token
        return[
            'token' => $user->token
        ];
    }

    public function logout(Request $request)
    {
        // Retrieve the authenticated user from the request
        $user = $request->user;

        // Invalidate the user's token by setting it to null
        $user->token = null;

        // Save the updated user record to the database
        $user->save();

        // Return a success response indicating the user has been logged out
        return null;
    }
}
