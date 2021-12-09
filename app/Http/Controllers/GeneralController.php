<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Auth;
use GrahamCampbell\GitHub\Facades\GitHub;

class GeneralController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function validateToken(Request $request)
    {
        return new UserResource($request->user());
    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 201);
    }

    public function githubToken(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'githubToken' => 'required',
        ]);

        try{
            $user = User::find($id);

                $user = User::updateOrCreate([
                    'githubToken' => $request->githubToken,
                    'token_verify' => "encrypted",
                ]);


        }catch (\Exception $exception)
        {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error' => false, 'message' => 'Token Encrypted', 'data' => $user], 200);
    }



    public function getUserToken($email)
    {

        try{
            // $users = $client->api('id', Auth::user())->starring()->all();

            $user = User::where('id', Auth::user())->where('token_verify', 'encrypted')->first();

            $userNull = User::where('id', Auth::user())->where('token_verify', NULL)->first();

            if($user){
                return response()->json(['error' => true, 'message' => 'Token Decrypted'], 400);
            }

            if(is_null($userNull)){

                return response()->json(['error' => true, 'message' => 'Git Token not Exist'], 400);
            }

        }catch (\Exception $exception)
        {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error' => false, 'message' => 'Token Decrypted', 'data' => $user], 200);
    }




}
