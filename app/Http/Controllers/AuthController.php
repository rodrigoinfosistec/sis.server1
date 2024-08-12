<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

// 5|0nrZUQkTJ6J9vt9Wp8LHSFJRSsqdtqo3MZ0ezsUCeb7b8218

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))):
            return $this->response('Authorized', 200, [
                'token' => $request->user()->createToken('user')->plainTextToken
            ]);
        endif;

        return $this->response('Not Authorized', 403);
    }

    public function logout()
    {

    }
}
