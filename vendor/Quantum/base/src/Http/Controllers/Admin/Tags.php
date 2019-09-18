<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Categories.php
 **/

namespace Quantum\base\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Quantum\base\Models\Tags as TagsModel;
use Yajra\DataTables\Facades\DataTables;

class Tags extends Controller
{

    public function index()
    {
        return view('base::admin.Tags.index');
    }

    public function data()
    {
            $tags = \Cache::rememberForever('site_tags', function() {
                return TagsModel::where('user_id', 0)->tenant()->orderBy('created_at', 'DESC')->get();
            });
        
        return Datatables::of($tags)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->addColumn('action', function ($tag) {
                return '<a href="'.url('admin/tags/'.$tag->slug.'/delete').'" class="btn bg-warning btn-labeled" type="button"><b><i class="far fa-times"></i></b> Delete</a>';
            })
            ->make(true);
    }

    public function delete($id)
    {
        $tag = TagsModel::where('slug', $id)->where('user_id', 0)->tenant()->firstOrFail();
        $tag->delete();
        
        Flash::success('Tag has been deleted.');
        \Activitylogger::log('Admin - Tag Deleted : '.$tag->name, $tag);
        \Cache::forget('site_tags');
        return redirect('admin/tags');
    }


}