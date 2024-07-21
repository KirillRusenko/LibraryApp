<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ClickHouseLoggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use ClickHouseLoggable;

    public function index()
    {
        $this->logToClickHouse('user_index');
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $this->logToClickHouse('user_show', ['user_id' => $id]);
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'password' => 'string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('user_update_failed', ['user_id' => $id, 'errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $user->update($request->except('password'));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $this->logToClickHouse('user_update_success', ['user_id' => $id]);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $this->logToClickHouse('user_delete', ['user_id' => $id]);
        return response()->json(null, 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $this->logToClickHouse('user_register_failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Str::random(15);

        $this->logToClickHouse('user_register_success', ['user_id' => $user->id]);
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
//
//    public function login(Request $request)
//    {
//        if (!Auth::attempt($request->only('email', 'password'))) {
//            return response()->json(['message' => 'Unauthorized'], 401);
//        }
//
//        $user = User::where('email', $request->email)->firstOrFail();
//        $token = $user->createToken('auth_token')->plainTextToken;
//
//        return response()->json([
//            'user' => $user,
//            'access_token' => $token,
//            'token_type' => 'Bearer',
//        ]);
//    }
//
//    public function logout(Request $request)
//    {
//        $request->user()->currentAccessToken()->delete();
//        return response()->json(['message' => 'Logged out successfully']);
//    }
}
