<?php

use App\Http\Controllers\LoginController;
use App\Models\login;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'prefix' => '',
    'as' => 'api.',
    'middleware' => ['auth:api']
], function () {
    //lists all users
    // Route::get('/all-users', 'Api\V1\ApiController@allUsers')->name('all-user');

    // fetch featured products for the homepage
    Route::get('all-users', function() {
        $users = User::all();

        return response()->json($users);
    });


});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});