<?php
namespace Quantum\tickets\Services;


use Quantum\tickets\Models\TicketDepartment;
use Quantum\tickets\Models\TicketStatus;

class TicketSettingsService
{

    public function getStatuses()
    {
        $statuses = TicketStatus::orderBy('name', 'ASC')->get();
        return $statuses;
    }

    public function getStatus($id)
    {
        $status = TicketStatus::where('id', $id)->firstOrFail();
        return $status;
    }

    public function getStatusBySlug($id)
    {
        $status = TicketStatus::where('slug', $id)->firstOrFail();
        return $status;
    }

    public function getStatusList()
    {
        $statuses = TicketStatus::orderBy('name', 'ASC')->pluck('name', 'id');
        return $statuses;
    }

    public function createStatus($request)
    {
        $status = TicketStatus::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'system' => 0,
            'default' => $request->default ?: 0,
            'colour' => '#000000'
        ]);
        if($request->default == 1) TicketStatus::where('id', '!=', $status->id)->update(['default' => 0]);
        flash('Status has been created.')->success();
        \Activitylogger::log('Admin - Created Ticket Status : '.$status->name, $status);
        return $status;
    }

    public function editStatus($request, $id)
    {
        $status = $this->getStatusBySlug($id);
        $status->name = $request->name;
        $status->description = $request->description;
        $status->icon = $request->icon;
        $status->system = 0;
        $status->default = $request->default ?: 0;
        $status->colour = '#000000';
        if($status->system == 1) $status->slug = $id;
        $status->save();
        if($request->default == 1) TicketStatus::where('id', '!=', $status->id)->update(['default' => 0]);
        flash('Status has been updated.')->success();
        \Activitylogger::log('Admin - Updated Ticket Status : '.$status->name, $status);
        return $status;
    }

    public function deleteStatus($id)
    {
        $status = $this->getStatusBySlug($id);
        if($status->default == 1) TicketStatus::orderBy('id')->first()->update(['default' => 1]);
        $status->delete();
        flash('Status has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Ticket Status : '.$status->name, $status);
    }


    public function getDepartments()
    {
        $statuses = TicketDepartment::orderBy('name', 'ASC')->get();
        return $statuses;
    }

    public function getDepartmentsList()
    {
        $departments = TicketDepartment::pluck('name', 'id');
        return $departments;
    }

    public function getDepartment($id)
    {
        $status = TicketDepartment::where('id', $id)->firstOrFail();
        return $status;
    }

    public function getDepartmentBySlug($id)
    {
        $status = TicketDepartment::where('slug', $id)->firstOrFail();
        return $status;
    }

    public function createDepartment($request)
    {
        $department = TicketDepartment::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'system' => 0,
            'default' => $request->default ?: 0,
            'colour' => '#000000'
        ]);
        if($request->default == 1) TicketDepartment::where('id', '!=', $department->id)->update(['default' => 0]);
        flash('Department has been created.')->success();
        \Activitylogger::log('Admin - Created Ticket Department : '.$department->name, $department);
        return $department;
    }

    public function editDepartment($request, $id)
    {
        $department = $this->getDepartmentBySlug($id);
        $department->name = $request->name;
        $department->description = $request->description;
        $department->icon = $request->icon;
        $department->system = 0;
        $department->default = $request->default ?: 0;
        $department->colour = '#000000';
        $department->save();
        if($request->default == 1) TicketDepartment::where('id', '!=', $department->id)->update(['default' => 0]);
        flash('Department has been updated.')->success();
        \Activitylogger::log('Admin - Updated Ticket Department : '.$department->name, $department);
        return $department;
    }

    public function deleteDepartment($id)
    {
        $department = $this->getDepartmentBySlug($id);
        if($department->default == 1) TicketDepartment::orderBy('id')->first()->update(['default' => 1]);
        $department->delete();
        flash('Department has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Ticket Department : '.$department->name, $department);
    }

    public function updateSettings($request)
    {
        $setting = \Quantum\base\Models\Settings::where('name', 'ticket_email_from_address')->tenant()->firstOrFail();
        $setting->data = $request->ticket_email_from_address;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'ticket_public_received_page')->tenant()->firstOrFail();
        $setting->data = $request->ticket_public_received_page;
        $setting->save();

        $this->clearCache();

        flash('Settings updated.')->success();
        \Activitylogger::log('Admin - Updated Ticket Settings', $setting);
    }

    private function clearCache()
    {
        \Cache::forget('site.settings');
        \Countries::clearCache();
    }

}