<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function sendMessage(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $request->all()
        ]);
    }
}
