<?php

namespace Quantum\base\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class MembersComposer
{

    protected $user;

    public function __construct()
    {
        $this->user = \Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('user', $this->user);
    }
}