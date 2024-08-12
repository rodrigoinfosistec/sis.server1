<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

// 8|BaM198HbllRoZtUSY5XYJQQSBSMhgS9ZpFQ4hdLB02e36759

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))):
            return $this->response('Authorized', 200, [
                'token' => $request->user()->createToken('user', ['user-index', 'user-store', 'user-update', 'user-show', 'user-destroy'])->plainTextToken
            ]);
        endif;

        return $this->response('Not Authorized', 403);
    }

    public function logout()
    {

    }
}
