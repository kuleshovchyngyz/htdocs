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

Route::get('/', function () {
    return view('welcome');
})->name('main');



Route::get('ready', 'ScheduleController@ddd');
Route::get('/cvs', function () {

    $row = 1;
    if (($handle = fopen(public_path("export.csv"), "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // $data = array_map("mb_convert_encoding", $data);
            //$data = array_map("utf8_encode", $data);
            $num = count($data);
            echo "<p> $num полей в строке $row: <br /></p>\n";
            $row++;
            for ($c = 0; $c < $num; $c++) {
                //$reportSubtitle = iconv('UTF-8', 'cp1251', $data[$c]);
                // $str = iconv('UTF-8', 'Windows-1252', $data[$c]);
                $str = iconv('Windows-1251', 'UTF-8', $data[$c]);
                $arr = explode(';', $str);
                dump($arr);
                echo $str . "<br />\n";
            }
        }
        fclose($handle);
    }
});


Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');



Route::get('/updateapp', function () {
    \Artisan::call('composer dump-autoload');
    echo 'dump-autoload complete';
});

Route::group(['prefix' => 'project', 'middleware' => ['auth']], function () {

    Route::get('/', 'ProjectController@index')->name('project.index');
    Route::get('create', 'ProjectController@create')->name('project.create');


    Route::get('position/{project}', 'ProjectController@position')->name('project.position');

    Route::get('select/{project}', 'ProjectController@select')->name('project.select');
    Route::post('store', 'ProjectController@store')->name('project.store');
    Route::get('archive/{project}', 'ProjectController@archive')->name('project.archive');
    Route::get('edit/{project}', 'ProjectController@edit')->name('project.edit');
    Route::put('update/{project}', 'ProjectController@update')->name('project.update');
    Route::get('destroy/{project}', 'ProjectController@destroy')->name('project.destroy');
    Route::get('multyplydestroy', 'ProjectController@multyplydestroy')->name('project.multyplydestroy');
    Route::get('multyplyarchive', 'ProjectController@multyplyarchive')->name('project.multyplyarchive');

    Route::group(['prefix' => 'summary', 'middleware' => ['auth']], function () {
        Route::get('/{project?}', 'RegionalSummary@index')->name('project.summary'); //->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'brief', 'middleware' => ['auth']], function () {
        Route::get('/{project}', 'SummaryController@index')->name('project.brief');
        Route::post('store', 'SummaryController@store')->name('project.brief.store');
    });

    Route::group(['prefix' => 'schedule', 'middleware' => ['auth']], function () {
        Route::get('/{id}', 'ScheduleController@index')->name('project.schedule')->where('id', '[0-9]+');
        Route::post('/schedule-all-positions', 'ScheduleController@schedule');
        Route::post('/edit_schedule-all-positions', 'ScheduleController@schedule');
        Route::get('/create', 'ScheduleController@create')->name('project.schedule.create');
        Route::get('/test', 'ScheduleController@testing');
        Route::get('/edit/{task}', 'ScheduleController@edit')->name('project.schedule.edit');
        Route::get('/delete/{task}', 'ScheduleController@destroy')->name('project.schedule.delete');
        //Route::post('/date_to_database', 'ScheduleController@store')->name('project.schedule.store');
    });

    Route::group(['prefix' => 'region', 'middleware' => ['auth']], function () {
        Route::get('/{id}', 'ProjectRegionController@index')->name('project.region.index');
        Route::get('create', 'ProjectRegionController@create')->name('project.region.create');
        Route::post('store', 'ProjectRegionController@store')->name('project.region.store');
        Route::get('edit/{projectRegion}', 'ProjectRegionController@edit')->name('project.region.edit');
        Route::post('update/{projectRegion}', 'ProjectRegionController@update')->name('project.region.update');
        Route::get('archive/{projectRegion}', 'ProjectRegionController@archive')->name('project.region.archive');
        Route::get('destroy/{projectRegion}', 'ProjectRegionController@destroy')->name('project.region.destroy');
    });

    Route::group(['prefix' => 'competitor', 'middleware' => ['auth']], function () {
        Route::get('/{id?}', 'CompetitorController@index')->name('project.competitor.index');
        Route::get('create', 'CompetitorController@create')->name('project.competitor.create');
        Route::post('store', 'CompetitorController@store')->name('project.competitor.store');
        Route::get('edit/{competitor}', 'CompetitorController@edit')->name('project.competitor.edit');
        Route::post('update/{competitor}', 'CompetitorController@update')->name('project.competitor.update');
        Route::get('destroy/{competitor}', 'CompetitorController@destroy')->name('project.competitor.destroy');
        Route::get('archive/{competitor}', 'CompetitorController@archive')->name('project.competitor.archive');
    });

    Route::get('/reports/{id?}', 'ReportController@index')->name('project.reports');
    Route::get('/settings/{id?}', 'SettingController@index')->name('project.settings');
});

Route::group(['prefix' => 'query-group', 'middleware' => ['auth']], function () {
    Route::get('/{project}', 'QueryGroupController@index')->name('query-group.index');
    Route::get('create/{parent_group_id?}', 'QueryGroupController@create')->name('query-group.create');
    Route::post('store', 'QueryGroupController@store')->name('query-group.store');
    Route::get('edit/{queryGroup}', 'QueryGroupController@edit')->name('query-group.edit');
    Route::post('ajax-rename/{queryGroup}', 'QueryGroupController@ajaxRename')->name('query-group.ajax-rename');
    Route::post('update/{queryGroup}', 'QueryGroupController@update')->name('query-group.update');
    Route::post('addtarget/{queryGroup}', 'QueryGroupController@addtarget')->name('query-group.addtarget');
    Route::get('destroy/{queryGroup}', 'QueryGroupController@destroy')->name('query-group.destroy');
    Route::get('archive/{queryGroup}', 'QueryGroupController@archive')->name('query-group.archive');
});

Route::group(['prefix' => 'query', 'middleware' => ['auth']], function () {
    Route::get('/', 'QueryController@index')->name('query.index');
    Route::get('/list/{query_group_id?}', 'QueryController@list')->name('query.list');
    Route::get('create/{query_group_id?}', 'QueryController@create')->name('query.create');
    Route::post('store', 'QueryController@store')->name('query.store');
    Route::get('edit/{query}', 'QueryController@edit')->name('query.edit');
    Route::post('store-update', 'QueryController@storeUpdate')->name('query.store-update');
    Route::post('mass-assign', 'QueryController@massAssign')->name('query.mass-assign');
    Route::post('assign-region', 'QueryController@assignRegion')->name('query.assign-region');
    Route::post('update/{query}', 'QueryController@update')->name('query.update');
    Route::get('archive', 'QueryController@archive')->name('query.archive');
    Route::get('destroy', 'QueryController@destroy')->name('query.destroy');
    Route::post('import', 'QueryController@fileUploadPost')->name('query.import');
});

Route::group(['prefix' => 'region', 'middleware' => ['auth']], function () {
    Route::get('/', 'RegionController@index')->name('region.index');
    Route::get('create', 'RegionController@create')->name('region.create');
    Route::post('store', 'RegionController@store')->name('region.store');
    Route::get('edit/{region}', 'RegionController@edit')->name('region.edit');
    Route::post('update/{region}', 'RegionController@update')->name('region.update');
    Route::get('destroy/{region}', 'RegionController@destroy')->name('region.destroy');
});

Route::group(['prefix' => 'client', 'middleware' => ['auth']], function () {
    Route::get('/', 'ClientController@index')->name('client.index');
    Route::get('create', 'ClientController@create')->name('client.create');
    Route::get('edit/{client}', 'ClientController@edit')->name('client.edit');
    Route::get('projects', 'ClientController@set_project')->name('client.set');
    Route::get('destroy/{client}', 'ClientController@destroy')->name('client.delete');
});

Route::group(['prefix' => 'position', 'middleware' => ['auth']], function () {
    Route::get('/', 'PositionController@index')->name('position.index');
    Route::get('create', 'PositionController@create')->name('position.create');
    //    Route::post('get-position', 'PositionController@getPosition')->name('position.get-position');
    Route::get('get-all-positions', 'PositionController@getAllPositions')->name('position.get-all-positions');
    Route::post('store', 'PositionController@store')->name('position.store');
    Route::get('edit/{position}', 'PositionController@edit')->name('position.edit');
    Route::post('update/{position}', 'PositionController@update')->name('position.update');
    Route::get('destroy/{position}', 'PositionController@destroy')->name('position.destroy');
});
