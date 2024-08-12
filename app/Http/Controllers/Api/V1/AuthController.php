<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        $this->response('Authorized', 200);
    }

    public function logout()
    {

    }
}
