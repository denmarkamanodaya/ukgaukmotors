<?php
//Member Routes
Route::group(['namespace' => 'Members'], function()
{
    Route::get('members/auctioneers', ['as' => 'members_auctioneers', 'uses' => 'Dealers@index']);
    Route::post('members/auctioneers', ['as' => 'members_auctioneers', 'uses' => 'Dealers@search']);
    Route::get('members/auctioneer/{id}', ['as' => 'members_auctioneer_show', 'uses' => 'Dealers@show']);
    Route::get('members/auctioneer/{id}/vehicles', ['as' => 'members_auctioneer_show_vehicles', 'uses' => 'Dealers@showVehicles']);

    Route::get('members/vehicles', ['as' => 'members_vehicles', 'uses' => 'Vehicles@index']);
    Route::post('members/vehicles', ['as' => 'members_vehicles_filter', 'uses' => 'Vehicles@filter']);
    Route::get('members/vehicle/{id}', ['as' => 'members_vehicle_show', 'uses' => 'Vehicles@show']);
    Route::get('members/vehicle/{id}/shortlist', ['as' => 'members_vehicle_shortlist', 'uses' => 'Vehicles@shortlist']);
    Route::post('members/vehicle/{id}/shortlist', ['as' => 'members_vehicle_shortlist', 'uses' => 'Vehicles@shortlist']);

    Route::get('members/shortlist', ['as' => 'members_vehicle_shortlist_show', 'uses' => 'Vehicles@shortlistShow']);

    Route::get('members/car-data/{only?}', ['as' => 'members_carmake_index', 'uses' => 'CarData@index']);
    Route::post('members/car-data/search', ['as' => 'members_carmake_search', 'uses' => 'CarData@search']);
    Route::get('members/car-make/{id}', ['as' => 'members_carmake_show', 'uses' => 'CarData@show']);
    Route::get('members/car-make/{id}/{model}', ['as' => 'members_carmake_model_show', 'uses' => 'CarData@showModel']);
    Route::get('members/car-make/{id}/{model}/{variant}', ['as' => 'members_carmake_model_variant_show', 'uses' => 'CarData@showModelVariant']);

    Route::get('members/motorpedia/{only?}', ['as' => 'members_motorpedia_index', 'uses' => 'CarData@index']);
    Route::post('members/motorpedia/search', ['as' => 'members_motorpedia_search', 'uses' => 'CarData@search']);
    Route::get('members/motorpedia/car-make/{id}', ['as' => 'members_motorpedia_carmake_show', 'uses' => 'CarData@show']);
    Route::get('members/motorpedia/car-make/{id}/{model}', ['as' => 'members_motorpedia_carmake_model_show', 'uses' => 'CarData@showModel']);
    Route::get('members/motorpedia/car-make/{id}/{model}/{variant}', ['as' => 'members_motorpedia_carmake_model_variant_show', 'uses' => 'CarData@showModelVariant']);

    Route::post('members/ajax/vehicleModelSearch/{returnVar?}', ['as' => 'members_ajax_vehicleModelSearch', 'uses' => 'Ajax@vehicleModelSearch']);
    Route::post('members/ajax/vehicleModelSearchAll/{returnVar?}', ['as' => 'members_ajax_vehicleModelSearchAll', 'uses' => 'Ajax@vehicleModelSearchAll']);
    Route::post('members/ajax/vehicleLocations', ['as' => 'members_ajax_vehicleLocations', 'uses' => 'Ajax@vehicleLocations']);
    Route::get('members/ajax/vehicleModels/{make}', ['as' => 'members_ajax_vehicleModels', 'uses' => 'CarData@modelsData']);
    Route::post('members/ajax/getDealers', ['as' => 'members_ajax_getDealers', 'uses' => 'Ajax@getDealers']);

    Route::get('members/books', ['as' => 'members_books', 'uses' => 'Book@index']);
    Route::get('members/book/markAsRead/{book}', ['as' => 'members_books', 'uses' => 'Book@markAsRead']);
    Route::get('members/book/{book}', ['as' => 'members_books', 'uses' => 'Book@show']);
    Route::get('members/book/{book}/details', ['as' => 'members_book_details', 'uses' => 'Book@details']);
    Route::get('members/book/{book}/{chapter}', ['as' => 'members_books', 'uses' => 'Book@showChapter']);
    Route::get('members/book/{book}/{chapter}/{page}', ['as' => 'members_books', 'uses' => 'Book@showPage']);

    Route::get('members/mygarage', ['as' => 'members_mygarage', 'uses' => 'MyGarage@index']);

    Route::group(['middleware' => ['premium']], function()
    {
        Route::get('members/mygarage/shortlist', ['as' => 'members_mygarage_shortlist_show', 'uses' => 'MyGarage@shortlist']);
        Route::get('members/mygarage/feed', ['as' => 'members_mygarage_feed', 'uses' => 'MyGarage@feed']);
        Route::post('members/mygarage/feed/setTitle', ['as' => 'members_mygarage_feed_setTitle', 'uses' => 'MyGarage@setFeedTitle']);
        Route::get('members/mygarage/feed/removeFeed/{id}', ['as' => 'members_mygarage_feed_removeFeed', 'uses' => 'MyGarage@removeFeed']);
        Route::post('members/mygarage/feed/changePosition', ['as' => 'members_mygarage_feed_changePosition', 'uses' => 'MyGarage@changePosition']);
        Route::post('members/mygarage/feed/tourComplete', ['as' => 'members_mygarage_feed_tourComplete', 'uses' => 'MyGarage@tourComplete']);
        Route::post('members/mygarage/feed/add', ['as' => 'members_mygarage_feed_add', 'uses' => 'MyGarage@addFeed']);
        Route::post('members/mygarage/getFeed', ['as' => 'members_mygarage_feed_get', 'uses' => 'MyGarage@getFeed']);
        Route::post('members/mygarage/feed/addFeed', ['as' => 'members_mygarage_feed_addFeed', 'uses' => 'MyGarage@addAjaxFeed']);

        Route::get('members/mygarage/calendar', ['as' => 'members_mygarage_calendar', 'uses' => 'MyGarage@calendar']);
        Route::post('members/mygarage/calendar/getDaysEvents', ['as' => 'members_calendar_dayEvents', 'uses' => 'MyGarage@getDay']);
        Route::post('members/mygarage/calendar/getDaysEvents/monthly', ['as' => 'members_calendar_monthlyEvents', 'uses' => 'MyGarage@getMonth']);

    });

});

//Admin Routes
Route::group(['namespace' => 'Admin', 'middleware' => ['admin']], function()
{
    //Dashboard
    Route::get('admin/dashboard', ['as' => 'admin_dashboard', 'uses' => 'Dashboard@index']);
    Route::get('admin/dashboard/ajax/users', ['as' => 'admin_dashboard_ajax_users', 'uses' => 'Dashboard@userWidget']);
    Route::get('admin/dashboard/ajax/tickets', ['as' => 'admin_dashboard_ajax_tickets', 'uses' => 'Dashboard@ticketWidget']);
    Route::get('admin/dashboard/ajax/vehicleCountLog', ['as' => 'admin_dashboard_ajax_tickets', 'uses' => 'Dashboard@vehicleCountWidget']);
    Route::get('admin/dashboard/ajax/vehicleImportCount', ['as' => 'admin_dashboard_ajax_vehicleImportCount', 'uses' => 'Dashboard@vehicleImportAmountWidget']);
    Route::get('admin/dashboard/ajax/newsletterCount', ['as' => 'admin_dashboard_ajax_newsletterCount', 'uses' => 'Dashboard@newsletterWidget']);

    Route::get('admin/import', ['as' => 'admin_import', 'uses' => 'Import@import']);
    Route::get('admin/import/media', ['as' => 'admin_import_media', 'uses' => 'Import@media']);

    Route::get('admin/dealers/auctioneers', ['as' => 'admin_auctioneers', 'uses' => 'Auctioneers@index']);
    Route::get('admin/dealers/auctioneers/data', ['as' => 'admin_auctioneers_data', 'uses' => 'Auctioneers@data']);
    Route::get('admin/dealers/auctioneer/{id}', ['as' => 'admin_auctioneer_show', 'uses' => 'Auctioneers@show']);
    Route::get('admin/dealers/auctioneer/{id}/vehicles', ['as' => 'admin_auctioneer_vehicles', 'uses' => 'Auctioneers@vehicles']);
    Route::get('admin/dealers/auctioneer/{id}/edit', ['as' => 'admin_auctioneer_edit', 'uses' => 'Auctioneers@edit']);
    Route::post('admin/dealers/auctioneer/{id}/edit',      ['as' => 'admin_auctioneer_update', 'uses' => 'Auctioneers@update']);
    Route::get('admin/dealers/auctioneer/{id}/vehicles/deleteall', ['as' => 'admin_auctioneer_vehicles_deleteall', 'uses' => 'Auctioneers@vehiclesDelete']);
    Route::get('admin/dealers/auctioneer/{auctioneer}/vehicle/{id}', ['as' => 'admin_auctioneer_vehicles', 'uses' => 'Auctioneers@vehicle']);
    Route::get('admin/dealers/auctioneer/{id}/events', ['as' => 'admin_auctioneer_events', 'uses' => 'AuctioneerEvents@index']);
    Route::get('admin/dealers/auctioneer/{id}/event/create', ['as' => 'admin_auctioneer_event_create', 'uses' => 'AuctioneerEvents@create']);
    Route::post('admin/dealers/auctioneer/{id}/event/create', ['as' => 'admin_auctioneer_event_store', 'uses' => 'AuctioneerEvents@store']);
    Route::get('admin/dealers/auctioneer/{id}/event/{event}/edit', ['as' => 'admin_auctioneer_event_edit', 'uses' => 'AuctioneerEvents@edit']);
    Route::post('admin/dealers/auctioneer/{id}/event/{event}/edit', ['as' => 'admin_auctioneer_event_edit', 'uses' => 'AuctioneerEvents@update']);
    Route::post('admin/dealers/auctioneer/{id}/event/{event}/delete', ['as' => 'admin_auctioneer_event_edit', 'uses' => 'AuctioneerEvents@delete']);
    Route::get('admin/dealers/auctioneer/{id}/event/{event}/clone', ['as' => 'admin_auctioneer_event_clone', 'uses' => 'AuctioneerEvents@cloneEvent']);

    //Dealer Features
    Route::get('admin/dealers/features', ['as' => 'admin_dealers_features', 'uses' => 'DealerFeatures@index']);
    Route::post('admin/dealers/features', ['as' => 'admin_dealers_features_create', 'uses' => 'DealerFeatures@store']);
    Route::get('admin/dealers/features/{id}', ['as' => 'admin_dealers_features_show', 'uses' => 'DealerFeatures@show']);
    Route::post('admin/dealers/features/{id}', ['as' => 'admin_dealers_features_update', 'uses' => 'DealerFeatures@update']);
    Route::post('admin/dealers/features/{id}/delete', ['as' => 'admin_dealers_features_delete', 'uses' => 'DealerFeatures@delete']);

    //Dealer Categories
    Route::get('admin/dealers/categories', ['as' => 'admin_categories', 'uses' => 'DealerCategories@index']);
    Route::post('admin/dealers/categories/create', ['as' => 'admin_categories', 'uses' => 'DealerCategories@store']);
    Route::get('admin/dealers/category/{id}', ['as' => 'admin_category_show', 'uses' => 'DealerCategories@show']);
    Route::get('admin/dealers/category/{id}/test', ['as' => 'admin_category_show', 'uses' => 'DealerCategories@showtest']);
    Route::post('admin/dealers/category/{id}/delete', ['as' => 'admin_category_delete', 'uses' => 'DealerCategories@delete']);
    Route::post('admin/dealers/category/{id}/update', ['as' => 'admin_category_update', 'uses' => 'DealerCategories@update']);
    Route::post('admin/dealers/category/{id}/create-child', ['as' => 'admin_category_create_child', 'uses' => 'DealerCategories@storeChild']);
    Route::get('admin/dealers/category/{id}/child/{child}', ['as' => 'admin_category_child_edit', 'uses' => 'DealerCategories@editChild']);
    Route::post('admin/dealers/category/{id}/child/{child}/update', ['as' => 'admin_category_child_edit', 'uses' => 'DealerCategories@updateChild']);
    Route::post('admin/dealers/category/{id}/child/{child}/delete', ['as' => 'admin_category_child_edit', 'uses' => 'DealerCategories@deleteChild']);


    Route::get('admin/vehicle-type', ['as' => 'admin_vehicletype', 'uses' => 'VehicleType@index']);
    Route::post('admin/vehicle-type/create', ['as' => 'admin_vehicletype_create', 'uses' => 'VehicleType@store']);
    Route::get('admin/vehicle-type/data', ['as' => 'admin_vehicletype_data', 'uses' => 'VehicleType@data']);
    //Route::get('admin/vehicle-type/convert', ['as' => 'admin_vehicletype_convert', 'uses' => 'VehicleType@convert']);
    Route::get('admin/vehicle-type/{id}/edit', ['as' => 'admin_vehicletype_edit', 'uses' => 'VehicleType@edit']);
    Route::post('admin/vehicle-type/{id}/update', ['as' => 'admin_vehicletype_edit', 'uses' => 'VehicleType@update']);
    Route::post('admin/vehicle-type/{id}/delete', ['as' => 'admin_vehicletype_edit', 'uses' => 'VehicleType@destroy']);

    Route::get('admin/vehicle-makes', ['as' => 'admin_vehiclemakes', 'uses' => 'VehicleMakes@index']);
    Route::get('admin/vehicle-makes/data', ['as' => 'admin_vehiclemakes_data', 'uses' => 'VehicleMakes@data']);
    Route::get('admin/vehicle-makes/create', ['as' => 'admin_vehiclemakes_create', 'uses' => 'VehicleMakes@create']);
    Route::post('admin/vehicle-makes/create', ['as' => 'admin_vehiclemakes_store', 'uses' => 'VehicleMakes@store']);
    Route::post('admin/vehicle-makes/create/addFromWiki', ['as' => 'admin_vehiclemake_create_addFromWiki', 'uses' => 'VehicleMakes@createAddFromWiki']);
    Route::get('admin/vehicle-make/{id}/edit', ['as' => 'admin_vehiclemakes_edit', 'uses' => 'VehicleMakes@edit']);
    Route::post('admin/vehicle-make/{id}/update', ['as' => 'admin_vehiclemakes_edit', 'uses' => 'VehicleMakes@update']);
    Route::post('admin/vehicle-make/{id}/delete', ['as' => 'admin_vehiclemakes_edit', 'uses' => 'VehicleMakes@destroy']);
    Route::post('admin/vehicle-make/{id}/remove-logo', ['as' => 'admin_vehiclemakes_remove-logo', 'uses' => 'VehicleMakes@removeLogo']);
    Route::get('admin/vehicle-make/{id}/description', ['as' => 'admin_vehiclemake_description', 'uses' => 'VehicleMakes@viewDescription']);
    Route::post('admin/vehicle-make/{id}/description', ['as' => 'admin_vehiclemake_description_update', 'uses' => 'VehicleMakes@descriptionUpdate']);
    Route::post('admin/vehicle-make/{id}/description/addFromWiki', ['as' => 'admin_vehiclemake_description_addFromWiki', 'uses' => 'VehicleMakes@descriptionAddFromWiki']);

    Route::get('admin/vehicle-models/data/{make?}', ['as' => 'admin_vehiclemodels_data', 'uses' => 'VehicleModels@data']);
    Route::get('admin/vehicle-models/{make?}', ['as' => 'admin_vehiclemodels', 'uses' => 'VehicleModels@index']);
    Route::get('admin/vehicle-models/{make}/create', ['as' => 'admin_vehiclemodels_create', 'uses' => 'VehicleModels@create']);
    Route::post('admin/vehicle-models/{make}/create', ['as' => 'admin_vehiclemodels_store', 'uses' => 'VehicleModels@store']);
    Route::post('admin/vehicle-models/{make}/create/addFromWiki', ['as' => 'admin_vehiclemodels_create_addFromWiki', 'uses' => 'VehicleModels@createAddFromWiki']);
    Route::get('admin/vehicle-model/{id}/description', ['as' => 'admin_vehiclemodel_description', 'uses' => 'VehicleModels@viewDescription']);
    Route::post('admin/vehicle-model/{id}/description', ['as' => 'admin_vehiclemodel_description_update', 'uses' => 'VehicleModels@descriptionUpdate']);
    Route::get('admin/vehicle-model/{id}/delete', ['as' => 'admin_vehiclemodel_description_update', 'uses' => 'VehicleModels@destroy']);
    Route::get('admin/vehicle-model/{id}/edit', ['as' => 'admin_vehiclemodel_description_edit', 'uses' => 'VehicleModels@edit']);
    Route::post('admin/vehicle-model/{id}/edit', ['as' => 'admin_vehiclemodel_description_edit', 'uses' => 'VehicleModels@update']);
    Route::post('admin/vehicle-model/{id}/description/addFromWiki', ['as' => 'admin_vehiclemodel_description_addFromWiki', 'uses' => 'VehicleModels@descriptionAddFromWiki']);


    Route::get('admin/vehicle-model-variants/data/{make?}', ['as' => 'admin_vehiclemodelvariants_data', 'uses' => 'VehicleVariants@data']);
    Route::get('admin/vehicle-model-variants/details/{id}', ['as' => 'admin_vehiclemodelvariants_details', 'uses' => 'VehicleVariants@details']);
    Route::get('admin/vehicle-model-variants/details/{id}/setDefault', ['as' => 'admin_vehiclemodelvariants_default', 'uses' => 'VehicleVariants@setdefault']);
    Route::get('admin/vehicle-model-variants/{make?}', ['as' => 'admin_vehiclemodelvariants', 'uses' => 'VehicleVariants@index']);
    Route::get('admin/vehicle-model-variants/{id}/description', ['as' => 'admin_vehiclemodelvariants_description', 'uses' => 'VehicleVariants@viewDescription']);
    Route::post('admin/vehicle-model-variants/{id}/description', ['as' => 'admin_vehiclemodelvariants_description_update', 'uses' => 'VehicleVariants@descriptionUpdate']);

    Route::get('admin/vehicle-features', ['as' => 'admin_vehicle_features', 'uses' => 'VehicleFeatures@index']);
    Route::get('admin/vehicle-features/create', ['as' => 'admin_vehicle_features_create', 'uses' => 'VehicleFeatures@create']);
    Route::post('admin/vehicle-features/create', ['as' => 'admin_vehicle_features_store', 'uses' => 'VehicleFeatures@store']);
    Route::post('admin/vehicle-features/savePosition', ['as' => 'admin_vehicle_features_position', 'uses' => 'VehicleFeatures@savePosition']);
    Route::get('admin/vehicle-features/{id}/edit', ['as' => 'admin_vehicle_features_edit', 'uses' => 'VehicleFeatures@edit']);
    Route::post('admin/vehicle-features/{id}/update', ['as' => 'admin_vehicle_features_update', 'uses' => 'VehicleFeatures@update']);
    Route::get('admin/vehicle-features/{id}/delete', ['as' => 'admin_vehicle_features_destroy', 'uses' => 'VehicleFeatures@destroy']);

    Route::get('admin/vehicle-features/{feature}/items', ['as' => 'admin_vehicle_feature_items', 'uses' => 'VehicleFeatureItems@index']);
    Route::get('admin/vehicle-features/{feature}/item/create', ['as' => 'admin_vehicle_feature_items_create', 'uses' => 'VehicleFeatureItems@create']);
    Route::post('admin/vehicle-features/{feature}/item/create', ['as' => 'admin_vehicle_feature_items_store', 'uses' => 'VehicleFeatureItems@store']);
    Route::post('admin/vehicle-features/{feature}/items/savePosition', ['as' => 'admin_vehicle_feature_items_position', 'uses' => 'VehicleFeatureItems@savePosition']);
    Route::get('admin/vehicle-features/{feature}/item/{id}/edit', ['as' => 'admin_vehicle_feature_items_edit', 'uses' => 'VehicleFeatureItems@edit']);
    Route::post('admin/vehicle-features/{feature}/item/{id}/update', ['as' => 'admin_vehicle_feature_items_update', 'uses' => 'VehicleFeatureItems@update']);
    Route::get('admin/vehicle-features/{feature}/item/{id}/delete', ['as' => 'admin_vehicle_feature_items_destroy', 'uses' => 'VehicleFeatureItems@destroy']);

    Route::get('admin/vehicles/auctions', ['as' => 'admin_vehicles', 'uses' => 'Vehicles@index']);
    Route::get('admin/vehicles/classifieds', ['as' => 'admin_vehicles', 'uses' => 'Vehicles@indexClassified']);
    Route::get('admin/vehicles/classifieds/create', ['as' => 'admin_vehicles', 'uses' => 'Vehicles@classifiedCreate']);
    Route::post('admin/vehicles/classifieds/create', ['as' => 'admin_vehicles', 'uses' => 'Vehicles@storeClassified']);
    Route::get('admin/vehicles/trade', ['as' => 'admin_vehicles', 'uses' => 'Vehicles@indexTrade']);
    Route::get('admin/vehicle/{id}', ['as' => 'admin_vehicles', 'uses' => 'Vehicles@show']);
    Route::get('admin/vehicle/{id}/auctionEdit', ['as' => 'admin_vehicle_auctionEdit', 'uses' => 'Vehicles@auctionEdit']);
    Route::post('admin/vehicle/{id}/auctionEdit/update', ['as' => 'admin_vehicle_auctionEdit_update', 'uses' => 'Vehicles@auctionUpdate']);
    Route::get('admin/vehicle/{id}/classifiedEdit', ['as' => 'admin_vehicle_classifiedEdit', 'uses' => 'Vehicles@classifiedEdit']);
    Route::post('admin/vehicle/{id}/classifiedEdit/update', ['as' => 'admin_vehicle_classifiedEdit_update', 'uses' => 'Vehicles@classifiedUpdate']);
    Route::get('admin/vehicle/{id}/classifiedEdit/loadImages', ['as' => 'admin_vehicle_classifiedEdit_loadImages', 'uses' => 'Vehicles@loadImages']);
    Route::post('admin/vehicle/{id}/classifiedEdit/uploadImages', ['as' => 'admin_vehicle_classifiedEdit_uploadImages', 'uses' => 'Vehicles@uploadImages']);
    Route::get('admin/vehicle/{id}/classifiedEdit/deleteImage/{image}', ['as' => 'admin_vehicle_classifiedEdit_deleteImage', 'uses' => 'Vehicles@deleteImage']);
    Route::get('admin/vehicle/{id}/classifiedEdit/defaultImage/{image}', ['as' => 'admin_vehicle_classifiedEdit_defaultImage', 'uses' => 'Vehicles@defaultImage']);

    Route::get('admin/vehicleTools/unclassified', ['as' => 'admin_vehicleTools_unclassified', 'uses' => 'VehicleTools@unclassified']);
    Route::get('admin/vehicleTools/unclassified/findMatch/{id}', ['as' => 'admin_vehicleTools_unclassified_findMatch', 'uses' => 'VehicleTools@unclassifiedMatch']);
    Route::get('admin/vehicleTools/unclassified/matchLogs', ['as' => 'admin_vehicleMatchLogs', 'uses' => 'VehicleTools@matchLogs']);
    Route::get('admin/vehicleTools/unclassified/matchLogData', ['as' => 'admin_vehicleMatchLogs_data', 'uses' => 'VehicleTools@matchData']);

    Route::get('admin/vehicleTools/import', ['as' => 'admin_vehicleTools_import', 'uses' => 'VehicleTools@import']);
    Route::get('admin/vehicleTools/import/importVehicles', ['as' => 'admin_vehicleTools_import_importVehicles', 'uses' => 'VehicleTools@importVehicles']);
    Route::get('admin/vehicleTools/import/importImages', ['as' => 'admin_vehicleTools_import_importImages', 'uses' => 'VehicleTools@importImages']);
    Route::get('admin/vehicleTools/import/expire', ['as' => 'admin_vehicleTools_import_expire', 'uses' => 'VehicleTools@expire']);
    Route::get('admin/vehicleTools/getExactModel/{model}/{makeId}', ['as' => 'admin_vehicleTools_getExactModel', 'uses' => 'VehicleTools@getExactModel']);


    Route::get('admin/gauk-settings', ['as' => 'admin_gauksettings', 'uses' => 'GaukSettings@index']);
    Route::post('admin/gauk-settings', ['as' => 'admin_gauksettings_update', 'uses' => 'GaukSettings@update']);


    Route::post('admin/post/{id}/convert/carmake', ['as' => 'admin_post_convert_carmake', 'uses' => 'VehicleType@convertCarMake']);
    Route::post('admin/post/{id}/convert/carmakenew', ['as' => 'admin_post_convert_carmakenew', 'uses' => 'VehicleType@convertCarMakeNew']);
    Route::post('admin/post/{id}/convert/carmodelnew', ['as' => 'admin_post_convert_carmodelnew', 'uses' => 'VehicleType@convertCarModelNew']);
    Route::post('admin/post/{id}/convert/carmodel', ['as' => 'admin_post_convert_carmodel', 'uses' => 'VehicleType@convertCarModel']);

    Route::post('admin/ajax/vehicleModels', ['as' => 'admin_ajax_vehicleModels', 'uses' => 'Ajax@vehicleModels']);
    Route::post('admin/ajax/vehicleType', ['as' => 'admin_ajax_vehicleType', 'uses' => 'Ajax@vehicleType']);
    Route::post('admin/ajax/vehicleModelVarient', ['as' => 'admin_ajax_vehicleModelVarient', 'uses' => 'Ajax@vehicleModelVarient']);

    //FileManager
    Route::get('admin/filemanager', ['uses' => '\Unisharp\Laravelfilemanager\controllers\LfmController@show', 'as' => 'show']);
    Route::any('admin/filemanager/upload', ['uses' => '\Unisharp\Laravelfilemanager\controllers\UploadController@upload', 'as' => 'upload']);
    Route::get('admin/filemanager/jsonitems', ['uses' => '\Unisharp\Laravelfilemanager\controllers\ItemsController@getItems', 'as' => 'getItems']);
    Route::get('admin/filemanager/newfolder', ['uses' => '\Unisharp\Laravelfilemanager\controllers\FolderController@getAddfolder', 'as' => 'getAddfolder']);
    Route::get('admin/filemanager/deletefolder', ['uses' => '\Unisharp\Laravelfilemanager\controllers\FolderController@getDeletefolder', 'as' => 'getDeletefolder']);
    Route::get('admin/filemanager/folders', ['uses' => '\Unisharp\Laravelfilemanager\controllers\FolderController@getFolders', 'as' => 'getFolders']);
    Route::get('admin/filemanager/crop', ['uses' => '\Unisharp\Laravelfilemanager\controllers\CropController@getCrop', 'as' => 'getCrop']);
    Route::get('admin/filemanager/cropimage', ['uses' => '\Unisharp\Laravelfilemanager\controllers\CropController@getCropimage', 'as' => 'getCropimage']);
    Route::get('admin/filemanager/rename', ['uses' => '\Unisharp\Laravelfilemanager\controllers\RenameController@getRename', 'as' => 'getRename']);
    Route::get('admin/filemanager/resize', ['uses' => '\Unisharp\Laravelfilemanager\controllers\ResizeController@getResize', 'as' => 'getResize']);
    Route::get('admin/filemanager/doresize', ['uses' => '\Unisharp\Laravelfilemanager\controllers\ResizeController@performResize', 'as' => 'performResize']);
    Route::get('admin/filemanager/download', ['uses' => '\Unisharp\Laravelfilemanager\controllers\DownloadController@getDownload', 'as' => 'getDownload']);
    Route::get('admin/filemanager/delete', ['uses' => '\Unisharp\Laravelfilemanager\controllers\DeleteController@getDelete', 'as' => 'getDelete']);

    Route::get('admin/cache/settings', ['as' => 'admin_cache_setting', 'uses' => 'CacheSettings@index']);
    Route::get('admin/cache/settings/clear', ['as' => 'admin_cache_setting/clear', 'uses' => 'CacheSettings@clearAll']);

    Route::get('admin/convert', ['as' => 'admin_convert', 'uses' => 'Converter@index']);
    Route::post('admin/convert/modeltomake', ['as' => 'admin_convert_modeltomake', 'uses' => 'Converter@modeltomake']);

    Route::get('admin/tools/updateDealerCounty', ['as' => 'admin_tools_updateDealerCounty', 'uses' => 'Tools@updateDealerCounty']);
    Route::get('admin/tools/moveImageDir', ['as' => 'admin_tools_updateDealerCounty', 'uses' => 'Tools@moveImageDir']);

    //Wiki Scraper
    Route::get('admin/wiki-scraper/{search}', ['as' => 'admin_wiki_scraper', 'uses' => 'WikiScraper@index']);

    //Book
    Route::get('admin/books', ['as' => 'admin_books', 'uses' => 'Book@index']);
    Route::get('admin/book/{id}/edit', ['as' => 'admin_book_edit', 'uses' => 'Book@edit']);
    Route::post('admin/book/{id}/edit', ['as' => 'admin_book_update', 'uses' => 'Book@update']);
    Route::get('admin/book/{id}/delete', ['as' => 'admin_book_delete', 'uses' => 'Book@destroy']);
    Route::get('admin/book/create', ['as' => 'admin_book_create', 'uses' => 'Book@create']);
    Route::post('admin/book/create', ['as' => 'admin_book_store', 'uses' => 'Book@store']);
    Route::get('admin/book/{id}/manage', ['as' => 'admin_book_manage', 'uses' => 'Book@manage']);
    Route::post('admin/book/{id}/manage/savePosition', ['as' => 'admin_book_manage_position', 'uses' => 'Book@manageSavePosition']);

    Route::get('admin/book/{id}/createChapter', ['as' => 'admin_book_createChapter', 'uses' => 'Book@createChapter']);
    Route::post('admin/book/{id}/createChapter', ['as' => 'admin_book_storeChapter', 'uses' => 'Book@storeChapter']);
    Route::get('admin/book/{id}/chapter/{chapter}/edit', ['as' => 'admin_book_chapter_edit', 'uses' => 'Book@editChapter']);
    Route::post('admin/book/{id}/chapter/{chapter}/edit', ['as' => 'admin_book_chapter_update', 'uses' => 'Book@updateChapter']);
    Route::get('admin/book/{id}/chapter/{chapter}/delete', ['as' => 'admin_book_chapter_delete', 'uses' => 'Book@destroyChapter']);
    Route::get('admin/book/{id}/chapter/{chapter}/pages', ['as' => 'admin_book_chapter_pages', 'uses' => 'Book@pages']);
    Route::post('admin/book/{id}/chapter/{chapter}/pages/savePosition', ['as' => 'admin_book_chapter_pages', 'uses' => 'Book@pagesSavePosition']);
    Route::get('admin/book/{id}/chapter/{chapter}/createPage', ['as' => 'admin_book_chapter_page_create', 'uses' => 'Book@pageCreate']);
    Route::post('admin/book/{id}/chapter/{chapter}/createPage', ['as' => 'admin_book_chapter_page_store', 'uses' => 'Book@pageStore']);

    Route::get('admin/book/{id}/chapter/{chapter}/page/{page}/edit', ['as' => 'admin_book_chapter_page_edit', 'uses' => 'Book@pageEdit']);
    Route::post('admin/book/{id}/chapter/{chapter}/page/{page}/edit', ['as' => 'admin_book_chapter_page_update', 'uses' => 'Book@pageUpdate']);
    Route::get('admin/book/{id}/chapter/{chapter}/page/{page}/delete', ['as' => 'admin_book_chapter_page_delete', 'uses' => 'Book@destroyPage']);
    Route::get('admin/book/{id}/chapter/{chapter}/page/{page}/revision/{revision}', ['as' => 'admin_book_chapter_page_revision', 'uses' => 'Book@showRevision']);

    //Filemanager
    Route::get('admin/filemanager', ['as' => 'admin_filemanager', 'uses' => 'Filemanager@index']);
    Route::get('admin/filemanager/jsonitems', ['as' => 'admin_filemanager_jsonitems', 'uses' => 'Filemanager@jsonitems']);
    Route::get('admin/filemanager/errors', ['as' => 'admin_filemanager_getErrors', 'uses' => 'Filemanager@getErrors']);
    Route::post('admin/filemanager/upload', ['as' => 'admin_filemanager_upload', 'uses' => 'Filemanager@upload']);

    //Calendar
    Route::get('admin/calendar/import', ['as' => 'admin_calendar_import', 'uses' => 'CalendarImport@index']);
    Route::post('admin/calendar/import', ['as' => 'admin_calendar_import', 'uses' => 'CalendarImport@create']);
    Route::get('admin/calendar/importFromFile', ['as' => 'admin_calendar_importFromFile', 'uses' => 'CalendarImport@importFromFile']);
    Route::post('admin/calendar/import/markComplete', ['as' => 'admin_calendar_markComplete', 'uses' => 'CalendarImport@markComplete']);

    // New Import
    Route::get('admin/import/features', ['as' => 'admin_import_features', 'uses' => 'Import@features']);
    Route::get('admin/import/categories', ['as' => 'admin_import_categories', 'uses' => 'Import@categories']);
    Route::get('admin/import/dealers', ['as' => 'admin_import_dealers', 'uses' => 'Import@dealers']);
    Route::get('admin/import/parsedDealers', ['as' => 'admin_import_parsedDealers', 'uses' => 'Import@parsedDealers']);
    Route::get('admin/import/getLots', ['as' => 'admin_import_getLots', 'uses' => 'Import@getLots']);
    Route::get('admin/import', ['as' => 'admin_import', 'uses' => 'Import@index']);

});

//public routes

Route::group(['namespace' => 'Frontend'], function()
{
    Route::get('vehicles', ['as' => 'public_vehicles', 'uses' => 'Vehicles@index']);
    Route::post('vehicles', ['as' => 'public_vehicles_filter', 'uses' => 'Vehicles@filter']);
    Route::get('vehicle/{id}', ['as' => 'public_vehicle_show', 'uses' => 'Vehicles@show']);

    Route::get('auctioneers', ['as' => 'public_auctioneers', 'uses' => 'Dealers@index']);
    Route::get('auctioneer/{id}', ['as' => 'public_auctioneer_show', 'uses' => 'Dealers@show']);
    Route::get('auctioneer/{id}/vehicles', ['as' => 'public_auctioneer_show_vehicles', 'uses' => 'Dealers@showVehicles']);

    Route::get('car-data/{only?}', ['as' => 'public_carmake_index', 'uses' => 'CarData@index']);
    Route::post('car-data/search', ['as' => 'public_carmake_search', 'uses' => 'CarData@search']);
    Route::get('car-make/{id}', ['as' => 'public_carmake_show', 'uses' => 'CarData@show']);
    Route::get('car-make/{id}/{model}', ['as' => 'public_carmake_model_show', 'uses' => 'CarData@showModel']);
    Route::get('car-make/{id}/{model}/{variant}', ['as' => 'public_carmake_model_variant_show', 'uses' => 'CarData@showModelVariant']);

    Route::get('motorpedia/{only?}', ['as' => 'public_motorpedia_index', 'uses' => 'CarData@index']);
    Route::post('motorpedia/search', ['as' => 'public_motorpedia_search', 'uses' => 'CarData@search']);
    Route::get('motorpedia/car-make/{id}', ['as' => 'public_motorpedia_carmake_show', 'uses' => 'CarData@show']);
    Route::get('motorpedia/car-make/{id}/{model}', ['as' => 'public_motorpedia_carmake_model_show', 'uses' => 'CarData@showModel']);
    Route::get('motorpedia/car-make/{id}/{model}/{variant}', ['as' => 'public_motorpedia_carmake_model_variant_show', 'uses' => 'CarData@showModelVariant']);

    Route::post('ajax/vehicleModelSearch/{returnVar?}', ['as' => 'public_ajax_vehicleModelSearch', 'uses' => 'Ajax@vehicleModelSearch']);
    Route::get('ajax/vehicleModels/{make}', ['as' => 'public_ajax_vehicleModels', 'uses' => 'CarData@modelsData']);
    Route::post('ajax/vehicleLocations', ['as' => 'public_ajax_vehicleLocations', 'uses' => 'Ajax@vehicleLocations']);
    Route::post('ajax/getDealers', ['as' => 'ajax_getDealers', 'uses' => 'Ajax@getDealers']);


    Route::get('books', ['as' => 'public_books', 'uses' => 'Book@index']);
    Route::get('book/{book}', ['as' => 'public_books', 'uses' => 'Book@show']);
    Route::get('book/{book}/details', ['as' => 'public_book_details', 'uses' => 'Book@details']);
    Route::get('book/{book}/{chapter}', ['as' => 'public_books', 'uses' => 'Book@showChapter']);
    Route::get('book/{book}/{chapter}/{page}', ['as' => 'public_books', 'uses' => 'Book@showPage']);

    Route::get('posts/feed/{type?}', ['as' => 'posts_feed_atom', 'uses' => 'Feeds@getFeed']);
    Route::get('feed/{type?}', ['as' => 'posts_feed_atom', 'uses' => 'Feeds@getFeed']);

});
