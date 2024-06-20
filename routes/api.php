<?php

use App\Http\Controllers\API\ApiMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/member/get-data', [ApiMemberController::class, 'index']);
