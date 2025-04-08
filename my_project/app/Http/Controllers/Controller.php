<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // // Trả về JSON với format chung
    // protected function sendResponse($data, $message = "Success", $status = 200)
    // {
    //     return response()->json([
    //         'status' => $status,
    //         'message' => $message,
    //         'data' => $data
    //     ], $status);
    // }

    // // Trả về lỗi với format chung
    // protected function sendError($message, $status = 400)
    // {
    //     return response()->json([
    //         'status' => $status,
    //         'message' => $message,
    //         'data' => null
    //     ], $status);
    // }


}
