<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\HelpText;
use Quantum\base\Models\MailLog;
use Quantum\base\Models\Role;
use Quantum\base\Models\User;
use Quantum\base\Services\EmailService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class Emailer extends Controller
{

    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId = 0)
    {
        $users = User::orderBy('id', 'ASC')->pluck('username', 'id')->toArray();
        array_unshift($users, "Select A User");
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->orderBy('title', 'ASC')->get();
        $helptext['page_content'] = HelpText::slug('emails')->first();
        
        return view('base::admin.Emailer.index', compact('users', 'roles', 'helptext', 'userId'));
    }

    public function send(\Quantum\base\Http\Requests\Admin\SendEmailRequest $request)
    {
        $emailer = $this->emailService->send_email($request);
        return redirect('/admin/emailer');
    }

    public function archive()
    {
        $emails = MailLog::with('user')->orderBy('created_at', 'DESC')->paginate(40);
        return view('base::admin.Emailer.archive', compact('emails'));
    }

    public function archiveShow($id)
    {
        $email = MailLog::where('id', $id)->firstOrFail();
        return view('base::admin.Emailer.archiveShow', compact('email'));
    }
}
