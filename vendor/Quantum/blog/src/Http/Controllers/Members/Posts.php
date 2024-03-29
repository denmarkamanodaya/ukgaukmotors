<?php

namespace Quantum\blog\Http\Controllers\Members;


use App\Http\Controllers\Controller;
use Quantum\blog\Http\Requests\Admin\SearchRequest;
use Quantum\blog\Services\BlogSeoService;
use Quantum\blog\Services\BlogService;
use Quantum\base\Services\CategoryService;


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

    /**
     * Posts constructor.
     * @param BlogService $blogService
     */
    public function __construct(BlogService $blogService, CategoryService $categoryService, BlogSeoService $blogSeoService)
    {
        if(\Settings::get('enable_blog') != '1') abort(404);
        $this->blogService = $blogService;
        $this->categoryService = $categoryService;
        $this->blogSeoService = $blogSeoService;
    }

    public function index()
    {
        $posts = $this->blogService->getPosts(['members','public']);
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['members','public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::members.index', compact('posts', 'latestPosts', 'categories', 'tags'));
    }
    
    public function show($postRoute)
    {
        $post = $this->blogService->show_post($postRoute,['members','public']);
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(5);
        $related = $this->blogService->relatedPosts(['members', 'public'], $post);
        $tags = $this->blogService->getTags('blog');
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['members','public']);
        $this->blogSeoService->post($post);
        return view('blog::members.showPost', compact('post', 'latestPosts', 'tags', 'categories', 'related'));
    }
    
    public function showCategory($category)
    {
        $category = $this->categoryService->getCategoryBySlug($category, 'blog');
        $posts = $this->blogService->getPostsInCategory(['members', 'public'],$category);
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['members','public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::members.index', compact('posts', 'latestPosts', 'categories', 'tags', 'category'));
    }
    
    public function showTag($tag)
    {
        $tag = $this->blogService->getTagBySlug($tag, 'blog');
        $posts = $this->blogService->getPostsInTag(['members', 'public'],$tag);
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['members','public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::members.index', compact('posts', 'latestPosts', 'categories', 'tags', 'tag'));
    }

    public function search(SearchRequest $searchRequest)
    {
        $searchData = $this->blogService->searchPosts($searchRequest, ['members','public']);
        $posts = $searchData['posts'];
        $search = $searchData['search'];
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['members','public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::members.searchResults', compact('posts', 'latestPosts', 'categories', 'tags', 'search'));
    }

    public function showTags()
    {
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['members','public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::members.tags', compact('latestPosts', 'categories', 'tags'));
    }

    public function showCategories()
    {
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['members','public']);
        $tags = $this->blogService->getTags('blog');
        return view('blog::members.categories', compact('latestPosts', 'categories', 'tags'));
    }


}
