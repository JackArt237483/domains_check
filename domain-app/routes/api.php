<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DomainCheckController;

Route::middleware('auth:sanctum')->get('/user', function () {
    return Auth::user();
});

Route::middleware('auth:sanctum')->get('/domains/check', [DomainCheckController::class, 'check']);

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('frontend')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
});
