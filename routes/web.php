<?php

use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', 'AuthController@form')->name('login');
Route::post('login', 'AuthController@authenticate')->name('authenticate');
Route::get('logout', 'AuthController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function()
{
    Route::get('/', function()
    {
        return view('app');
    });
    Route::get('/{filename}', function($filename)
    {
        //$disk = Storage::disk('local');

        $path = storage_path("app/public/$filename");

        //return $disk->get( $path );

        //return response()->download( 'public', $filename );

        return response()->file( $path );
    });
});


