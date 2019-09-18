<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Categories.php
 **/

namespace Quantum\blog\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\Role;
use Quantum\blog\Http\Requests\Admin\CreateAutoPostRequest;
use Quantum\blog\Http\Requests\Admin\UpdatePostRequest;
use Quantum\blog\Services\BlogService;
use Quantum\base\Services\CategoryService;
use Quantum\blog\Http\Requests\Admin\CreatePostRequest;
use Yajra\DataTables\Facades\DataTables;


class Posts extends Controller
{

    /**
     * @var BlogService
     */
    private $blogService;
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Posts constructor.
     * @param BlogService $blogService
     */
    public function __construct(BlogService $blogService, CategoryService $categoryService)
    {
        $this->blogService = $blogService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return view('blog::admin.Posts.index');
    }

    public function data()
    {
        $posts = \Cache::rememberForever('post_list', function() {
            $parentCategory = $this->categoryService->getCategoryBySlug('blog');
            return \Quantum\blog\Models\Posts::select(['id','title', 'slug', 'area', 'sticky', 'featured', 'status', 'publishOnTime', 'publish_on', 'created_at', 'updated_at'])->tenant()->where('post_group', 'admin')->where('main_category_id', $parentCategory->id)->orderBy('publish_on', 'DESC')->get();
        });

        return Datatables::collection($posts)
            ->editColumn('publishOnTime', function($post) {
                return ($post->publishOnTime == '1') ? '<i class="far fa-clock posts_timed_icon"></i>' : '';
            })
            ->editColumn('sticky', function($post) {
                return ($post->sticky == '1') ? '<i class="far fa-check posts_timed_icon"></i>' : '';
            })
            ->editColumn('featured', function($post) {
                return ($post->featured == '1') ? '<i class="far fa-check posts_timed_icon"></i>' : '';
            })
            ->editColumn('publish_on', function ($post) {
                $pubdate = $post->publish_on->format('F jS Y H:i');
                return ($post->status == 'unpublished' && $post->publishOnTime == 0) ? '' : $pubdate;
            })
            ->editColumn('updated_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
           // ->editColumn('status', '{!! ucfirst($status) !!}')
            ->editColumn('status', function ($post) {
               return '<span class="postList_'.$post->status.'">'.ucfirst($post->status).'</span>';
           })
           // ->editColumn('area', '{!! ucfirst($area) !!}')
            ->editColumn('area', function ($post) {
                return ucfirst($post->area);
            })
            ->addColumn('action', function ($post) {
                return '<a href="'.url('admin/post/'.$post->slug).'" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-list-alt"></i></b> Details</a>';
            })
            ->rawColumns(['status', 'action', 'publishOnTime', 'sticky', 'featured'])
            ->make(true);
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        $categories = $this->categoryService->getChildCategoriesList('blog');
        $drafts = $this->blogService->getPostDraftList();
        $defaultCategory = 0;
        foreach ($categories as $key => $category)
        {
            if($category == 'Uncategorised') $defaultCategory = $key;
        }
        return view('blog::admin.Posts.create', compact('roles', 'categories', 'defaultCategory', 'drafts'));
    }

    public function createFromDraft($revision)
    {
        $postRevision = $this->blogService->getRevisionById($revision, '0');
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        $categories = $this->categoryService->getChildCategoriesList('blog');
        $drafts = $this->blogService->getPostDraftList();
        $defaultCategory = 0;
        foreach ($categories as $key => $category)
        {
            if($category == 'Uncategorised') $defaultCategory = $key;
        }
        return view('blog::admin.Posts.createFromDraft', compact('roles', 'categories', 'defaultCategory', 'drafts', 'postRevision'));
    }
    
    public function store(CreatePostRequest $createPostRequest)
    {
        $this->blogService->createPost($createPostRequest);
        return redirect('/admin/posts');
    }

    public function autoSave(CreateAutoPostRequest $createPostRequest, $id=0)
    {
        if($id != '0')
        {
            $post = $this->blogService->getPostBySlug($id);
            $id = $post->id;
        }
        $saved = $this->blogService->postAutoSave($createPostRequest, $id);
        if($saved) {
            $out = 'saved';
        } else {
            $out = 'notsaved';
        }
        return $out;
    }
    
    public function edit($id)
    {
        $post = $this->blogService->getPostBySlug($id);
        $revisions = $this->blogService->getPostRevisionList($post);
        $drafts = $this->blogService->getPostDraftList($post);
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        $categories = $this->categoryService->getChildCategoriesList('blog');
        return view('blog::admin.Posts.edit', compact('roles', 'categories', 'post', 'revisions', 'drafts'));
    }

    public function update(UpdatePostRequest $updatePostRequest, $id)
    {
        $post = $this->blogService->updatePost($updatePostRequest, $id);
        return redirect('/admin/post/'.$post->slug);
    }

    public function destroy($id)
    {
        $this->blogService->deletePost($id);
        return redirect('/admin/posts');
    }
    
    public function showRevision($id, $revision)
    {
        $post = $this->blogService->getPostBySlug($id);
        $postRevision = $this->blogService->getRevisionById($revision, $post->id);
        $revisions = $this->blogService->getPostRevisionList($post);
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        $categories = $this->categoryService->getChildCategoriesList('blog');
        return view('blog::admin.Posts.editRevision', compact('roles', 'categories', 'post', 'revisions', 'postRevision'));
    }

 


}