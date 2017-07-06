<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    const _OK = "OK";
    const _ERROR = "ERROR";

    public function sendResponse($message, $data = null, $accessToken = null) {
        $response = [
            'status' => self::_OK,
            'message' => $message
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        if (!empty($accessToken)) {
            $response['token'] = 'Bearer ' . $accessToken;
        }

        return response()->json($response, 200);
    }

    public function sendError($message) {
        return response()->json([
            'status' => self::_ERROR,
            'message' => $message
        ], 500);
    }
}
