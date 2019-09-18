<?php
namespace App\Http\ViewComposers;
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : MyGarageViewComposer.php
 **/

use Illuminate\Contracts\View\View;

class MyGarageViewComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('Template', 'FullWithSide');
    }
}