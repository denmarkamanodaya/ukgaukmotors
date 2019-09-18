<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : EmailService.php
 **/

namespace Quantum\base\Services;


use Quantum\base\Mail\General;
use Quantum\base\Models\Emailing;
use Quantum\base\Models\MailLog;
use Quantum\base\Models\Role;
use Quantum\base\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class EmailService
{

    /**
     * @var UserShortcodeService
     */
    private $userShortcodeService;

    protected $user = null;

    protected $sent_user = 0;

    public function __construct(UserShortcodeService $userShortcodeService)
    {
        $this->userShortcodeService = $userShortcodeService;
    }

    public function send_system_mail($user, $systemMail, $request = null, $event = null)
    {
        if(is_array($systemMail))
        {
            $data['to'] = $systemMail['to'];
            $systemMail = $systemMail['mail_template'];
        } else {
            $data['to'] = $user->email;
        }

        if(is_numeric($systemMail))
        {
            $mailContent = Emailing::where('id', $systemMail)->tenant()->firstOrFail();
        }else {
            $mailContent = Emailing::where('title', $systemMail)->tenant()->firstOrFail();
        }
        

        $data['subject'] = $this->userShortcodeService->membersEmail($mailContent->subject, $user);
        $data['content_html'] = $this->userShortcodeService->membersEmail($mailContent->content_html, $user, $event);
        $data['content_text'] = $this->userShortcodeService->membersEmail($mailContent->content_text, $user, $event);
        $data['from'] = \Settings::get('site_email_address');
        $data['from_name'] = \Settings::get('site_email_from_name');
        $data['template'] = 'emails.general';

        if($mailContent->title == 'Welcome Email')
        {
            if($request == null) $request['password'] = 'Password you registered with';
            $data['content_html'] = $this->userShortcodeService->replace($data['content_html'], 'password', $request['password']);
            $data['content_text'] = $this->userShortcodeService->replace($data['content_text'], 'password', $request['password']);
        }

        /*Mail::queue('emails.general', $data, function($message) use($data)
        {
            $message->from($data['from'], $data['from_name']);
            $message->to($data['to']);
            $message->subject($data['subject']);
        });*/

        Mail::to($data['to'])->queue(new General($data));
        
        if(is_array($systemMail))
        {
            $this->mailLog(1, $data);
        } else {
            $this->mailLog($user, $data);
        }

    }

    public function send_email($request)
    {
        if($request->user > 0)
        {
            return $this->send_user($request);
        }

        if(isset($request->roles))
        {
            return $this->send_roles($request);
        }
        if(isset($request->allUsers))
        {
            return $this->send_all_users($request);
        }
        //nothing selected
        flash('Recipients have not been selected.')->error();
        return Redirect::back()->withInput();
    }

    private function send_user($request)
    {
        $user = User::where('id', $request->user)->Active()->firstOrFail();
        $this->sendMail($request, $user);
        flash('Email has been sent.')->success();
        $this->reset_sent();
        return true;
    }

    private function send_roles($request)
    {
        foreach($request->roles as $roleId)
        {
            $role = Role::with(['user' => function($q){
                $q->Active();
            }])->where('id', $roleId)->first();

            foreach($role->user as $user)
            {
                $this->sendMail($request, $user);
            }
        }
        flash('Email has been sent to '.$this->sent_user.' users')->success();
        $this->reset_sent();
        return true;
    }

    private function send_all_users($request)
    {
        $users = User::Active()->get();
        foreach($users as $user)
        {
            $user = User::where('id', $request->user)->firstOrFail();
            $this->sendMail($request, $user);
        }
        flash('Email has been sent to '.$this->sent_user.' users')->success();
        $this->reset_sent();
        return true;
    }
    private function reset_sent()
    {
        $this->sent_user = 0;
    }

    private function sendMail($request, $user)
    {
        $data['subject'] = $this->userShortcodeService->membersEmail($request->subject, $user);
        $data['content_html'] = $this->userShortcodeService->membersEmail($request->content_html, $user);
        $data['content_text'] = $this->userShortcodeService->membersEmail($request->content_html, $user);
        $data['from'] = \Settings::get('site_email_address');
        $data['from_name'] = \Settings::get('site_email_from_name');
        $data['template'] = 'emails.general';

        /*Mail::queue('emails.general', $data, function($message) use($data, $user)
        {
            $message->from($data['from'], $data['from_name']);
            $message->to($user->email);
            $message->subject($data['subject']);
        });*/
        Mail::to($user->email)->queue(new General($data));
        $this->mailLog($user, $data);
        $this->sent_user ++;
    }

    public function send_contact($request)
    {
        $data['subject'] = strip_tags($request->subject);
        $data['content_html'] = nl2br(strip_tags($request->message));
        $data['content_text'] = nl2br(strip_tags($request->message));
        $data['from'] = $request->email;
        $data['to'] = \Settings::get('site_email_address');
        $data['template'] = 'emails.general';

        /*Mail::queue('emails.general', $data, function($message) use($data)
        {
            $message->from($data['from']);
            $message->to($data['to']);
            $message->subject($data['subject']);
        });*/
        Mail::to($data['to'])->queue(new General($data));
        
    }

    private function mailLog($user, $data)
    {
        $mailllog = new MailLog();
        $mailllog->user_id = is_object($user) ? $user->id : $user;
        $mailllog->subject = $data['subject'];
        $mailllog->content_html = $data['content_html'];
        $mailllog->content_text = isset($data['content_text']) ? $data['content_text'] : '';
        $mailllog->save();
    }

}