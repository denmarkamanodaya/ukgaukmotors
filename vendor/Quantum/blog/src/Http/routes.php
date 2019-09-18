<?php

Route::group(['namespace' => 'Quantum\blog\Http\Controllers', 'middleware' => ['web']], function()
{

//Member Routes
    Route::group(['namespace' => 'Members', 'middleware' => ['auth']], function()
    {
            Route::get('members/posts', ['as' => 'members_posts', 'uses' => 'Posts@index']);
            Route::get('members/post/{any}', ['as' => 'members_post_show', 'uses' => 'Posts@show'])->where('any', '.*');
            Route::get('members/category/{id}/posts', ['as' => 'members_post_show', 'uses' => 'Posts@showCategory']);
            Route::get('members/tag/{id}/posts', ['as' => 'members_post_show', 'uses' => 'Posts@showTag']);
            Route::post('members/posts/search', ['as' => 'members_posts_search', 'uses' => 'Posts@search']);
            Route::get('members/posts/search', ['as' => 'members_posts_search', 'uses' => 'Posts@search']);
            Route::get('members/posts/tags', ['as' => 'members_post_show_tags', 'uses' => 'Posts@showTags']);
            Route::get('members/posts/categories', ['as' => 'members_post_show_ctegories', 'uses' => 'Posts@showCategories']);


    });

//Admin Routes
    Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function()
    {
        Route::get('admin/posts', ['as' => 'admin_posts', 'uses' => 'Posts@index']);
        Route::get('admin/posts/data', ['as' => 'admin_posts_data', 'uses' => 'Posts@data']);
        Route::get('admin/post/create', ['as' => 'admin_posts_create', 'uses' => 'Posts@create']);
        Route::post('admin/post/create', ['as' => 'admin_posts_store', 'uses' => 'Posts@store']);
        Route::get('admin/post/{id}', ['as' => 'admin_post_edit', 'uses' => 'Posts@edit']);
        Route::post('admin/post/{id}/update', ['as' => 'admin_post_update', 'uses' => 'Posts@update']);
        Route::post('admin/post/{id}/delete', ['as' => 'admin_post_delete', 'uses' => 'Posts@destroy']);
        Route::get('admin/post/{id}/revision/{revision}', ['as' => 'admin_post_show_revision', 'uses' => 'Posts@showRevision']);
        Route::get('admin/post/createFromDraft/{revision}', ['as' => 'admin_post_show_createFromDraft', 'uses' => 'Posts@createFromDraft']);

        Route::post('admin/post/{id}/update/autoSave', ['as' => 'admin_post_update', 'uses' => 'Posts@autoSave']);
        Route::post('admin/post/create/autoSave', ['as' => 'admin_post_update', 'uses' => 'Posts@autoSave']);

        Route::get('admin/blog/settings', ['as' => 'admin_post_settings', 'uses' => 'Settings@index']);
        Route::post('admin/blog/settings', ['as' => 'admin_post_settings_update', 'uses' => 'Settings@update']);


    });

//public routes
    Route::group(['namespace' => 'Frontend'], function()
    {
            Route::get('posts', ['as' => 'public_posts', 'uses' => 'Posts@index']);
            Route::get('post/{any}', ['as' => 'public_post_show', 'uses' => 'Posts@show'])->where('any', '.*');
        Route::get('category/{id}/posts', ['as' => 'public_post_show', 'uses' => 'Posts@showCategory']);
        Route::get('tag/{id}/posts', ['as' => 'public_post_show', 'uses' => 'Posts@showTag']);

        Route::get('posts/tags', ['as' => 'public_post_show_tags', 'uses' => 'Posts@showTags']);
        Route::get('posts/categories', ['as' => 'public_post_show_categories', 'uses' => 'Posts@showCategories']);

    });

});
