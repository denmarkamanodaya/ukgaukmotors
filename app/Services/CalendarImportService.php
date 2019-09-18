<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : CalendarImportService.php
 **/

namespace App\Services;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Quantum\base\Models\Import;
use Quantum\base\Models\ImportCategories;

class CalendarImportService
{
    protected $xmlContents;

    protected $imported = 0;

    protected $posts=[];

    public function import_list($searchTerm)
    {
        $posts = Import::whereHas('categories', function ($query) use($searchTerm){
            $query->where('name', $searchTerm);
        })->with('categories')->where('import_area', 'auctioneer_event')->where('import_type', 'wp')->where('complete', 0)->paginate(25);


        return $posts;
    }

    public function importFromFile()
    {
        if(!$this->processImportFile()) return false;
        return $this->imported;
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    private function processImportFile()
    {
        $exists = Storage::exists('calendarImport.xml');
        if(!$exists) return false;
        $this->xmlContents = Storage::get('calendarImport.xml');
        $xml = simplexml_load_string($this->xmlContents);
        $namespaces = $xml->getDocNamespaces();

        foreach ( $xml->channel->item as $item ) {

            $post = [];
            $post['post_title'] = (string) $item->title;
            $post['guid'] = (string) $item->guid;

            $dc = $item->children( 'http://purl.org/dc/elements/1.1/' );
            $post['post_author'] = (string) $dc->creator;
            $content = $item->children( 'http://purl.org/rss/1.0/modules/content/' );
            $excerpt = $item->children( $namespaces['excerpt'] );
            $post['post_content'] = (string) $content->encoded;
            $post['post_excerpt'] = (string) $excerpt->encoded;
            $wp = $item->children( $namespaces['wp'] );
            $post['post_id'] = (int) $wp->post_id;
            $post['post_date'] = (string) $wp->post_date;
            $post['post_date_gmt'] = (string) $wp->post_date_gmt;
            $post['comment_status'] = (string) $wp->comment_status;
            $post['ping_status'] = (string) $wp->ping_status;
            $post['post_name'] = (string) $wp->post_name;
            $post['status'] = (string) $wp->status;
            $post['post_parent'] = (int) $wp->post_parent;
            $post['menu_order'] = (int) $wp->menu_order;
            $post['post_type'] = (string) $wp->post_type;
            $post['post_password'] = (string) $wp->post_password;
            $post['is_sticky'] = (int) $wp->is_sticky;

            foreach ( $item->category as $c ) {
                $att = $c->attributes();
                if ( isset( $att['nicename'] ) )
                    $post['terms'][] = array(
                        'name' => (string) $c,
                        'slug' => (string) $att['nicename'],
                        'domain' => (string) $att['domain']
                    );
            }

            foreach ( $wp->postmeta as $meta ) {
                $post['postmeta'][] = array(
                    'key' => (string) $meta->meta_key,
                    'value' => (string) $meta->meta_value
                );
            }
            if(isset($post['terms'])) $post['terms'] = collect($post['terms']);

            $this->saveImport($post);
            //array_push($this->posts, collect($post));
        }
        //$this->posts = collect($this->posts);
        return true;
    }

    private function saveImport($post)
    {
        $import = Import::create([
            'title' => $post['post_title'],
            'import_area' => 'auctioneer_event',
            'import_type' => 'wp',
            'content' => serialize($post),
            'complete' => 0
        ]);

        if(isset($post['terms']) && count($post['terms']) > 0)
        {
            foreach($post['terms'] as $term)
            {
                if(!isset($term['slug'])) continue;
                ImportCategories::create([
                   'import_id' => $import->id,
                   'name' => $term['slug']
                ]);
            }
        }



        $this->imported ++;
    }

}