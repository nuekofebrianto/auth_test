<?php

namespace Add\Controllers;

use Illuminate\Http\Request;
use Add\Models\User;
use Illuminate\Support\Facades\Validator;
use Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 120 * 60
        ]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|string|max:20|min:6|unique:users,username',
                'role' => 'required',
            ],
            [
                'username.required' => 'username tidak boleh kosong',
                'username.max' => 'username maksimal 20 karakter',
                'username.min' => 'username minimal 6 karakter',
                'username.unique' => 'username sudah digunakan',
                'role.required' => 'role sudah digunakan',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        //random password
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $new_password = implode($pass);
        //end random password

        $user = User::create([
            'username' => $request->username,
            'role' => $request->role,
            'password' => bcrypt($new_password),
        ]);

        $username = $user['username'];
        $role = $user['role'];
        $password = $new_password;

        return response()->json(compact('username', 'role', 'password'), 200);
    }
    public function login(Request $request)
    {

        $credentials = request(['username', 'password']);
        if (!$token = auth()->setTTL(60)->attempt($credentials)) {
            return  response()->json([
                'errors' => [
                    'msg' => ['Incorrect username or password.']
                ]
            ], 401);
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Logged in.',
            'token' => $token
        ]);
    }

    public function validateToken()
    {
        try {

            if (!$user = auth()->parseToken()) {
                return response()->json(['user_not_found'], 404);
            }

            date_default_timezone_set("Asia/Bangkok");

            $is_valid       = true;
            $expire_at      = date("Y-m-d", auth()->getPayload()->get('exp'));
            $users          = auth()->authenticate();
            $username       = $users['username'];
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['is_valid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('is_valid', 'expire_at', 'username'), 200);


    }
}
