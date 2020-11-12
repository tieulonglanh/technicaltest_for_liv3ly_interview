<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'data' => []
            ], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $accessToken = $user->createToken('Laravel Personal Access Client')->accessToken;

        return response()->json([
            'message' => 'Registered successfully!',
            'accessToken' => $accessToken,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'data' => []
            ], 422);
        }

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Login Fail!',
                'data' => []
            ], 401);
        }

        $user = $request->user();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'message' => 'Login successfully!',
            'accessToken' => $accessToken,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $data = [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email
        ];

        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
            'data' => $data
        ]);
    }
}
