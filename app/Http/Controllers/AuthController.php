<?php
/**
 * Created By: Praditha Hidayat
 * Date: 06/07/2017
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use JWTAuth;

class AuthController extends ApiBaseController
{
    /**
     * Check user authentication and send the token if success
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
    	// grab credentials from the request
        $credentials = $request->only('email', 'password');

        // attempt to verify the credentials and create a token for the user
        if (! $token = JWTAuth::attempt($credentials)) {
            return $this->sendError('Invalid email or password', null, 401);
        }

        // all good so return the token
        return $this->sendResponse('Login Successfull', null, $token);
    }
}
