<?php
Route::group(['namespace' => 'Quantum\base\Http\Controllers', 'middleware' => ['web']], function()
{

//Member Routes
    Route::group(['namespace' => 'Members', 'middleware' => ['auth']], function()
    {
        //Route::get('members/dashboard', ['as' => 'members_dashboard', 'uses' => 'Dashboard@index']);

        //Profile
        Route::get('members/profile', ['as' => 'members_profile', 'uses' => 'Profile@index']);
        Route::post('members/profile/update', ['as' => 'members_profile_update', 'uses' => 'Profile@update']);
        Route::post('members/account/delete', ['as' => 'members_profile_update', 'uses' => 'Profile@destroy']);
        //Activity
        Route::get('members/logs/activity', ['as' => 'members_logs_activity', 'uses' => 'Logs@data']);
        //Commerce
        Route::get('members/cart/add/{model}/{modelId}', ['as' => 'cart_members_add', 'uses' => 'Cart@add']);
        Route::get('members/cart/test', ['as' => 'cart_test', 'uses' => 'CartTest@index']);
        Route::get('members/cart/test2', ['as' => 'cart_test2', 'uses' => 'CartTest2@index']);

        Route::get('members/payment/{type}/success/{gateway}', ['as' => 'members_payment_success', 'uses' => 'Payment@success']);
        Route::get('members/payment/{type}/cancel/{gateway}', ['as' => 'members_payment_cancel', 'uses' => 'Payment@cancel']);
        Route::get('members/payment/{gateway}', ['as' => 'members_payment', 'uses' => 'Payment@index']);

        Route::get('members/logs/payments', ['as' => 'members_logs_payments', 'uses' => 'CommerceLogs@dataUser']);

    });

//Admin Routes
    Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function()
    {
        //Update
        //Route::get('admin/update', ['as' => 'admin_update', 'uses' => 'Update@index']);
        //Dashboard
        Route::get('admin/dashboard', ['as' => 'admin_dashboard', 'uses' => 'Dashboard@index']);
        Route::get('admin/loginAsUser/{id}', ['as' => 'admin_login_as_user', 'uses' => 'Users@loginAs']);
        //Menu
        Route::get('admin/menu', ['as' => 'admin_menu', 'uses' => 'Menu@index']);
        Route::get('admin/menu/{id}/edit', ['as' => 'admin_menu_edit', 'uses' => 'Menu@edit']);
        Route::get('admin/menu/{id}/delete', ['as' => 'admin_menu_edit', 'uses' => 'Menu@destroy']);
        Route::post('admin/menu/{id}/get-item', ['as' => 'admin_menu_edit', 'uses' => 'Menu@getItem']);
        Route::post('admin/menu/create', ['as' => 'admin_menu_create', 'uses' => 'Menu@store']);
        Route::post('admin/menu/{id}/update', ['as' => 'admin_menu_update', 'uses' => 'Menu@update']);
        Route::post('admin/menu-item/create', ['as' => 'admin_menu_item_create', 'uses' => 'Menu@storeList']);
        Route::post('admin/menu-item/position', ['as' => 'admin_menu_item_position', 'uses' => 'Menu@ItemPosition']);
        Route::post('admin/menu-item/{id}/update', ['as' => 'admin_menu_item_update', 'uses' => 'Menu@ItemUpdate']);
        Route::post('admin/menu-item/{id}/delete', ['as' => 'admin_menu_item_delete', 'uses' => 'Menu@ItemDelete']);
        //Settings
        Route::get('admin/settings', ['as' => 'admin_settings', 'uses' => 'Settings@index']);
        Route::post('admin/settings/update', ['as' => 'admin_settings_update', 'uses' => 'Settings@update']);
        //Categories
        Route::get('admin/categories', ['as' => 'admin_categories', 'uses' => 'Categories@index']);
        Route::post('admin/categories/create', ['as' => 'admin_categories', 'uses' => 'Categories@store']);
        Route::get('admin/category/{id}', ['as' => 'admin_category_show', 'uses' => 'Categories@show']);
        Route::get('admin/category/{id}/test', ['as' => 'admin_category_show', 'uses' => 'Categories@showtest']);
        Route::post('admin/category/{id}/delete', ['as' => 'admin_category_delete', 'uses' => 'Categories@delete']);
        Route::post('admin/category/{id}/update', ['as' => 'admin_category_update', 'uses' => 'Categories@update']);
        Route::post('admin/category/{id}/create-child', ['as' => 'admin_category_create_child', 'uses' => 'Categories@storeChild']);
        Route::get('admin/category/{id}/child/{child}', ['as' => 'admin_category_child_edit', 'uses' => 'Categories@editChild']);
        Route::post('admin/category/{id}/child/{child}/update', ['as' => 'admin_category_child_edit', 'uses' => 'Categories@updateChild']);
        Route::post('admin/category/{id}/child/{child}/delete', ['as' => 'admin_category_child_edit', 'uses' => 'Categories@deleteChild']);
        //Tags
        Route::get('admin/tags', ['as' => 'admin_tags', 'uses' => 'Tags@index']);
        Route::get('admin/tags/data', ['as' => 'admin_tags_data', 'uses' => 'Tags@data']);
        Route::get('admin/tags/{id}/delete', ['as' => 'admin_tags_delete', 'uses' => 'Tags@delete']);
        //Acl
        Route::get('admin/acl', ['as' => 'admin_acl', 'uses' => 'Acl@index']);
        Route::post('admin/acl/role/create', ['as' => 'admin_acl_role_create', 'uses' => 'Acl@roleStore']);
        Route::get('admin/acl/role/{id}/edit', ['as' => 'admin_acl_role_edit', 'uses' => 'Acl@roleEdit']);
        Route::post('admin/acl/role/{id}/update', ['as' => 'admin_acl_role_edit', 'uses' => 'Acl@roleUpdate']);
        Route::post('admin/acl/role/{id}/delete', ['as' => 'admin_acl_role_delete', 'uses' => 'Acl@roleDestroy']);
        Route::post('admin/acl/permission/create', ['as' => 'admin_acl_permission_create', 'uses' => 'Acl@permissionCreate']);
        Route::get('admin/acl/permission/{id}/edit', ['as' => 'admin_acl_permission_edit', 'uses' => 'Acl@permissionEdit']);
        Route::post('admin/acl/permission/{id}/update', ['as' => 'admin_acl_permission_update', 'uses' => 'Acl@permissionUpdate']);
        Route::post('admin/acl/permission/{id}/delete', ['as' => 'admin_acl_permission_delete', 'uses' => 'Acl@permissionDestroy']);
        //Firewall
        Route::get('admin/firewall/failure', ['as' => 'admin_firewall_failure', 'uses' => 'Firewall@show_failed']);
        Route::get('admin/firewall/blocked', ['as' => 'admin_firewall_blocked', 'uses' => 'Firewall@show_blocked']);
        Route::get('admin/firewall/whitelisted', ['as' => 'admin_firewall_whitelisted', 'uses' => 'Firewall@show_whitelisted']);
        Route::post('admin/firewall/failure/remove', ['as' => 'admin_firewall_failure_remove', 'uses' => 'Firewall@remove_failure']);
        Route::post('admin/firewall/blocked/remove', ['as' => 'admin_firewall_blocked_remove', 'uses' => 'Firewall@remove_blocked']);
        Route::post('admin/firewall/whitelist/remove', ['as' => 'admin_firewall_whitelist_remove', 'uses' => 'Firewall@remove_whitelist']);
        Route::post('admin/firewall/whitelist/add', ['as' => 'admin_firewall_whitelist_add', 'uses' => 'Firewall@add_whitelist']);
        //users
        Route::get('admin/users/{role?}', ['as' => 'admin_users', 'uses' => 'Users@index']);
        Route::post('admin/users', ['as' => 'admin_users', 'uses' => 'Users@showRole']);
        Route::get('admin/user/create', ['as' => 'admin_users_create', 'uses' => 'Users@create']);
        Route::post('admin/user/create', ['as' => 'admin_users_store', 'uses' => 'Users@store']);
        Route::get('admin/user/{username}/edit', ['as' => 'admin_user_edit', 'uses' => 'Users@edit']);
        Route::post('admin/user/{id}/update', ['as' => 'admin_user_profile_update', 'uses' => 'Users@update']);
        Route::post('admin/user/{id}/removeProfilePicture', ['as' => 'admin_user_profile_update', 'uses' => 'Users@removeProfilePicture']);
        Route::post('admin/user/{id}/delete', ['as' => 'admin_user_delete', 'uses' => 'Users@destroy']);
        Route::post('admin/user/{id}/membership/add', ['as' => 'admin_user_membership_add', 'uses' => 'Users@addMembership']);
        Route::post('admin/user/{id}/membership/expire', ['as' => 'admin_user_membership_add', 'uses' => 'Users@removeMembership']);
        Route::post('admin/users/search', ['as' => 'admin_users_search', 'uses' => 'Users@search']);
        //Emails
        Route::get('admin/emails', ['as' => 'admin_emails', 'uses' => 'Emails@index']);
        Route::get('admin/emails/create', ['as' => 'admin_emails_create', 'uses' => 'Emails@create']);
        Route::post('admin/emails/create', ['as' => 'admin_emails_store', 'uses' => 'Emails@store']);
        Route::get('admin/email/{id}/edit', ['as' => 'admin_emails_edit', 'uses' => 'Emails@edit']);
        Route::post('admin/email/{id}/update', ['as' => 'admin_emails_update', 'uses' => 'Emails@update']);
        Route::get('admin/email/{id}/delete', ['as' => 'admin_emails_delete', 'uses' => 'Emails@destroy']);
        //News
        Route::get('admin/news/{area?}', ['as' => 'admin_news', 'uses' => 'News@index']);
        Route::post('admin/news', ['as' => 'admin_news_area', 'uses' => 'News@showArea']);
        Route::get('admin/newsItem/create', ['as' => 'admin_news_create', 'uses' => 'News@create']);
        Route::post('admin/newsItem/create', ['as' => 'admin_news_store', 'uses' => 'News@store']);
        Route::get('admin/newsItem/{id}/edit', ['as' => 'admin_news_edit', 'uses' => 'News@edit']);
        Route::post('admin/newsItem/{id}/update', ['as' => 'admin_news_update', 'uses' => 'News@update']);
        Route::get('admin/newsItem/{id}/delete', ['as' => 'admin_news_delete', 'uses' => 'News@destroy']);
        //PageSnippet
        Route::post('admin/pagesnippet', ['as' => 'admin_news_area', 'uses' => 'PageSnippet@showArea']);
        Route::get('admin/pagesnippet/{area?}', ['as' => 'admin_news', 'uses' => 'PageSnippet@index']);
        Route::get('admin/pagesnippet/snippet/create', ['as' => 'admin_news_create', 'uses' => 'PageSnippet@create']);
        Route::post('admin/pagesnippet/snippet/create', ['as' => 'admin_news_store', 'uses' => 'PageSnippet@store']);
        Route::get('admin/pagesnippet/snippet/{id}/edit', ['as' => 'admin_news_edit', 'uses' => 'PageSnippet@edit']);
        Route::post('admin/pagesnippet/snippet/{id}/update', ['as' => 'admin_news_update', 'uses' => 'PageSnippet@update']);
        Route::get('admin/pagesnippet/snippet/{id}/delete', ['as' => 'admin_news_delete', 'uses' => 'PageSnippet@destroy']);
        //Email/
        Route::get('admin/emailer/', ['as' => 'admin_emailer', 'uses' => 'Emailer@index']);
        Route::get('admin/emailer/user/{id}', ['as' => 'admin_emailer', 'uses' => 'Emailer@index']);
        Route::post('admin/emailer', ['as' => 'admin_emailer_send', 'uses' => 'Emailer@send']);
        Route::get('admin/emailer/archive', ['as' => 'admin_email_archive', 'uses' => 'Emailer@archive']);
        Route::get('admin/emailer/archive/{id}', ['as' => 'admin_email_archive_item', 'uses' => 'Emailer@archiveShow']);
        // About
        Route::get('admin/about', ['as' => 'admin_aboutModules', 'uses' => 'AboutModules@index']);
        // Log Viewer
        Route::get('admin/logs', ['as' => 'admin_logviewer', 'uses' => 'LogViewer@index']);

        //Recaptcha
        Route::get('admin/recaptcha/settings', ['as' => 'admin_recaptcha_settings', 'uses' => 'Recaptcha@index']);
        Route::post('admin/recaptcha/settings/update', ['as' => 'admin_recaptcha_settings', 'uses' => 'Recaptcha@update']);
        //Docs
        Route::get('admin/docs', 'Documentation@showRootPage');
        Route::get('admin/docs/{version}/{page?}', 'Documentation@show');
        //Icons
        Route::get('admin/icons', 'Icon@index');
        Route::get('admin/icons/makeJson', 'Icon@makeJson');
        //Activity
        Route::get('admin/activity', ['as' => 'admin_activity', 'uses' => 'Activity@index']);
        Route::get('admin/activity/data/{user?}', ['as' => 'admin_activity_data', 'uses' => 'Activity@data']);
        Route::get('admin/activity/system', ['as' => 'admin_activity_system', 'uses' => 'SystemActivity@index']);
        Route::get('admin/activity/system/data', ['as' => 'admin_activity_system_data', 'uses' => 'SystemActivity@data']);
        //ShortCodes
        Route::get('admin/shortcodes', ['as' => 'admin_shortcodes', 'uses' => 'Shortcode@index']);
        Route::get('admin/shortcodes/create', ['as' => 'admin_shortcodes_create', 'uses' => 'Shortcode@create']);
        Route::post('admin/shortcodes/create', ['as' => 'admin_shortcodes_save', 'uses' => 'Shortcode@store']);
        Route::get('admin/shortcode/{id}/edit', ['as' => 'admin_shortcode_edit', 'uses' => 'Shortcode@edit']);
        Route::post('admin/shortcode/{id}/update', ['as' => 'admin_shortcode_update', 'uses' => 'Shortcode@update']);
        Route::get('admin/shortcode/{id}/delete', ['as' => 'admin_shortcode_destroy', 'uses' => 'Shortcode@destroy']);
        //Notifications
        Route::get('admin/notifications', ['as' => 'admin_notifications', 'uses' => 'NotificationSettings@index']);
        Route::post('admin/notifications/update', ['as' => 'admin_notifications_update', 'uses' => 'NotificationSettings@update']);
        //Membership
        Route::get('admin/membership', ['as' => 'admin_membership', 'uses' => 'Membership@index']);
        Route::get('admin/membership/create', ['as' => 'admin_membership_create', 'uses' => 'Membership@create']);
        Route::post('admin/membership/create', ['as' => 'admin_membership_store', 'uses' => 'Membership@store']);
        Route::get('admin/membership/settings/registration-form', ['as' => 'admin_membership_settings_registration', 'uses' => 'MembershipSettings@registration_form']);
        Route::post('admin/membership/settings/registration-form', ['as' => 'admin_membership_settings_registration_update', 'uses' => 'MembershipSettings@registration_form_update']);

        Route::get('admin/membership/{id}/edit', ['as' => 'admin_membership_create', 'uses' => 'Membership@edit']);
        Route::post('admin/membership/{id}/update', ['as' => 'admin_membership_update', 'uses' => 'Membership@update']);
        Route::get('admin/membership/{id}/delete', ['as' => 'admin_membership_delete', 'uses' => 'Membership@destroy']);
        //Commerce
        Route::get('admin/commerce/settings', ['as' => 'admin_commerce_settings', 'uses' => 'CommerceSettings@index']);
        Route::post('admin/commerce/settings', ['as' => 'admin_commerce_settings_update', 'uses' => 'CommerceSettings@update']);
        Route::get('admin/commerce/logs', ['as' => 'admin_commerce_logs', 'uses' => 'Logs@index']);
        Route::get('admin/commerce/payments/data', ['as' => 'admin_commerce_payments_data', 'uses' => 'Logs@data']);
        Route::get('admin/commerce/payments/data/{user}', ['as' => 'admin_commerce_payments_datauser', 'uses' => 'Logs@dataUser']);

    });

//public routes
    // Authentication Routes...
    Route::get('auth/login', 'Auth\LoginController@showLoginForm');
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('auth/login', 'Auth\LoginController@login');
    Route::get('auth/logout', 'Auth\LogoutController@Logout');
    Route::get('logout', 'Auth\LogoutController@Logout');
    // Password reset link request routes...
    Route::get('password/email', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    // Password reset routes...
    Route::get('password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::group(['namespace' => 'Frontend'], function()
    {
        //Contact
        Route::post('contact', ['as' => 'public_contact_post', 'uses' => 'Contact@message']);
        Route::get('confirm-email/resend', ['as' => 'public_confirm_resend', 'uses' => 'Verify@resend']);
        Route::post('confirm-email/resend', ['as' => 'public_confirm_resent', 'uses' => 'Verify@sent']);
        Route::get('confirm-email/{id}', ['as' => 'public_confirm_email', 'uses' => 'Verify@email']);
        //Membership
        Route::get('register/{membership?}', ['as' => 'register', 'uses' => 'Register@index']);
        Route::post('register', ['as' => 'register_store', 'uses' => 'Register@store']);
    });
    //Firewalled Routes
    #Route::group(['namespace' => 'Frontend', 'middleware' => ['firewall']], function()
	Route::group(['namespace' => 'Frontend'], function()
    {
        //Commerce
        Route::get('cart/add/{model}/{modelId}', ['as' => 'cart_public_add', 'uses' => 'Cart@add']);
        Route::get('payment/{type}/success/{gateway}', ['as' => 'public_payment_success', 'uses' => 'Payment@success']);
        Route::get('payment/{type}/cancel/{gateway}', ['as' => 'public_payment_cancel', 'uses' => 'Payment@cancel']);
        Route::get('payment/{gateway}', ['as' => 'public_payment', 'uses' => 'Payment@index']);
        Route::post('payment/ipn/{gateway}', ['as' => 'public_payment_ipn', 'uses' => 'Payment@ipn']);
        Route::get('payment/ipn/{gateway}', ['as' => 'public_payment_ipn', 'uses' => 'Payment@ipn']);
        Route::post('payment/webhook/{gateway}', ['as' => 'public_payment_webhook', 'uses' => 'Payment@webhook']);
        Route::get('payment/webhook/{gateway}/{type?}', ['as' => 'public_payment_webhook', 'uses' => 'Payment@webhookTest']);

	#Route::post('payment/webhook/{gateway}/gerger', ['as' => 'public_payment_webhook', 'uses' => 'Payment@webhookTest2']);
	#Route::get('payment/webhook/{gateway}/gerger', ['as' => 'public_payment_webhook', 'uses' => 'Payment@webhookTest2']);

    });



});

