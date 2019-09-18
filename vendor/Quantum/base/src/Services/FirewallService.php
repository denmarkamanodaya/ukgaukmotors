<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : FirewallService.php
 **/

namespace Quantum\base\Services;

use Quantum\base\Models\Failures;
use Quantum\base\Models\Lockouts;
use Quantum\base\Models\Whitelist;
use Carbon\Carbon;
use Config;
use DB;
use Request;
use Validator;


class FirewallService
{
    /**
     * @var
     */
    protected $ip;
    /**
     * @var
     */
    protected $status;
    /**
     * @var
     */
    protected $reason;
    /**
     * @var bool
     */
    protected $whitelisted = false;

    /**
     * Initialise the firewall
     *
     * @param null $ip
     * @return bool
     */
    public function init($ip = null)
    {
        //$this->cleanup(); //moved to cron
        if($ip == ''){
            $ip = Request::getClientIp();
        }
        $validator = Validator::make(
            array('ip' => $ip),
            array('ip' => array('required', 'ip'))
        );
        if ($validator->fails())
        {
            return false;
        }

        $this->ip = $ip;
        $this->is_whitelisted();
        $this->status = 'allowed';
    }

    /**
     * Log a firewall failure
     *
     * @param string $reason
     * @return bool
     */
    public function failure($reason = '')
    {
        $this->reason = $reason;
        if($this->ip == '') return false;
        if($this->status == 'ban') return $this->status;
        if($this->whitelisted) return false;
        $this->do_failure();
        return $this->status;
    }


    /**
     * Check if banned
     *
     * @return bool
     */
    public function is_banned()
    {
        $count = Lockouts::where('ip_address', '=', $this->ip)->count();
        if($count > 0)
        {
            $this->status = "banned";
            return true;
        }
        return false;
    }

    /**
     * Count the failures
     *
     * @return mixed
     */
    private function count()
    {
        $count = Failures::where('ip_address', '=', $this->ip)->count();
        return $count;
    }


    /**
     *Check for ban
     */
    private function is_ban()
    {
        $count = $this->count();
        if($count >= Config::get('firewall.failures_limit'))
        {
            $this->do_ban();
        }
        return;
    }


    /**
     *Ban an ip
     */
    private function do_ban()
    {
        $lockouts = new Lockouts();
        $lockouts->ip_address = $this->ip;
        $lockouts->info = 'Lockout limit reached';
        $lockouts->released = Carbon::now()->addMinutes(Config::get('firewall.ban_time'));
        $lockouts->save();
        $this->status = "banned";
        DB::table('failures')->where( 'ip_address', '=', $this->ip )->delete();
        return;
    }

    /**
     *Fail an ip
     */
    private function do_failure()
    {
        $failures = new Failures();
        $failures->ip_address = $this->ip;
        $failures->info = $this->reason;
        $failures->released = Carbon::now()->addMinutes(Config::get('firewall.failures_time'));
        $failures->save();

        $this->is_ban();
        return;
    }

    /**
     * Release bans and failures
     */
    public function cleanup()
    {
        DB::table('failures')->where( 'released', '<', Carbon::now() )->delete();
        DB::table('lockouts')->where( 'released', '<', Carbon::now() )->delete();
    }

    /**
     * Check if whitelisted
     */
    private function is_whitelisted()
    {
        $whiteCount = Whitelist::where('ip_address', '=', $this->ip)->count();
        if($whiteCount > 0) $this->whitelisted = true;
        return;
    }

    public function login()
    {
        DB::table('failures')->where( 'ip_address', '=', $this->ip )->delete();
    }
}