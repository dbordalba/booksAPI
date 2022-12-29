<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 

class LoginController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $data = $request->all();

        $user = User::where('email', $data['email'])->first();
        if ($user) {
            if (Hash::check($data['password'], $user->password)) {
                
                $accessToken = $user->createToken('authToken')->accessToken;
                $user->access_token = $accessToken;

                Auth::logoutOtherDevices($user->password);
                
                return response()->json(['user' => $user], 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 200);
            }
                
        } else {
            return response()->json(['message' => "User do not exist."], 200);
        }
    }

    public function logout(){ 
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
        return response()->json(['success'=>true], 200); 
    }
}
