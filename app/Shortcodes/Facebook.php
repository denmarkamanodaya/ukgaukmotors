<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Facebook.php
 **/

namespace App\Shortcodes;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Quantum\base\Models\Transactions;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Facebook
{
    public static function facebookSale(ShortcodeInterface $s)
    {
        $user = \Auth::user();
        if(!$user) return '';
        if($transaction = Transactions::where('user_id', $user->id)->where('type', 'sale')->where('created_at', '>=', Carbon::now()->subMinutes(5))->orderBy('id', 'DESC')->first())
        {
            $fb_out_id = Session::get('fb_out_id');
            if($fb_out_id && $fb_out_id == $transaction->id) return '';

            if($transaction->amount != '0')
            {
                $out = "<script>
fbq('track', 'Purchase', {
value: $transaction->amount,
currency: 'GBP'
});
</script>";
                Session::set('fb_out_id', $transaction->id);
                return $out;
            }

        }
        return '';
    }
}