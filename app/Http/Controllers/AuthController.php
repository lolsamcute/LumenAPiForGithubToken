<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try{
            $user = User::where('email', $validated['email'])->first();
            if(is_null($user)){
                return response()->json(['error' => true, 'message' => 'Incorrect Email'], 400);
            }
            if(!Hash::check($validated['password'], $user->password)){
                return response()->json(['error' => true, 'message' => 'Incorrect Password'], 400);
            }
        }catch (\Exception $exception)
        {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
        $data['user'] =  $user;
        $data['token'] =  $user->createToken('GITHUBTOKEN')->accessToken;
        return response()->json(['error' => false, 'message' => 'login successful', 'data' => $data], 200);
    }




    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);

        try{

            $checkEmail = User::where('email', $request->email)->first();



             if($checkEmail)
             {

                 $message = 'Email Existing In our System';
                 $data = null;
                 return response()->json(['error' => false, 'status_code' => 200, 'message' => $message], 200);
             }

                if(is_null($checkEmail))
                {
                    $userData = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request['password']),
                    ]);

                    /**Take note of this: Your user authentication access token is generated here **/
                    $data['token'] =  $userData->createToken('GITHUBTOKEN')->accessToken;
                    $data['name'] =  $userData->name;



                }

        }catch (\Exception $exception){
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error' => false, 'status_code' => 200, 'message' => 'Register Successfully', 'data' => $data ], 200);

    }



}
