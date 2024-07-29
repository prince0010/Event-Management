<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //Authenticate and Logout


    public function login(Request $request)
    {

        // Validate the Credentials
        // Getting the Email and Password
        $request->validate(
            [
            'email'=>'required|email',
            'password' => 'required'
            ]
        );
        // Once validated the data, find the user that someone requests to sign in as. Pangitaon niya ang user na nag sign in
        $user = User::where('email', $request->email)->first(); // use the user model to try and find the user by email so we can use the where() clause where the 'email' is exactly as the $request->email, so that would be a field on the request body and fetch the first() element that can be found using this email.
        
        // Checks if the user exists
        if(!$user){
            throw ValidationException::withMessages([
                'email' => ['The Provided Credentails are incorrect and invalid.']
            ]);
        }

        // Compare the hash password 
        // if the 2 values dont match in the check() method this would return false.
        if(!Hash::check($request->password, $user->password)) // If the $request password dont match with the $user password, it will return false
        {
            throw ValidationException::withMessages([
                'email' => ['The Provided Credentails are incorrect and invalid.']
            ]);
        }

        // After those two checks, it is finalize to deal with the actual genuine user who has the account
        // Now we generate token
        $token = $user->createToken('api-token')->plainTextToken; //Since this is an object this will store in the database the api-token
        
        // You can find the api-token saved in the database to the personal_access_tokens table.
        return response()->json(
            [
                'token' => $token
            ]
        );
        // The token can be now used to authorize requests that require the user to be authenticated. And now how can we protect routes. How can we enforce that any given route that we want the user has to be authenticated to access that route. 
    }

    // Revoke all the tokens
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
