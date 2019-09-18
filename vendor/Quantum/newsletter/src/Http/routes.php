<?php

Route::group(['namespace' => 'Quantum\newsletter\Http\Controllers', 'middleware' => ['web']], function()
{

//Member Routes
    Route::group(['namespace' => 'Members', 'middleware' => ['auth']], function()
    {
        Route::get('members/newsletter/subscribe/{id}', ['as' => 'members_newsletter_subscribe', 'uses' => 'Subscribe@index']);
        Route::get('members/newsletter/unsubscribe/{id}', ['as' => 'members_newsletter_unsubscribe', 'uses' => 'Subscribe@unsubscribe']);
        Route::get('members/newsletter/details/{id}', ['as' => 'members_newsletter_details', 'uses' => 'Subscribe@details']);
    });

//Admin Routes
    Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function()
    {
        Route::get('admin/newsletter', ['as' => 'admin_newsletter', 'uses' => 'Newsletter@index']);
        Route::get('admin/newsletter/create', ['as' => 'admin_newsletter_create', 'uses' => 'Newsletter@create']);
        Route::post('admin/newsletter/create', ['as' => 'admin_newsletter_store', 'uses' => 'Newsletter@store']);
        Route::get('admin/newsletter/{id}/edit', ['as' => 'admin_newsletter_edit', 'uses' => 'Newsletter@edit']);
        Route::post('admin/newsletter/{id}/edit', ['as' => 'admin_newsletter_edit', 'uses' => 'Newsletter@update']);
        Route::get('admin/newsletter/{id}/delete', ['as' => 'admin_newsletter_delete', 'uses' => 'Newsletter@delete']);
        Route::post('admin/newsletter/{id}/delete', ['as' => 'admin_newsletter_delete', 'uses' => 'Newsletter@destroy']);
        Route::get('admin/newsletter/{id}/getCode', ['as' => 'admin_newsletter_getCode', 'uses' => 'Newsletter@getCode']);
        Route::get('admin/newsletter/{id}/responders', ['as' => 'admin_newsletter_responders', 'uses' => 'Newsletter@responders']);
        Route::get('admin/newsletter/{id}/responder/create', ['as' => 'admin_newsletter_responder_create', 'uses' => 'Newsletter@responderCreate']);
        Route::post('admin/newsletter/{id}/responder/create', ['as' => 'admin_newsletter_responder_store', 'uses' => 'Newsletter@responderStore']);
        Route::get('admin/newsletter/{id}/responder/{respID}/edit', ['as' => 'admin_newsletter_responder_edit', 'uses' => 'Newsletter@responderEdit']);
        Route::post('admin/newsletter/{id}/responder/{respID}/edit', ['as' => 'admin_newsletter_responder_edit', 'uses' => 'Newsletter@responderUpdate']);
        Route::post('admin/newsletter/{id}/updateResponderPositions', ['as' => 'admin_newsletter_updateResponderPositions', 'uses' => 'Newsletter@updateResponderPositions']);
        Route::get('admin/newsletter/{id}/responder/{respID}/delete', ['as' => 'admin_newsletter_responder_delete', 'uses' => 'Newsletter@responderDelete']);

        Route::get('admin/newsletter/subscriber/create/{id?}', ['as' => 'admin_newsletter_subscriber_create', 'uses' => 'Subscribers@create']);
        Route::post('admin/newsletter/subscriber/create', ['as' => 'admin_newsletter_subscriber_create', 'uses' => 'Subscribers@store']);
        Route::get('admin/newsletter/subscribers', ['as' => 'admin_newsletter_subscribers', 'uses' => 'Subscribers@index']);
        Route::post('admin/newsletter/subscriber/search', ['as' => 'admin_newsletter_subscribers_search', 'uses' => 'Subscribers@search']);
        Route::get('admin/newsletter/subscriber/{id}/edit', ['as' => 'admin_newsletter_subscribers_edit', 'uses' => 'Subscribers@edit']);
        Route::post('admin/newsletter/subscriber/{id}/edit', ['as' => 'admin_newsletter_subscribers_update', 'uses' => 'Subscribers@update']);
        Route::get('admin/newsletter/subscribers/{id}', ['as' => 'admin_newsletter_subscribers_newsletter', 'uses' => 'Subscribers@newsletterSubscribers']);

        Route::get('admin/newsletter/import', ['as' => 'admin_newsletter_import', 'uses' => 'Import@index']);
        Route::post('admin/newsletter/import', ['as' => 'admin_newsletter_import_doImport', 'uses' => 'Import@doImport']);
        Route::get('admin/newsletter/import/queued', ['as' => 'admin_newsletter_import_queued', 'uses' => 'Import@queued']);

        Route::get('admin/newsletter/mail', ['as' => 'admin_newsletter_mail', 'uses' => 'Mailer@index']);
        Route::get('admin/newsletter/mail/create', ['as' => 'admin_newsletter_mail_create', 'uses' => 'Mailer@create']);
        Route::post('admin/newsletter/mail/create', ['as' => 'admin_newsletter_mail_store', 'uses' => 'Mailer@store']);
        Route::get('admin/newsletter/mail/{id}/edit', ['as' => 'admin_newsletter_mail_edit', 'uses' => 'Mailer@edit']);
        Route::post('admin/newsletter/mail/{id}/edit', ['as' => 'admin_newsletter_mail_update', 'uses' => 'Mailer@update']);
        Route::post('admin/newsletter/mail/{id}/delete', ['as' => 'admin_newsletter_mail_edit', 'uses' => 'Mailer@destroy']);
        Route::get('admin/newsletter/mail/{id}/preview', ['as' => 'admin_newsletter_mail_preview', 'uses' => 'Mailer@preview']);

        Route::get('admin/newsletter/maillog', ['as' => 'admin_newsletter_maillog', 'uses' => 'MailerLog@index']);
        Route::get('admin/newsletter/maillog/{id}', ['as' => 'admin_newsletter_maillog_show', 'uses' => 'MailerLog@show']);

        Route::get('admin/newsletter/templates', ['as' => 'admin_newsletter_templates', 'uses' => 'Templates@index']);
        Route::get('admin/newsletter/template/create', ['as' => 'admin_newsletter_template_create', 'uses' => 'Templates@create']);
        Route::post('admin/newsletter/template/create', ['as' => 'admin_newsletter_template_create', 'uses' => 'Templates@store']);
        Route::get('admin/newsletter/template/{id}/edit', ['as' => 'admin_newsletter_template_edit', 'uses' => 'Templates@edit']);
        Route::post('admin/newsletter/template/{id}/edit', ['as' => 'admin_newsletter_template_edit', 'uses' => 'Templates@update']);
        Route::get('admin/newsletter/template/{id}/delete', ['as' => 'admin_newsletter_template_delete', 'uses' => 'Templates@delete']);

        Route::get('admin/newsletter/themes', ['as' => 'admin_newsletter_themes', 'uses' => 'Themes@index']);
        Route::get('admin/newsletter/theme/create', ['as' => 'admin_newsletter_theme_create', 'uses' => 'Themes@create']);
        Route::post('admin/newsletter/theme/create', ['as' => 'admin_newsletter_theme_create', 'uses' => 'Themes@store']);
        Route::get('admin/newsletter/theme/{id}/edit', ['as' => 'admin_newsletter_theme_edit', 'uses' => 'Themes@edit']);
        Route::post('admin/newsletter/theme/{id}/edit', ['as' => 'admin_newsletter_theme_edit', 'uses' => 'Themes@update']);
        Route::get('admin/newsletter/theme/{id}/delete', ['as' => 'admin_newsletter_theme_delete', 'uses' => 'Themes@delete']);

        Route::post('admin/newsletter/getTemplate', ['as' => 'admin_newsletter_gettemplate', 'uses' => 'Templates@getTemplate']);
        Route::get('admin/newsletter/sendShot', ['as' => 'admin_newsletter_sendshot', 'uses' => 'Newsletter@sendShot']);
    });

//Public Routes
    Route::group(['namespace' => 'Frontend'], function()
    {
        Route::get('newsletter/mailerimage/{id}/{subcode?}', ['as' => 'public_newsletter_mailimage', 'uses' => 'MailerTrack@index']);
        Route::post('newsletter/subscribe/{id}', ['as' => 'public_newsletter_subscribe', 'uses' => 'Subscribe@index']);
        Route::get('newsletter/subscriber/confirm/{id}', ['as' => 'public_newsletter_subscriber_confirm', 'uses' => 'Subscribe@confirm']);
        Route::get('newsletter/unsubscribe/{newsletter}/{id}', ['as' => 'public_newsletter_unsubscribe', 'uses' => 'Subscribe@unsubscribe']);

        Route::get('newsletter/manage', ['as' => 'public_newsletter_manage', 'uses' => 'Subscribe@manage']);
        Route::post('newsletter/manage', ['as' => 'public_newsletter_manage_login', 'uses' => 'Subscribe@manageLogin', 'middleware' => 'firewall']);

        Route::get('newsletter/manage/subscribe/{id}', ['as' => 'public_newsletter_subscribe', 'uses' => 'Subscribe@manageSubscribe']);
        Route::get('newsletter/manage/unsubscribe/{id}', ['as' => 'public_newsletter_unsubscribe', 'uses' => 'Subscribe@manageUnsubscribe']);
        Route::get('newsletter/manage/details/{id}', ['as' => 'public_newsletter_details', 'uses' => 'Subscribe@manageDetails']);
        Route::get('newsletter/manage/logout', ['as' => 'public_newsletter_details', 'uses' => 'Subscribe@manageLogout']);

    });

});

