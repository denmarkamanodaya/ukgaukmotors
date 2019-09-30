<?php

namespace Quantum\blog\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Quantum\blog\Services\BlogSeoService;
use Quantum\blog\Services\BlogService;
use Quantum\base\Services\CategoryService;

use App\Services\SeoService;

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
     * @var BlogSeoService
     */
    private $blogSeoService;

    private $seoService;
	
    /**
     * Posts constructor.
     * @param BlogService $blogService
     */
    public function __construct(BlogService $blogService, CategoryService $categoryService, BlogSeoService $blogSeoService, SeoService $seoService)
    {
        $this->blogService = $blogService;
        if(\Settings::get('enable_blog') != '1') abort(404);
        $this->categoryService = $categoryService;
	$this->blogSeoService = $blogSeoService;
	$this->seoService = $seoService;
    }

    public function index()
    {
        $posts = $this->blogService->getPosts(['public']);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
	$tags = $this->blogService->getTags('blog');

	// Seo
        $seoData = (object) array(
                'title'         => "UK Car Auction Blog, News, Fun and Great Stuff",
                'description'   => "Car auction and classifieds news, car auction alerts, quizzes, grab a cup of coffee and enjoy the motoring blog"
        );
        $this->seoService->generic($seoData);

        return view('blog::frontend.index', compact('posts', 'latestPosts', 'categories', 'tags'));
    }

    public function show($postRoute)
    {
        $post = $this->blogService->show_post($postRoute,['public']);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(5);
        $tags = $this->blogService->getTags('blog');
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $this->blogSeoService->post($post);
        return view('blog::frontend.showPost', compact('post', 'latestPosts', 'tags', 'categories'));
    }

    public function showCategory($category)
    {
        $category = $this->categoryService->getCategoryBySlug($category, 'blog');
        $posts = $this->blogService->getPostsInCategory(['public'],$category);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::frontend.index', compact('posts', 'latestPosts', 'categories', 'tags', 'category'));
    }

    public function showTag($tag)
    {
        $tag = $this->blogService->getTagBySlug($tag, 'blog');
        $posts = $this->blogService->getPostsInTag(['public'],$tag);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::frontend.index', compact('posts', 'latestPosts', 'categories', 'tags', 'tag'));
    }

    public function showTags()
    {
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::frontend.tags', compact('latestPosts', 'categories', 'tags'));
    }

    public function showCategories()
    {
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::frontend.categories', compact('latestPosts', 'categories', 'tags'));
    }


}
