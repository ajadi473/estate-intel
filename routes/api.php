<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\TradesControllerController;
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
    Route::get('all-books', function() {
        $users = User::all();

        return response()->json($users);
    });

    

});

Route::group([
    'prefix' => 'v1/offer',
    'as' => 'api.',
], function () {

    Route::post('/all', [TradesControllerController::class, 'fetch_offers']);
    Route::post('', [TradesControllerController::class, 'fetch_an_offer']);
    Route::post('/create', [TradesControllerController::class, 'create_an_offer']);
    Route::post('/created/list', [TradesControllerController::class, 'list_created_offers']);
});

Route::group([
    'prefix' => 'v1/trade',
    'as' => 'api.',
], function () {

    Route::get('/all', [TradesControllerController::class, 'index']);
    Route::post('/get', [TradesControllerController::class, 'fetch_a_trade']);
    Route::post('/start', [TradesControllerController::class, 'start_trade']);
    Route::post('/completed', [TradesControllerController::class, 'fetch_completed_trade']);

});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});