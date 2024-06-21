<?php

use App\Http\Controllers\API\ApiMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/member/get-data', [ApiMemberController::class, 'index']);
Route::get('/members/show/{id}', [ApiMemberController::class, 'show']);
Route::post('/member/store', [ApiMemberController::class, 'store']);
Route::patch('/update/{id}', [ApiMemberController::class, 'update']);
Route::delete('/member/delete/{id}', [ApiMemberController::class, 'destroy']);
