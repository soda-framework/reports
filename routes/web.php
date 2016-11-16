<?php

Route::group([
    'prefix' => config('soda.cms.path') . '/reports' ,
    'middleware' => [
        'soda.main',
        'soda.auth',
        'soda.permission:view-reports'
    ]
], function () {
    Route::get('/{reportId?}', 'ReportController@index')->name('soda.reports.index');
    Route::get('view/{id}', 'ReportController@view')->name('soda.reports.view');
    Route::get('export/{id}', 'ReportController@export')->name('soda.reports.export');
});
