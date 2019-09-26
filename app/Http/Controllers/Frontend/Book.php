<?php

namespace App\Http\Controllers\Frontend;

use App\Services\BookSeoService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\blog\Services\BlogSeoService;
use Quantum\blog\Services\BlogService;
use Quantum\base\Services\CategoryService;
use App\Services\BookService;
use App\Services\SeoService;

class Book extends Controller
{
    /**
     * @var BookService
     */
    private $bookService;
    /**
     * @var BlogService
     */
    private $blogService;
    /**
     * @var CategoryService
     */
    private $categoryService;
    private $bookSeoService;
    private $seoService;

    public function __construct(BookService $bookService, BlogService $blogService, CategoryService $categoryService, BookSeoService $bookSeoService, SeoService $seoService)
    {
        $this->bookService = $bookService;
        $this->blogService = $blogService;
        $this->categoryService = $categoryService;
	$this->bookSeoService = $bookSeoService;
	$this->seoService = $seoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = $this->bookService->getBooks();
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
	$tags = $this->blogService->getTags('blog');

	// Seo
        $seoData = (object) array(
                'title'         => "GAUK Motors Automotive Guides and Books",
                'description'   => "Browse our huge motoring library unique books and guides for the car enthusiast"
        );
        $this->seoService->generic($seoData);

        return view('frontend.Book.index', compact('books', 'latestPosts', 'categories', 'tags'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = $this->bookService->getBookCached($id);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $tags = $this->blogService->getTags('blog');
	$this->bookSeoService->book($book);

	// Seo
        $seoData = (object) array(
                'title'         => "GAUK Motors Automotive Guides and Books",
                'description'   => "Browse our huge motoring library unique books and guides for the car enthusiast"
        );
        $this->seoService->generic($seoData);

        return view('frontend.Book.show', compact('book', 'latestPosts', 'categories', 'tags'));

    }

    public function showChapter($bookId, $chapterId)
    {
        $book = $this->bookService->getBookCached($bookId);
        $chapter = false;

        foreach ($book->chapters as $bchapter)
        {
            if($bchapter->slug == $chapterId)
            {
                $chapter = $bchapter;
            }
        }

        if(!$chapter) abort(404);
        $links = $this->bookService->buildLinks($book, $chapterId);

        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $tags = $this->blogService->getTags('blog');
        $this->bookSeoService->book($book);
        return view('frontend.Book.showChapter', compact('book', 'chapter', 'latestPosts', 'categories', 'tags', 'links'));
    }

    public function showPage($bookId, $chapterId, $pageId)
    {
        $book = $this->bookService->getBookCached($bookId);
        $chapter = false;
        $page = false;

        foreach ($book->chapters as $bchapter)
        {
            if($bchapter->slug == $chapterId)
            {
                $chapter = $bchapter;
            }
        }

        if(!$chapter) abort(404);

        foreach ($chapter->pages as $cpage)
        {
            if($cpage->slug == $pageId)
            {
                $page = $cpage;
            }
        }
        if(!$page) abort(404);
        $links = $this->bookService->buildLinks($book, $chapterId, $pageId);
        $this->bookService->setSession($book, $page, $chapter, $links);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(3);
        $categories = $this->categoryService->getCategoriesSortPosts('blog', ['public']);
        $tags = $this->blogService->getTags('blog');
        $this->bookSeoService->book($book);
        return view('frontend.Book.showPage', compact('book', 'chapter', 'page', 'latestPosts', 'categories', 'tags', 'links'));
    }

    public function details($bookId)
    {
        $book = $this->bookService->getBookCached($bookId);
        $this->bookSeoService->book($book);
        return view('frontend.Book.details', compact('book'));
    }
}
