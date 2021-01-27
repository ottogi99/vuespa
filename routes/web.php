<?php

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

use Illuminate\Http\Request;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    $query = http_build_query([
        'client_id' => '3',
        'redirect_url' => 'http://vuespanew.test/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://vuespanew.test/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    // 사용자 로그인을 한 뒤 -> accessToken이 발급되는 구조로, 이 경우에는  
    // \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class, 를 사용하면 별도로 http 해더에 넣지 않아도 인증되지만,
    $response = $http->post('http://vuespanew.test/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '3',
            'client_secret' => 'eYnCnkJBwvuKBfxTtIIDEmGrMk7D7TkBo7fRSMAc',
            'redirect_url' => 'http://vuespanew.test/callback',
            'code' => $request->code,
        ],
    ]);

    // 패스워드 그랜트를 사용한 경우에는, 수신된 accessToken을 개발자가 직접 헤더에 넣는 처리를 해주어야 한다.
    // $response = $http->post('http://vuespanew.test/oauth/token', [
    //     'form_params' => [
    //         'grant_type' => 'password',
    //         'client_id' => '2',    # DB에 name이 Laravel_Passport Password Grant Client 인 id값
    //         'client_secret' => 'zeB77duDQkG9MovOYg6gxzGtnCxg1cG3eKehWC2W', # DB에 client_id 2의 secret 코드값
    //         'username' => 'sunghwa@onthesys.com',
    //         'password' => 'wjdtjdghk1',
    //         'scope' => '*',
    //     ],
    // ]);

    return json_decode((string) $response->getBody(), true);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
