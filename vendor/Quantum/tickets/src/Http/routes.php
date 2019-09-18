<?php

Route::group(['namespace' => 'Quantum\tickets\Http\Controllers', 'middleware' => ['web']], function()
{

    //Member Routes
    Route::group(['namespace' => 'Members', 'middleware' => ['auth']], function()
    {
        Route::get('members/support', ['as' => 'members_support', 'uses' => 'Tickets@index']);
        Route::get('members/support/createTicket', ['as' => 'members_support_create', 'uses' => 'Tickets@create']);
        Route::post('members/support/createTicket', ['as' => 'members_support_create', 'uses' => 'Tickets@store']);
        Route::get('members/support/openTickets', ['as' => 'members_support_openTickets', 'uses' => 'Tickets@openTickets']);
        Route::get('members/support/closedTickets', ['as' => 'members_support_closedTickets', 'uses' => 'Tickets@closedTickets']);
        Route::get('members/support/ticket/{id}', ['as' => 'members_support_ticket', 'uses' => 'Tickets@show']);
        Route::post('members/support/ticket/{id}/reply', ['as' => 'members_support_ticket_reply', 'uses' => 'Tickets@reply']);
    });
    
    //Admin Routes
    Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function()
    {
        Route::get('admin/ticket/settings', ['as' => 'admin_tickets_settings', 'uses' => 'TicketSettings@index']);
        Route::post('admin/ticket/settings', ['as' => 'admin_tickets_settings_update', 'uses' => 'TicketSettings@update']);

        Route::get('admin/tickets', ['as' => 'admin_tickets', 'uses' => 'Tickets@index']);
        Route::get('admin/tickets/openTickets', ['as' => 'admin_tickets_openTickets', 'uses' => 'Tickets@openTickets']);
        Route::get('admin/tickets/repliedTickets', ['as' => 'admin_tickets_repliedTickets', 'uses' => 'Tickets@repliedTickets']);
        Route::get('admin/tickets/closedTickets', ['as' => 'admin_tickets_closedTickets', 'uses' => 'Tickets@closedTickets']);
        Route::post('admin/tickets/deleteTickets', ['as' => 'admin_tickets_deleteTickets', 'uses' => 'Tickets@deleteTickets']);
        Route::get('admin/tickets/ticket/{id}', ['as' => 'admin_tickets_ticket', 'uses' => 'Tickets@show']);
        Route::post('admin/tickets/ticket/{id}/reply', ['as' => 'admin_tickets_ticket_reply', 'uses' => 'Tickets@reply']);
        Route::post('admin/tickets/ticket/{id}/changeStatus', ['as' => 'admin_tickets_ticket_changeStatus', 'uses' => 'Tickets@changeStatus']);
        Route::post('admin/tickets/ticket/{id}/delete', ['as' => 'admin_tickets_ticket_delete', 'uses' => 'Tickets@delete']);

        Route::get('admin/ticket/departments', ['as' => 'admin_ticket_departments', 'uses' => 'Departments@index']);
        Route::post('admin/ticket/department/create', ['as' => 'admin_ticket_department_create', 'uses' => 'Departments@store']);
        Route::post('admin/ticket/department/{id}/update', ['as' => 'admin_ticket_department_update', 'uses' => 'Departments@update']);
        Route::get('admin/ticket/department/{id}/edit', ['as' => 'admin_ticket_department_edit', 'uses' => 'Departments@edit']);
        Route::post('admin/ticket/department/{id}/delete', ['as' => 'admin_ticket_department_delete', 'uses' => 'Departments@destroy']);

        Route::get('admin/ticket/statuses', ['as' => 'admin_ticket_statuses', 'uses' => 'TicketStatus@index']);
        Route::post('admin/ticket/status/create', ['as' => 'admin_ticket_status_create', 'uses' => 'TicketStatus@store']);
        Route::post('admin/ticket/status/{id}/update', ['as' => 'admin_ticket_status_update', 'uses' => 'TicketStatus@update']);
        Route::get('admin/ticket/status/{id}/edit', ['as' => 'admin_ticket_status_edit', 'uses' => 'TicketStatus@edit']);
        Route::post('admin/ticket/status/{id}/delete', ['as' => 'admin_ticket_status_delete', 'uses' => 'TicketStatus@destroy']);
    });
    
    //public routes
    
    Route::group(['namespace' => 'Frontend', 'middleware' => ['firewall']], function()
    {
        Route::get('support', ['as' => 'public_support', 'uses' => 'Tickets@index']);
        Route::post('support', ['as' => 'public_support_create', 'uses' => 'Tickets@store']);
        Route::get('support/ticket/{id}', ['as' => 'public_support_show_ticket', 'uses' => 'Tickets@show']);
        Route::post('support/ticket/{id}/reply', ['as' => 'members_support_ticket_reply', 'uses' => 'Tickets@reply']);

    });

});