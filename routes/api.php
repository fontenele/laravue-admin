Route::group(['middleware' => ['json.response']], function () {
    Route::middleware('auth:api')->get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });

    Route::post('/login', 'App\Http\Controllers\Api\AuthController@login')->name('login.api');
    Route::post('/register', 'App\Http\Controllers\Api\AuthController@register')->name('register.api');

    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'App\Http\Controllers\Api\AuthController@logout')->name('logout');
        Route::resource('users', 'App\Http\Controllers\Admin\UsersController');
    });
});
