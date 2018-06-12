<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function sendResponse($data, $message)
    {
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => $message
        ];
        return response()->json($response, JsonResponse::HTTP_OK);
    }

    public function sendError($message, $status = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        return response()->json($response, $status ?? JsonResponse::HTTP_OK);
    }
}
