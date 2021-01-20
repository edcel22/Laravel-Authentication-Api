<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\ForgotRequest;

class ForgotController extends Controller
{
    //
    public function forgot (ForgotRequest $request) {
    	$email = $request->input('email');

    	if (User::where('email', $email)->doesntExist()) {
    		return response([
    			'message' => 'User doesn\'t exists!'
    		], 400);
    	}

    	//will generate a token which will be a random string
    	// we will restore his token in the restore token table
    	$token = Str::random(10);

    	try {
	    	DB::table('password_resets')->insert([
	    		'email' => $email,
	    		'token' => $token
	    	]);

	    	//send email

	    	return response([
	    		'message' => 'Check your email!'
	    	]);

    	} catch (\Exception $exception) {
			return response ([
				'message' => $exception->getMessage()
			], 400);    		
    	}

    }
}
