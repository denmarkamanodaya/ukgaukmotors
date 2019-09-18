<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Http\Requests\Admin\UpdateEmailsRequest;
use Quantum\base\Models\Emailing;
use Quantum\base\Models\HelpText;
use Illuminate\Http\Request;

use Quantum\base\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class Emails extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = Emailing::tenant()->paginate(20);
        return view('base::admin.Emails.index', compact('emails'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email = Emailing::where('id', $id)->tenant()->firstOrFail();
        $helptext['page_content'] = HelpText::slug('emails')->first();
        return view('base::admin.Emails.edit', compact('email', 'helptext'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmailsRequest $request, $id)
    {
        $email = Emailing::where('id', $id)->tenant()->firstOrFail();
        $email->update($request->all());
        Flash::success('Email has been updated');
        \Activitylogger::log('Admin - Updated email : '.$email->title, $email);
        return redirect('/admin/email/'.$id.'/edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $helptext['page_content'] = HelpText::slug('emails')->first();
        return view('base::admin.Emails.create', compact('helptext'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\CreateEmailsRequest $request)
    {
        $emails = Emailing::create($request->all());
        $emails->system = 0;
        $emails->save();
        Flash::success('Email has been created');
        \Activitylogger::log('Admin - Created email : '.$emails->title, $emails);
        return redirect('/admin/emails');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $email = Emailing::where('id', $id)->where('system', '0')->tenant()->firstOrFail();
        $email->delete();
        Flash::success('Email has been deleted');
        \Activitylogger::log('Admin - Deleted email : '.$email->title, $email);
        return redirect('/admin/emails');
    }

}
