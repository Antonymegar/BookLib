<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\error;

class UsersController extends Controller
{
    /**
     * Display a listing of all the users.
     */
    public function index()
    {
     try{
         $users = User::all();

         //check whether the user records is null
         if($users == null){
             return response()->json(['message' => 'no record found'],200);
         }else{
             return response()->json($users);
         }
     }catch (\Exception $exception){
         return response()->json(['message' => 'error, while retrieving users'],200);
     }

    }

    /**
     * register users
     */
    public function registerUsers(Request $request)
    {

      try{
          //validation of input
          $validated = $request->validate([
              'name' => 'required',
              'email_address' => 'nullable',
              'email' => 'required|email|unique:users',
              'password' => 'required',
//            'role' => 'required|in:admin,user',
          ]);
          $user = new User();
          $user->name = $validated['name'];
          $user->email = $validated['email'];
          $user->password = Hash::make($validated['password']);
          $user->role = 'user';

          //check if email is set
          if (isset($validated['email_address'])) {
              $user->email_address = $validated['email_address'];
          }

          $user->save();

          return response()->json(['message' => 'user added']);
      } catch (\Exception $exception) {
          Log::error($exception);
          return response()->json(['message' => 'error occurred trying to add user', 'error' => $exception->getMessage()], 500);
      }
    }
    public function  login(Request $request){
        try{
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');

            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::guard('api')->user();
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }catch (\Exception $exception){
            return response()->json(['message' => 'error during login','error' => $exception->getMessage() ]);
        }

    }


}
