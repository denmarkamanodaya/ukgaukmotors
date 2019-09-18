<?php

Route::group(['namespace' => 'Quantum\base\Http\Controllers', 'middleware' => ['web']], function()
{

    //Member Routes
    Route::group(['namespace' => 'Members', 'middleware' => ['auth']], function()
    {
        //Page
        Route::get('members/{id?}', ['as' => 'members_page', 'uses' => 'Page@show']);
        
    });
    
    //Admin Routes
    Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function()
    {
    
        //Pages
        Route::get('admin/pages/{area?}', ['as' => 'admin_pages', 'uses' => 'Pages@index']);
        Route::post('admin/pages', ['as' => 'admin_pages_area', 'uses' => 'Pages@showArea']);
        Route::get('admin/page/create', ['as' => 'admin_pages_create', 'uses' => 'Pages@create']);
        Route::post('admin/page/create', ['as' => 'admin_pages_store', 'uses' => 'Pages@store']);
        Route::get('admin/page/{id}/edit', ['as' => 'admin_pages_edit', 'uses' => 'Pages@edit']);
        Route::post('admin/page/{id}/update', ['as' => 'admin_pages_update', 'uses' => 'Pages@update']);
        Route::get('admin/page/{id}/delete', ['as' => 'admin_page_delete', 'uses' => 'Pages@destroy']);
        Route::get('admin/page/{id}/revision/{revision}', ['as' => 'admin_page_show_revision', 'uses' => 'Pages@showRevision']);

        Route::get('admin/{id}', ['as' => 'admin_page', 'uses' => 'Page@show']);
        
    });
    
    //public routes
    
    Route::group(['namespace' => 'Frontend'], function()
    {
        //Page
        Route::get('{id}', ['as' => 'public_page', 'uses' => 'Page@show']);
        Route::get('/', ['as' => 'public_page_index', 'uses' => 'Page@index']);
    });

});