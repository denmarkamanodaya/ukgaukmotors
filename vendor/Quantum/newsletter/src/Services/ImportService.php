<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ImportService.php
 **/

namespace Quantum\newsletter\Services;


use Carbon\Carbon;
use Quantum\base\Models\User;
use Quantum\newsletter\Models\NewsletterImportQueue;
use Quantum\newsletter\Models\NewsletterSubscriber;

class ImportService
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var NewsletterEmailService
     */
    private $newsletterEmailService;

    public function __construct(NewsletterService $newsletterService, NewsletterEmailService $newsletterEmailService)
    {
        $this->newsletterService = $newsletterService;
        $this->newsletterEmailService = $newsletterEmailService;
    }

    public function getAllQueued()
    {
        $queued = NewsletterImportQueue::with('newsletter')->orderBy('id', 'DESC')->get();
        return $queued;
    }

    public function import($request)
    {
        $imported = 0;
        if(!$request->file('importcsv')) return false;
        $this->newsletter = $this->newsletterService->getNewsletterById($request->newsletter, ['noJoin', 'roles']);

        $csvfile = $request->file('importcsv');
        $name = time() . '-' . $csvfile->getClientOriginalName();

        // Move file to storage on server
        $csvfile->move(storage_path(), $name);

        if($request->large_import && $request->large_import == 1)
        {
            NewsletterImportQueue::create([
                'newsletter_id' => $this->newsletter->id,
                'start_responder' => ($request->start_responder && $request->start_responder == '1') ? 1 : 0,
                'send_welcome' => ($request->send_welcome && $request->send_welcome == '1') ? 1 : 0,
                'csvfile' => $name,
                'startAt' => 0,
                'error' => 0,
                'completed' => 0
            ]);
            flash('Import has been queued.')->success();
            return 0;
        }

        $handle = fopen(storage_path($name), "r");
        $header = true;

        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($header) {
                $header = false;
            } else {
                if($this->importSubscriber($csvLine, $request)) $imported ++;
            }
        }
        fclose($handle);
        @unlink(storage_path($name));
        return $imported;

    }

    public function runImportQueue()
    {
        $queue = NewsletterImportQueue::where('error', 0)->where('completed', 0)->orderBy('id', 'DESC')->first();
        if(!$queue) return;
        if(!file_exists(storage_path($queue->csvfile))) return;

        $this->newsletter = $this->newsletterService->getNewsletterById($queue->newsletter_id, ['noJoin', 'roles']);

        $imported = 0;

        $handle = fopen(storage_path($queue->csvfile), "r");
        $header = true;

        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($header) {
                $header = false;
            } else {
                try {
                    if ($this->importSubscriber($csvLine, $queue)) {
                        $imported ++;
                        //update log every 100 records
                        if($imported %100 == 0) {
                            $queue->startAt = $imported;
                            $queue->save();
                        }
                    }
                } catch (\Exception $e) {
                    dd($e);
                    $queue->startAt = $imported;
                    $queue->error = 1;
                    $queue->save();
                }
            }
        }
        fclose($handle);
        $queue->startAt = $imported;
        $queue->completed = 1;
        $queue->save();

        @unlink(storage_path($queue->csvfile));
        \Activitylogger::log('Imported Newsletter Subscriber : '.$imported, $queue);
    }

    private function importSubscriber($csvline, $request)
    {
        if (!filter_var($csvline[0], FILTER_VALIDATE_EMAIL)) return false;

        //get any subscriptions
        $currentSubscriber = NewsletterSubscriber::where('email', $csvline[0])->get();
        $user = User::where('email', $csvline[0])->first();

        //check if subscribed
        if($currentSubscriber)
        {
            if($currentSubscriber->contains('newsletter_id', $this->newsletter->id)) return false;
        }

        //check if has roles
        $allowed = false;
        if($this->newsletter->roles)
        {
            foreach ($this->newsletter->roles as $role)
            {
                if($role->name == 'guest' && !$user) $allowed = true;
                if($user && $user->hasRole($role->name)) $allowed = true;
            }
        }
        if(!$allowed) return false;

        //no join check
        if($this->newsletter->noJoin)
        {
            $allowed = true;
            foreach ($this->newsletter->noJoin as $noJoin)
            {
                if($currentSubscriber)
                {
                    if($currentSubscriber->contains('newsletter_id', $noJoin->id)) $allowed = false;
                }
            }
            if(!$allowed) return false;
        }

        //set responder sequence
        $sequence = 0;
        $sequence_send_on = null;
        if($request->start_responder && $request->start_responder == '1')
        {
            if($responder = $this->newsletter->mails->where('message_type', 'responder')->sortBy('position')->first())
            {
                $sequence = 1;
                if($responder->interval_type == 'Minutes') $sequence_send_on = Carbon::now()->addMinutes($responder->interval_amount);
                if($responder->interval_type == 'Hours') $sequence_send_on = Carbon::now()->addHours($responder->interval_amount);
                if($responder->interval_type == 'Days') $sequence_send_on = Carbon::now()->addDays($responder->interval_amount);
                if($responder->interval_type == 'Weeks') $sequence_send_on = Carbon::now()->addWeeks($responder->interval_amount);
                if($responder->interval_type == 'Months') $sequence_send_on = Carbon::now()->addMonths($responder->interval_amount);
                if($responder->interval_type == 'Years') $sequence_send_on = Carbon::now()->addYears($responder->interval_amount);
            }
        }

        //signup
        $subscriber = NewsletterSubscriber::create([
            'newsletter_id' => $this->newsletter->id,
            'user_id' => isset($user) ? $user->id : null,
            'first_name' => isset($csvline[1]) ? $csvline[1] : null,
            'last_name' => isset($csvline[2]) ? $csvline[2] : null,
            'email' => $csvline[0],
            'email_confirmed' => 1,
            'sequence' => $sequence,
            'sequence_send_on' => $sequence_send_on,
            'newsletter_import_id' => null,
            'sub_code' => str_random(40)
        ]);
        $subscriber->user = $user;
        $subscriber->newsletter = $this->newsletter;

        //send welcome email
        if($request->send_welcome && $request->send_welcome == '1')
        {
            if($message = $this->newsletter->mails->where('message_type', 'welcome_email')->sortBy('position')->first())
            {
                $this->newsletterEmailService->sendEmail($message, $subscriber);
            }
        }

        return true;

    }

}