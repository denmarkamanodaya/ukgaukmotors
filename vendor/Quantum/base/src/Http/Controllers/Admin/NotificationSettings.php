<?php

namespace Quantum\base\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Http\Requests\UpdateNotificationRequest;
use Quantum\base\Models\NotificationEvents;
use Quantum\base\Models\NotificationTypes;
use Quantum\base\Services\NotificationService;

class NotificationSettings extends Controller
{

    /**
     * @var NotificationService
     */
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Notif_events = NotificationEvents::with('types')->where('area', 'admin')->get();
        $Notif_types = NotificationTypes::get();
        return view('base::admin.Notification.Settings.index', compact('Notif_events', 'Notif_types'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotificationRequest $request)
    {
        $this->notificationService->updateSettings($request);
        return redirect('/admin/notifications');
    }
    
}
