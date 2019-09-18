<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : BlogService.php
 **/

namespace Quantum\blog\Services;


use Cache;
use Illuminate\Support\Facades\Input;
use Quantum\base\Models\User;
use Quantum\base\Services\AclService;
use Quantum\base\Services\CategoryService;
use Quantum\blog\Models\PostMeta;
use Quantum\blog\Models\PostRevisions;
use Quantum\blog\Models\Posts;
use Carbon\Carbon;
use Quantum\base\Models\Tags;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class BlogService
{

    /**
     * @var CategoryService
     */
    private $categoryService;

    protected $roles;
    /**
     * @var AclService
     */
    private $aclService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->aclService = new AclService();
        $this->roles = $this->aclService->getAllRoles();
    }

    public function createPost($request, $category='blog')
    {
        $post = new Posts;
        $post = $this->setPost($post, $request, 'create',$category);
        
        flash('Post has been created.')->success();
        \Activitylogger::log('Admin - Created Post : '.$post->title, $post);
        return $post;
    }


    private function syncRoles($post, $request)
    {
        if(!$guest = $this->roles->where('name', 'guest')->first()) abort(404);
        if(!$member = $this->roles->where('name', 'member')->first()) abort(404);

        if($post->area == 'public')
        {
            $post->roles()->sync([$guest->id]);
        } else {
            //members
            $roles = $request->roles;
            if(is_array($request->roles)) {
                if(!in_array($member->id, $request->roles)) array_push($roles, $member->id);
                $post->roles()->sync($roles);
            } else {
                $post->roles()->sync($member->id);
            }
        }
    }
    
    public function getPostBySlug($slug, $category='blog')
    {
        $parentCategory = $this->categoryService->getCategoryBySlug($category);
        return Posts::with('meta', 'tags', 'roles')->tenant()->where('slug', $slug)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->firstOrFail();
    }
    
    private function cache_clear()
    {
        \Cache::forget('post_list');
        \Cache::forget('blog_tags');

        if($this->checkForCacheTags())
        {
            Cache::tags('posts')->flush();
            Cache::tags('posts_search')->flush();
            Cache::tags('latest_posts')->flush();
        }
    }
    
    public function updatePost($request, $slug, $category='blog')
    {
        $post = $this->getPostBySlug($slug, $category);
        $this->saveRevision($post);
        $post = $this->setPost($post, $request, 'update', $category);
        flash('Post has been updated.')->success();
        \Activitylogger::log('Admin - Updated Post : '.$post->title, $post);
        $this->cache_clear();
        return $post;
        
    }

    private function getUniqueSlug($slug, $post)
    {
        $slugCount = count(Posts::whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$post->id}'")->tenant()->get());
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    private function setPost($post, $request, $type='create', $category)
    {
        $parentCategory = $this->categoryService->getCategoryBySlug($category);

        //duplicate slug
        $slug = $this->getUniqueSlug($request->slug, $post);

        $post->title = $request->title;
        $post->slug = $slug;
        $post->content = $request->content;
        $post->summary = $request->summary;
        $post->area = $request->area;
        $post->status = $request->status;
        $post->publishOnTime = ($request->publishOnTime) ? $request->publishOnTime : 0;
        $post->sticky = ($request->sticky) ? $request->sticky : 0;
        $post->featured = ($request->featured) ? $request->featured : 0;

        $now = Carbon::now();
        $post->publish_on = Carbon::createFromFormat('F jS Y H:i', $request->publishDateTime)->toDateTimeString();
       // if(($request->status == 'published') && ($request->publishOnTime != '1'))  $post->publish_on = $now;
        //if(($request->status == 'unpublished') && ($request->publishOnTime != '1'))  $post->publish_on = '';

        if($request->publishOnTime == '1')
        {
            //$post->publish_on = Carbon::createFromFormat('F jS Y H:i', $request->publishDateTime)->toDateTimeString();
            if ($post->publish_on->gte($now)) $post->status = 'unpublished';
        }


        $category = $this->categoryService->getCategoryById($request->category);
        $post->main_category_id = $parentCategory->id;
        $post->post_category_id = $category->id;
        $post->post_group = 'admin';
        if($type == 'create') $post->user_id = \Auth::user()->id;

        $post->save();
        if($type == 'create') {
            $meta = new PostMeta($request->only('featured_image', 'description', 'keywords', 'type', 'robots'));

            if($featured_image2 = $this->featureImageUpload($request))
            {
                $meta->featured_image = $featured_image2;
            }
            $post->meta()->save($meta);
        }
        if($type == 'update') {
            $post->meta()->update($request->only('featured_image', 'description', 'keywords', 'type', 'robots'));

            if($featured_image2 = $this->featureImageUpload($request))
            {
                $post->load('meta');
                $post->meta->featured_image = $featured_image2;
                $post->meta->save();
            }
        }
        $this->setTags($post, $request, $parentCategory->slug);
        $this->syncRoles($post, $request);
        $this->cache_clear();
        return $post;
    }

    private function featureImageUpload($request)
    {
        $user = \Auth::user();
        $path = config('main.public_path').'/media/photos/'.$user->username;
        File::exists($path) or File::makeDirectory($path);

        if($request->file('featured_image2'))
        {
            $featured_image = $request->file('featured_image2')->getClientOriginalName();
            $image = Image::make($request->file('featured_image2')->getRealPath());
            //Save new
            $featured_image = str_replace(' ', '_', $featured_image);
            $image->save($path.'/'.$featured_image);
            $image->resize(200, 200)->save($path.'/thumbs/'.$featured_image);
            return config('app.url').'/media/photos/'.$user->username.'/'.$featured_image;
        }
        return false;
    }

    private function setTags($post, $request, $category)
    {
        $tagIDs = [];
        $tags = explode(',', $request->tags);

        foreach ($tags as $tag)
        {

            $validator = \Validator::make(['tag' => $tag], [
                'tag' => 'required|string|between:2,50',
            ]);

            if ($validator->fails()) {
             continue;
            } else {
                $TagDetail = Tags::firstOrCreate([
                    'name' => ucfirst($tag),
                    'slug' => str_slug($tag),
                    'area' => $category,
                    'user_id' => 0,
                    'tenant' => config('app.name')
                ]);
                array_push($tagIDs, $TagDetail->id);
            }

        }

        $post->tags()->sync($tagIDs);
    }

    public function publishTimed()
    {
       $user = (\Auth::check()) ? \Auth::user() : User::where('id', 1)->first();
        $posts = Posts::where('post_group', 'admin')->where('publishOnTime', 1)->whereDate('publish_on', '<=', Carbon::now()->toDateString())->get();
        
        foreach ($posts as $post)
        {
            $post->publishOnTime = 0; 
            $post->publish_on = Carbon::now();
            $post->status = 'published';
            $post->save();
            $this->cache_clear();
            \Activitylogger::log('Cron - Timed Post Publish : '.$post->title, $post, $user);
        }
    }

    public function getPosts($area, $userRoles=[], $catId=null, $tagId=null, $category='blog')
    {
        if((!is_array($area)) || (count($area) == 0)) return false;
        $parentCategory = $this->categoryService->getCategoryBySlug($category);
        
        $page = Input::get('page', 1);
        
        $cachekey = '';
        foreach ($area as $postArea)
        {
            $cachekey .= $postArea; 
        }

        
        if(in_array('members', $area)){
            if(!\Auth::check()) abort(404);
            $userRoles = \Auth::user()->roles->pluck('name')->toArray();
            foreach ($userRoles as $role) {
                if($role != 'super_admin') $cachekey .= $role;
            }
        }
        array_push($userRoles, 'guest');
        $cachekey .= 'guest_page_'.$page.$category;


        if($catId) $cachekey .= $catId;
        if($tagId) $cachekey .= $tagId;

        
        $hasCacheTags = $this->checkForCacheTags();
        
        if($hasCacheTags)
        {
            if (Cache::tags(['posts'])->has($cachekey)) {
                $posts = Cache::tags(['posts'])->get($cachekey);
            } else {
                $posts = Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->where(function ($query) use($catId) {
                    if($catId) $query->where("post_category_id", $catId);
                })->where(function ($query) use($tagId) {
                    if($tagId) $query->whereHas("tags", function ($query) use($tagId) {
                        $query->where('id', $tagId);
                    });
                })->orderBy('sticky', 'desc')->orderBy('publish_on', 'desc')->paginate(10);
                Cache::tags(['posts'])->put($cachekey, $posts, 10);
            }
        } else {
            $posts = Cache::remember($cachekey, 10, function() use($area, $userRoles, $catId, $tagId, $parentCategory) {
                return Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->where(function ($query) use($catId) {
                    if($catId) $query->where("post_category_id", $catId);
                })->where(function ($query) use($tagId) {
                    if($tagId) $query->whereHas("tags", function ($query) use($tagId) {
                        $query->where('id', $tagId);
                    });
                })->orderBy('sticky', 'desc')->orderBy('publish_on', 'desc')->paginate(10);
            });
        }

        return $posts;
    }
    
    public function checkForCacheTags()
    {
        $cacheType = config('cache.default');
        if($cacheType == 'redis') return true;
        return false;
    }

    public function show_post($postRoute, $area=[], $category='blog')
    {
        $Post = $this->decodePostUrl($postRoute);

        $parentCategory = $this->categoryService->getCategoryBySlug($category);

        if((!is_array($area)) || (count($area) == 0)) return false;

        $userRoles = [];
        $cachekey = $postRoute;

        if(in_array('members', $area)){
            if(!\Auth::check()) abort(404);
            $userRoles = \Auth::user()->roles->pluck('name')->toArray();

            foreach ($userRoles as $role) {
                if($role != 'super_admin') $cachekey .= $role;
            }
        }
        array_push($userRoles, 'guest');
        $cachekey .= 'guest'.$category;
        
        if($Post['dateFormat'] != '')
        {
            
            $post = Cache::remember($cachekey, 60, function() use($area, $Post, $userRoles, $parentCategory) {
                return Posts::with('meta', 'user', 'roles', 'category', 'tags')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('slug', $Post['title'])->where(function ($query) use ($Post) {
                    if(isset($Post['year'])) $query->whereYear('publish_on', '=', $Post['year']);
                    if(isset($Post['month'])) $query->wheremonth('publish_on', '=', $Post['month']);
                    if(isset($Post['day'])) $query->whereDay('publish_on', '=', $Post['day']);
                    return $query;
                })->where('status', 'published')->where(function($query) use($userRoles) {

                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });

                })->firstOrFail();
            });


        } else {

            $post = Cache::remember($cachekey, 60, function() use($area, $Post, $userRoles, $parentCategory) {
                return Posts::with('meta', 'user','tags')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('slug', $Post['title'])->where('status', 'published')
                    ->where(function($query) use($userRoles) {
                        $query->whereHas('roles', function($query) use($userRoles) {
                            $query->whereIn("name", $userRoles);
                        });
                    })
                    ->firstOrFail();
            });

        }

        return $post;
    }

    private function decodePostUrl($postRoute)
    {
        //$postLinkStructure = 'Y/m/d/title';
        $postLinkStructure = $this->getPostLinkStructure();

        $Pls_parts = explode('/', $postLinkStructure);

        $pr_parts = explode('/', $postRoute);

        $Post =[];
        $Post['dateFormat'] = '';
        $Post['dateFormatDate'] = '';

        foreach($Pls_parts as $key => $part)
        {

            try {
                if (strtolower($part) == 'y') {
                    $Post['year'] = $pr_parts[$key];
                    $Post['dateFormat'] .= 'Y/';
                    $Post['dateFormatDate'] .= $pr_parts[$key].'/';
                }
                if (strtolower($part) == 'm') {
                    $Post['month'] = $pr_parts[$key];
                    $Post['dateFormat'] .= 'm/';
                    $Post['dateFormatDate'] .= $pr_parts[$key].'/';
                }
                if (strtolower($part) == 'd') {
                    $Post['day'] = $pr_parts[$key];
                    $Post['dateFormat'] .= 'd/';
                    $Post['dateFormatDate'] .= $pr_parts[$key].'/';
                }
                if (strtolower($part) == 'title') {
                    $Post['title'] = $pr_parts[$key];
                }
            } catch (\Exception $e) {
                //not expected structure
                abort(404);
            }
        }

        $validator = \Validator::make($Post, [
            'day' => 'integer|between:1,31',
            'month' => 'integer|between:1,12',
            'year' => 'integer|between:1910,2030',
            'title' => 'required|alpha_dash',
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        $Post['dateFormat'] = rtrim($Post['dateFormat'], '/');
        $Post['dateFormatDate'] = rtrim($Post['dateFormatDate'], '/');


        return $Post;
    }

    private function getPostLinkStructure()
    {
        $pl_id = \Settings::get('blog_link_structure');

        switch($pl_id)
        {
            case 1:
                return "Y/m/d/title";
                break;
            case 2:
                return "Y/m/title";
                break;
            case 3:
                return "Y/title";
                break;
            case 4:
                return "title";
                break;
            default:
                return "Y/m/d/title";
        }
    }
    
    public function deletePost($slug, $category='blog')
    {
        $parentCategory = $this->categoryService->getCategoryBySlug($category);
        $post = Posts::where('post_group', 'admin')->tenant()->where('slug', $slug)->where('main_category_id', $parentCategory->id)->firstOrFail();
        $post->meta()->delete();
        $post->delete();
        flash('Post has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Post : '.$post->title, $post);
        $this->cache_clear();
        return;
    }

    public function postAutoSave($request, $id)
    {
        $parentCategory = $this->categoryService->getCategoryBySlug('blog');
        if($request->title == '') return false;
        if($request->content == '') return false;
        $post = new PostRevisions;

        $post->posts_id = $id;
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->content = $request->content;
        $post->summary = $request->summary;
        $post->area = $request->area;
        $post->status = $request->status;
        $post->publishOnTime = ($request->publishOnTime) ? $request->publishOnTime : 0;
        $post->publish_on = Carbon::createFromFormat('F jS Y H:i', $request->publishDateTime)->toDateTimeString();
        $post->main_category_id = $parentCategory->id;
        $post->post_category_id = $request->category;
        $post->user_id = \Auth::user()->id;
        $post->rev_type = 'autoSave';

        $post->save();
        return true;
    }
    
    
    private function saveRevision($post)
    {
        $revision = new PostRevisions($post->toArray());
        $post->revisions()->save($revision);

        $count = PostRevisions::where('posts_id', $post->id)->where('rev_type', 'revision')->count();
        if($count > 5)
        {
            PostRevisions::where('posts_id', $post->id)->orderBy('id', 'asc')->where('rev_type', 'revision')->first()->delete();
        }
    }
    
    
    public function getPostRevisionList($post)
    {
        return PostRevisions::select(['id', 'created_at'])->where('posts_id', $post->id)->where('rev_type', 'revision')->orderBy('id','desc')->get();
    }

    public function getRevisionById($id, $postId)
    {
        return PostRevisions::with('roles')->where('id', $id)->where('posts_id', $postId)->firstOrFail();
    }

    public function getPostDraftList($post=null)
    {
        if($post)
        {
            return PostRevisions::select(['id', 'created_at'])->where('posts_id', $post->id)->where('rev_type', 'autoSave')->orderBy('id','desc')->get();
        }
        return PostRevisions::select(['id', 'created_at'])->where('posts_id', '0')->where('rev_type', 'autoSave')->orderBy('id','desc')->get();
    }

    public function getDraftById($id, $postId)
    {
        return PostRevisions::with('roles')->where('id', $id)->where('posts_id', $postId)->where('rev_type', 'autoSave')->firstOrFail();
    }
    
    
    public function latest_posts($area, $amount=10, $userRoles=[], $category='blog')
    {
        $parentCategory = $this->categoryService->getCategoryBySlug($category);
        
        if((!is_array($area)) || (count($area) == 0)) return false;

        $cachekey = $amount;
        foreach ($area as $postArea)
        {
            $cachekey .= $postArea;
        }


        if(in_array('members', $area)){
            if(!\Auth::check()) abort(404);
            $userRoles = \Auth::user()->roles->pluck('name')->toArray();
            foreach ($userRoles as $role) {
                if($role != 'super_admin') $cachekey .= $role;
            }
        }
        array_push($userRoles, 'guest');
        $cachekey .= 'guest'.$category;


        $hasCacheTags = $this->checkForCacheTags();

        if($hasCacheTags)
        {
            if (Cache::tags(['latest_posts'])->has($cachekey)) {
                $posts = Cache::tags(['latest_posts'])->get($cachekey);
            } else {
                $posts = Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->orderBy('publish_on', 'desc')->limit($amount)->get();
                Cache::tags(['latest_posts'])->put($cachekey, $posts, 10);
            }
        } else {
            $posts = Cache::remember('lat_post'.$cachekey, 10, function() use($area, $userRoles, $amount, $parentCategory) {
                return Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->orderBy('publish_on', 'desc')->limit($amount)->get();
            });
        }

        return $posts;
    }

    public function latest_posts_category($area, $amount=10, $category='blog', $userRoles=[])
    {
        $parentCategory = $this->categoryService->getCategoryBySlug($category);

        if((!is_array($area)) || (count($area) == 0)) return false;

        $cachekey = $amount.$category;
        foreach ($area as $postArea)
        {
            $cachekey .= $postArea;
        }


        if(in_array('members', $area)){
            if(!\Auth::check()) abort(404);
            $userRoles = \Auth::user()->roles->pluck('name')->toArray();
            foreach ($userRoles as $role) {
                if($role != 'super_admin') $cachekey .= $role;
            }
        }
        array_push($userRoles, 'guest');
        $cachekey .= 'guest';


        $hasCacheTags = $this->checkForCacheTags();

        if($hasCacheTags)
        {
            if (Cache::tags(['latest_posts'])->has($cachekey)) {
                $posts = Cache::tags(['latest_posts'])->get($cachekey);
            } else {
                $posts = Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->orderBy('publish_on', 'desc')->limit($amount)->get();
                Cache::tags(['latest_posts'])->put($cachekey, $posts, 10);
            }
        } else {
            $posts = Cache::remember('lat_post'.$cachekey, 10, function() use($area, $userRoles, $amount, $parentCategory) {
                return Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->orderBy('publish_on', 'desc')->limit($amount)->get();
            });
        }

        return $posts;
    }

    public function getTags($section, $amount = null)
    {
        if($amount) {
            $tags = Cache::remember($section.'_tags_'.$amount, 10, function() use ($section, $amount) {
                return Tags::where('area', $section)->tenant()->limit($amount)->get();
            });
        } else {
            $tags = Cache::remember($section.'_tags_', 10, function() use ($section) {
                return Tags::where('area', $section)->tenant()->get();
            });
        }
        return $tags;
    }
    
    public function getPostsInCategorySlug($area, $slug, $type)
    {
        $category = $this->categoryService->getCategoryBySlug($slug, $type);
        return $this->getPosts($area, [], $category->id);
    }

    public function getPostsInCategory($area, $category, $categoryParent='blog')
    {
        return $this->getPosts($area, [], $category->id, null, $categoryParent);
    }

    public function getTagBySlug($slug, $area=null)
    {
        if($area)
        {
            return Cache::remember('tag_'.$slug.$area, 60, function() use($slug, $area) {
                return Tags::where('slug', $slug)->tenant()->where('user_id', 0)->where('area', $area)->firstOrFail();
            });
        }
        return Cache::remember('tag_'.$slug, 60, function() use($slug) {
            return Tags::where('slug', $slug)->tenant()->where('user_id', 0)->firstOrFail();
        });
    }

    public function getPostsInTag($area, $tag, $category='blog')
    {
        return $this->getPosts($area, [], null, $tag->id, $category);
    }
    
    public function cleanTags($area='blog')
    {
        $tags = Tags::where('area', $area)->tenant()->get();
        foreach($tags as $tag)
        {
            if($tag->posts->count() < 1)
            {
                $tag->delete();
                Cache::forget('tag_'.$tag->slug);
            }
        }
        $this->cache_clear();
    }

    public function relatedPosts($area, $post)
    {
        $related = $this->getPostsInCategory($area, $post->category, 'blog');

        $related = $related->reject(function( $relPost ) use($post) {
            return $relPost->id == $post->id;
        });
        return $related;
    }


    public function searchPosts($request, $area, $userRoles=[], $catId=null, $tagId=null, $category='blog')
    {
        $search = '';
        if(isset($request->search))
        {
            $search = filter_var ( trim(urldecode($request->search)), FILTER_SANITIZE_STRING);
        }
        if($search == '') abort(404);

        if((!is_array($area)) || (count($area) == 0)) return false;
        $parentCategory = $this->categoryService->getCategoryBySlug($category);

        $page = Input::get('page', 1);

        if($page == 1)
        {

        } else {

        }

        $cachekey = md5($search);
        foreach ($area as $postArea)
        {
            $cachekey .= $postArea;
        }


        if(in_array('members', $area)){
            if(!\Auth::check()) abort(404);
            $userRoles = \Auth::user()->roles->pluck('name')->toArray();
            foreach ($userRoles as $role) {
                if($role != 'super_admin') $cachekey .= $role;
            }
        }
        array_push($userRoles, 'guest');
        $cachekey .= 'guest_page_'.$page.$category;


        if($catId) $cachekey .= $catId;
        if($tagId) $cachekey .= $tagId;


        $hasCacheTags = $this->checkForCacheTags();

        if($hasCacheTags)
        {
            if (Cache::tags(['posts_search'])->has($cachekey)) {
                $data['posts'] = Cache::tags(['posts_search'])->get($cachekey);
            } else {
                $data['posts'] = Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->where(function ($query) use($catId) {
                    if($catId) $query->where("post_category_id", $catId);
                })->where(function ($query) use($tagId) {
                    if($tagId) $query->whereHas("tags", function ($query) use($tagId) {
                        $query->where('id', $tagId);
                    });
                })->search($search, ['title' => 10, 'content' => 5])->orderBy('publish_on', 'desc')->paginate(10);
                Cache::tags(['posts_search'])->put($cachekey, $data['posts'], 10);
            }
        } else {
            $data['posts'] = Cache::remember($cachekey, 10, function() use($area, $userRoles, $catId, $tagId, $parentCategory, $search) {
                return Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->where(function ($query) use($catId) {
                    if($catId) $query->where("post_category_id", $catId);
                })->where(function ($query) use($tagId) {
                    if($tagId) $query->whereHas("tags", function ($query) use($tagId) {
                        $query->where('id', $tagId);
                    });
                })->search($search, ['title' => 10, 'content' => 5])->orderBy('publish_on', 'desc')->paginate(10);
            });
        }

        $data['search'] = urlencode($search);
        return $data;
    }

    public function searchPostsOld($request, $area, $userRoles=[], $catId=null, $tagId=null, $category='blog')
    {
        $search = '';
        if(isset($request->search))
        {
            $search = filter_var ( trim(urldecode($request->search)), FILTER_SANITIZE_STRING);
        }
        if($search == '') abort(404);

        if((!is_array($area)) || (count($area) == 0)) return false;
        $parentCategory = $this->categoryService->getCategoryBySlug($category);

        $page = Input::get('page', 1);

        if($page == 1)
        {

        } else {

        }

        $cachekey = md5($search);
        foreach ($area as $postArea)
        {
            $cachekey .= $postArea;
        }


        if(in_array('members', $area)){
            if(!\Auth::check()) abort(404);
            $userRoles = \Auth::user()->roles->pluck('name')->toArray();
            foreach ($userRoles as $role) {
                if($role != 'super_admin') $cachekey .= $role;
            }
        }
        array_push($userRoles, 'guest');
        $cachekey .= 'guest_page_'.$page.$category;


        if($catId) $cachekey .= $catId;
        if($tagId) $cachekey .= $tagId;


        $hasCacheTags = $this->checkForCacheTags();

        if($hasCacheTags)
        {
            if (Cache::tags(['posts_search'])->has($cachekey)) {
                $data['posts'] = Cache::tags(['posts_search'])->get($cachekey);
            } else {
                $data['posts'] = Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->where(function ($query) use($catId) {
                    if($catId) $query->where("post_category_id", $catId);
                })->where(function ($query) use($tagId) {
                    if($tagId) $query->whereHas("tags", function ($query) use($tagId) {
                        $query->where('id', $tagId);
                    });
                })->where('content', 'LIKE', '%'.$search.'%')->orderBy('sticky', 'desc')->orderBy('publish_on', 'desc')->paginate(10);
                Cache::tags(['posts_search'])->put($cachekey, $data['posts'], 10);
            }
        } else {
            $data['posts'] = Cache::remember($cachekey, 10, function() use($area, $userRoles, $catId, $tagId, $parentCategory, $search) {
                return Posts::with('meta', 'user')->tenant()->whereIn('area', $area)->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->where('status', 'published')->where(function($query) use($userRoles) {
                    $query->whereHas('roles', function($query) use($userRoles) {
                        $query->whereIn("name", $userRoles);
                    });
                })->where(function ($query) use($catId) {
                    if($catId) $query->where("post_category_id", $catId);
                })->where(function ($query) use($tagId) {
                    if($tagId) $query->whereHas("tags", function ($query) use($tagId) {
                        $query->where('id', $tagId);
                    });
                })->where('content', 'LIKE', '%'.$search.'%')->orderBy('sticky', 'desc')->orderBy('publish_on', 'desc')->paginate(10);
            });
        }

        $data['search'] = urlencode($search);
        return $data;
    }

    public function cleanDrafts()
    {
        PostRevisions::where('created_at', '<', Carbon::now()->subDays(2)->toDateTimeString())->delete();
    }


    

}