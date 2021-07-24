<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegistrationFormRequest;
use Exception;
use Illuminate\Validation\Validator as ValidationValidator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['login', 'register', 'logout']]);
    }
    public function register (Request $request) {

        $validate = Validator::make($request->only(['name','email', 'password']),[
            'email' => 'required|email|string|unique:users',
            'name' => 'required|string',
            'password' => 'required|string|min:5'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->messages()
            ],400);
        }
        $request->password = bcrypt($request->password);
        $user = User::create($request->all());

        try {
            return response()->json([
                'success' => true,
                'message' => 'user not registred successfully',
                'user' => $user
            ],201);
        }catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'user not registred successfully'
            ],400);
        }
    }
    public function login (Request $request) {

        $validate = Validator::make($request->only(['email', 'password']),[
            'email' => 'required|email|string',
            'password' => 'required|string|min:5'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->messages()
            ],400);
        }
        if (!$token = JWTAuth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ],401);
        }
        return response()->json([
            'success' => true,
            'token' => $token
        ],200);

    }
    public function logout (Request $request) {

        // $validator = Validator::make($request->only(['token']),[
        //     'token' => 'required'
        // ]);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'errors' => $validator->errors()->messages()
        //     ]);
        // }
        try {

            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'User logged out succesfully'
            ],200);

        }catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User cannot be logged out'
            ],500);
        }
    }
    public function profil() {
       
       if(!auth()->check()) {
           return response()->json([
               'message' => 'unaithorized'
           ],401);
       }
       return response()->json([
           'user' => auth()->user()
       ]);
    }
}
