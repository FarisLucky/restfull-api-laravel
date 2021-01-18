<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => env('PASSPORT_CLIENT_ID'),
        'redirect_uri' => 'http://localhost:8000/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);
    return redirect('http://localhost:8000/oauth/authorize?'.$query);
});

Route::get('/callback', function (\Illuminate\Http\Request $request) {
    $http = new GuzzleHttp\Client;
    $response = $http->post('http://localhost:8000/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => env('PASSPORT_CLIENT'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'redirect_uri' => 'http://localhost:8000/callback',
            'code' => $request->code
        ]
    ]);
    $tokens = json_decode((string) $response->getBody(), true);
    dd($tokens);
    $user = fetchUser($tokens['access_token'], $http);
    dd($user);
    return view('authenticated', array_merge($tokens, $user));
});

function fetchUser($accessToken, $http) {
    $response = $http->get('http://localhost:8000/api/v1/authors', [
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $accessToken,
        ]
    ]);

    return json_decode((string) $response->getBody(), true);
}

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
