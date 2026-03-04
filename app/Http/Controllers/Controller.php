<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $result,
        ]);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errros'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
