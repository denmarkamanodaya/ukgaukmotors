<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : NewsService.php
 **/

namespace Quantum\base\Services;


use Quantum\base\Models\News;

class NewsService
{

    public function CreateNewsItem($request)
    {
        $newsItem = News::create($request->all());
        $this->syncRoles($newsItem, $request);
        if($request->type == 'news')
        {
            flash('News has been created.')->success();
            \Activitylogger::log('Admin - Created News : '.$newsItem->title, $newsItem);
        } else {
            flash('Snippet has been created.')->success();
            \Activitylogger::log('Admin - Created Page Snippet : '.$newsItem->title, $newsItem);
        }

        $this->cacheClear();
        return $newsItem;
    }

    private function syncRoles($newsItem, $request)
    {
        if($newsItem->area == 'public')
        {
            $newsItem->roles()->detach();
        }
        else {
            if(is_array($request->roles)) {
                $newsItem->roles()->sync($request->roles);
            } else {
                $newsItem->roles()->detach();
            }
        }
    }

    public function roleArray($newsItem)
    {
        $roleArray = [];
        foreach($newsItem->roles as $role)
        {
            array_push($roleArray, $role->id);
        }
        return $roleArray;
    }

    public function updateNewsItem($id, $request)
    {
        $newsItem = News::tenant()->where('id', $id)->firstOrFail();
        if($newsItem->system == 1)
        {
            $newsItem->update($request->except('title'));
        } else {
            $newsItem->update($request->all());
        }

        $this->syncRoles($newsItem, $request);

        if($request->type == 'news')
        {
            flash('News Item has been updated')->success();
            \Activitylogger::log('Admin - Updated News : '.$newsItem->title, $newsItem);
            $this->cacheClear();
        } else {
            flash('Snippet has been updated.')->success();
            \Activitylogger::log('Admin - Updated Page Snippet : '.$newsItem->title, $newsItem);
            $this->cacheClear($newsItem->title);
        }

        return $newsItem;
    }

    public function deleteNewsItem($id)
    {
        $newsItem = News::tenant()->where('id', $id)->firstOrFail();
        if($newsItem->system == 1)
        {
            flash('This can not be deleted')->error();
            return $newsItem->area;
        }
        $area = $newsItem->area;

        $newsItem->delete();

        if($newsItem->type == 'news')
        {
            flash('News Item has been deleted')->success();
            \Activitylogger::log('Admin - Deleted News : '.$newsItem->title, $newsItem);
            $this->cacheClear();
        } else {
            flash('Snippet has been deleted.')->success();
            \Activitylogger::log('Admin - Deleted Page Snippet : '.$newsItem->title, $newsItem);
            $this->cacheClear($newsItem->title);
        }
        return $area;
    }

    public function getSnippet($title, $area='members')
    {
        if(!in_array($area, ['members', 'public', 'admin'])) return null;

        if(is_null($title) || $title=='') return null;
        $snippet = \Cache::rememberForever('page_snippet_'.str_slug($title), function() use($title, $area) {
            return News::Area($area)->where('type', 'snippet')->where('title', $title)->tenant()->Published()->first();
        });

        return $snippet;
    }

    public function cacheClear($title=null)
    {
        \Cache::forget('news_members');
        \Cache::forget('news_public');
        \Cache::forget('news_admin');
        if(!is_null($title)) \Cache::forget('page_snippet_'.str_slug($title));
    }

}