<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : RestrictUserService.php
 **/

namespace App\Services;


use Illuminate\Support\Facades\Session;

class RestrictUserService
{
    public function restrictView($sessionName)
    {

        if($this->is_bot()) return false;
        if ($searches = session($sessionName)) {
            if($searches > 3) return true;
        }

        return false;
    }

    public function updateCount($sessionName)
    {
        if ($searches = session($sessionName)) {
            $searches ++;
        } else {
            $searches = 1;
        }
        session([$sessionName => $searches]);
    }


    public function restrictViewMembers($sessionName)
    {
	    #dd(\Auth::user());
	    if($this->is_bot()) return false;
	    if($searches = session($sessionName))
	    {
		    if($searches > 19) return true;
	    }

	    return false;
    }		

    public function updateCountMembers($sessionName)
    {
	if($searches = session($sessionName))
	{
		$searches ++;
	}
	else
	{
		$searches = 1;
	}

	session([$sessionName => $searches]);
    }

    private function is_bot()
    {
        if(!isset($_SERVER['HTTP_USER_AGENT'])) return false;

        $bots = array(
            'Googlebot',
            'Baiduspider',
            'ia_archiver',
            'R6_FeedFetcher',
            'NetcraftSurveyAgent',
            'Sogou web spider',
            'bingbot',
            'Yahoo! Slurp',
            'facebookexternalhit',
            'PrintfulBot',
            'msnbot',
            'Twitterbot',
            'UnwindFetchor',
            'urlresolver',
            'Butterfly',
            'TweetmemeBot');

        foreach($bots as $b){
            if( stripos( $_SERVER['HTTP_USER_AGENT'], $b ) !== false ) return true;
        }
        return false;
    }

}
