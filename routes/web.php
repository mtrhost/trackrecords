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

//Route::get('db/convert-games-date', 'DatabaseController@convertGameDates');
//Route::get('db/add-active-roles-to-winners', 'DatabaseController@addActiveRolesToGameWinners');
//Route::get('players/list', 'PlayerController@playersList')->name('player.list');

Route::get('storage/players/{player}/{image}', function ($player, $image)
{
    $path = \Storage::disk('public')->path('/players/' . $player . '/' . $image);
    
    if (!File::exists($path)) {
        abort(404);
    }
    
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
//Auth::routes();
//Route::get('login', [ 'as' => 'login', 'uses' => 'LoginController@authorize'])->name('auth.login');
$this->get('login', 'Auth\LoginController@index')->name('login');
$this->post('login', 'Auth\LoginController@process');
Route::get('/logout', function(){
    \Auth::logout();
    return Redirect::to('/');
 });

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'AdminController@index')->name('admin.index');
    Route::get('roles/add', 'AdminController@roles')->name('admin.roles');
    Route::post('roles/save', 'AdminController@saveRoles')->name('admin.roles.save');
    Route::get('players/add', 'AdminController@players')->name('admin.players');
    Route::post('players/save', 'AdminController@savePlayers')->name('admin.players.save');
    Route::get('setting/add', 'AdminController@setting')->name('admin.setting');
    Route::post('setting/save', 'AdminController@saveSetting')->name('admin.setting.save');
    Route::get('game/add', 'AdminController@game')->name('admin.gane');
    Route::post('game/save', 'AdminController@saveGame')->name('admin.game.save');
});

/*Route::get('/{vue_capture?}', function () {
    return view('templates.main');
})->where('vue_capture', '[\/\w\.-]*');*/
$this->get('/', 'IndexController@index')->name('index');
Route::group(['prefix' => 'players'], function () {
    Route::get('/', 'PlayerController@index')->name('player.index');
    Route::get('statistics', 'PlayerController@statistics')->name('player.statistics');
    Route::get('{id}', 'PlayerController@details')->name('player.details');
    Route::post('get-last-activity', 'PlayerController@getLastActivity')->name('player.getLastActivity');
    Route::post('list', 'PlayerController@playersList')->name('player.list');
    Route::post('show', 'PlayerController@show')->name('player.show');
    Route::post('parse', 'PlayerController@parsePlayerProfile')->name('player.parsePlayerProfile');
});

Route::group(['prefix' => 'games'], function () {
    Route::get('/', 'GameController@index')->name('game.index');
    Route::get('{id}', 'GameController@details')->name('game.details');
    Route::post('list', 'GameController@list')->name('game.list');
    Route::post('show', 'GameController@show')->name('game.show');
});

Route::group(['prefix' => 'settings'], function () {
    Route::get('/', 'SettingController@index')->name('setting.index');
    Route::get('{id}', 'SettingController@details')->name('setting.details');
    Route::post('list', 'SettingController@list')->name('setting.list');
    Route::post('show', 'SettingController@show')->name('setting.show');
});

Route::group(['prefix' => 'achievements'], function () {
    Route::get('/', 'AchievementController@index')->name('achievements.index');
});