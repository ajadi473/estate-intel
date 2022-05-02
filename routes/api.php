<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TradesControllerController;
use App\Models\Books;
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
        $books = Books::all();

        return response()->json($books);
    });

    

});

Route::group([
    'prefix' => 'v1/books',
    'as' => 'api.',
], function () {

    Route::get('', [BooksController::class, 'fetch_all_books']);
    Route::post('/create', [BooksController::class, 'create_book']);
    Route::patch('/update/{id}', [BooksController::class, 'update_book']);
    Route::delete('/delete/{id}', [BooksController::class, 'destroy_book']);
    Route::get('{id}', [BooksController::class, 'show_book']);
});

Route::group([
    'prefix' => 'v1/external-books',
    'as' => 'api.',
], function () {

    Route::get('/all', [BooksController::class, 'index']);

});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});