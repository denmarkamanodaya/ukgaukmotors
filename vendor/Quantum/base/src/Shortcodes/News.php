<?php

namespace Quantum\base\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Quantum\base\Models\News as NewsModal;

class News
{
    public static function news(ShortcodeInterface $s)
    {
        $news = \Cache::rememberForever('news_members', function() {
            return NewsModal::Area('members')->where('type', 'news')->Published()->tenant()->latest()->get();
        });
        $view = \View::make('base::admin.Shortcodes.Widgets.News', compact('news'));
        $widget = $view->render();
        
        return $widget;
    }

    public static function pageSnippet(ShortcodeInterface $s)
    {
        $title = null;
        $area = 'members';
        if($s->getParameter('title'))
        {
            $title = $s->getParameter('title');
        }
        if($s->getParameter('area'))
        {
            $area = $s->getParameter('area');
            if(!in_array($area, ['members', 'public', 'admin'])) return null;
        }
        if(is_null($title)) return null;
        $snippet = \Cache::rememberForever('page_snippet_'.str_slug($title), function() use($title, $area) {
            return NewsModal::Area($area)->where('type', 'snippet')->where('title', $title)->tenant()->Published()->get();
        });

        return $snippet;
    }



}