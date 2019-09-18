<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\Failures;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Quantum\base\Http\Requests\Admin\AddWhitelist;
use Quantum\base\Http\Requests\Admin\RemoveBlocked;
use Quantum\base\Http\Requests\Admin\RemoveFailure;
use Quantum\base\Http\Requests\Admin\RemoveWhitelisted;
use Quantum\base\Models\Lockouts;
use Quantum\base\Models\Whitelist;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

/**
 * Class Firewall
 * @package App\Http\Controllers\Admin
 */
class Firewall extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return view('base::admin.Firewall.List');
	}

    /**
     * List all blocked IP addresses
     *
     * @return \Illuminate\View\View
     */
    public function show_blocked()
    {
        $lockouts = Lockouts::paginate(15);
        return view('base::admin.Firewall.ListBlocked',compact('lockouts'));
    }

    /**
     * List all failed IP addresses
     *
     * @return \Illuminate\View\View
     */
    public function show_failed()
    {
        $failures = Failures::paginate(15);
        return view('base::admin.Firewall.ListFailing',compact('failures'));
    }

    /**
     * List all whitelisted IP addresses
     *
     * @return \Illuminate\View\View
     */
    public function show_whitelisted()
    {
        $whitelisted = Whitelist::paginate(15);
        return view('base::admin.Firewall.ListWhitelisted',compact('whitelisted'));
    }

    /**
     * @param RemoveFailure $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove_failure(RemoveFailure $request)
    {
        Failures::whereId($request->id)->delete();
        Flash::success('Success : Ip has been removed.');
        return redirect('admin/firewall/failure');
    }

    /**
     * @param RemoveBlocked $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove_blocked(RemoveBlocked $request)
    {
        Lockouts::whereId($request->id)->delete();
        Flash::success('Success : Ip has been removed.');
        return redirect('admin/firewall/blocked');
    }

    /**
     * @param RemoveWhitelisted $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove_whitelist(RemoveWhitelisted $request)
    {
        Whitelist::whereId($request->id)->delete();
        Flash::success('Success : Ip has been removed.');
        return redirect('admin/firewall/whitelisted');
    }

    /**
     * @param AddWhitelist $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add_whitelist(AddWhitelist $request)
    {
        Whitelist::create($request->all());
        Flash::success('Success : Ip has been added.');
        return redirect('admin/firewall/whitelisted');

    }




}
