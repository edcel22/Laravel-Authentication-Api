<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Hash;
use App\Http\Requests\RegisterRequest;
use Auth;

class AuthController extends Controller
{

    //Login using email and password
	public function login (Request $request) {
		try {
			if (Auth::attempt($request->only('email', 'password'))) {
				$user = Auth::user();
				//generate oauth token
				$token = $user->createToken('app')->accessToken;

				return response([
					'message' => 'success',
					'token' => $token,
					'user' => $user
				]);
			}
			//return if username or password is invalid
			return response([
				'message' => 'Invalid username/password'
			], 401);
		} catch (\Exception $exception) {
			return response([
				'message' => $exception->getMessage()
			], 400);
		}
	}

	/*get user data based on token*/
	public function user () {
		return Auth::user();
	}

	/*register user*/
	public function register (RegisterRequest $request) {

		try {
			$user = User::create([
				'first_name' => $request->input('first_name'),
				'last_name' => $request->input('last_name'),
				'email' => $request->input('email'),
				'password' => \Hash::make($request->input('password')),
			]);

			return $user;
		} catch (\Exception $exception) {
			return response([
				'message' => $exception->getMessage()
			], 400);
		}
	}
}
