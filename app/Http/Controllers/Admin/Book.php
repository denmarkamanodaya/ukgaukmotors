<?php

namespace App\Http\Controllers\Admin;

use App\Services\BookService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Book extends Controller
{

    /**
     * @var BookService
     */
    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = $this->bookService->getBooks();
        return view('admin.Book.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Book.create');
    }

    public function createChapter($id)
    {
        $book = $this->bookService->getBook($id, true);
        return view('admin.Book.Chapters.create', compact('book'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\CreateBookRequest $request)
    {
        $this->bookService->createBook($request);
        return redirect('/admin/books');
    }

    public function storeChapter(Requests\Admin\CreateChapterRequest $request, $id)
    {
        $this->bookService->createChapter($request, $id);
        return redirect('/admin/book/'.$id.'/manage');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manage($id)
    {
        $book = $this->bookService->getBook($id, true);
        return view('admin.Book.Chapters.index', compact('book'));
    }

    public function pages($id, $chapterid)
    {
        $book = $this->bookService->getBook($id, true);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterid == $bchapter->id) $chapter = $bchapter;
        }
        if(!$chapter) abort(404);
        $chapter->load('pages');
        return view('admin.Book.Pages.index', compact('book', 'chapter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = $this->bookService->getBook($id);
        return view('admin.Book.edit', compact('book'));
    }

    public function editChapter($id, $chapterid)
    {
        $book = $this->bookService->getBook($id, true);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterid == $bchapter->id) $chapter = $bchapter;
        }
        if(!$chapter) abort(404);
        return view('admin.Book.Chapters.edit', compact('book', 'chapter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\CreateBookRequest $request, $id)
    {
        $this->bookService->updateBook($request, $id);
        return redirect('/admin/book/'.$id.'/edit');
    }

    public function updateChapter(Requests\Admin\CreateChapterRequest $request, $id, $chapter)
    {
        $this->bookService->updateChapter($request, $id, $chapter);
        return redirect('/admin/book/'.$id.'/manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->bookService->bookDelete($id);
        return redirect('/admin/books');
    }

    public function destroyChapter($id, $chapter)
    {
        $this->bookService->chapterDelete($id, $chapter);
        return redirect('/admin/book/'.$id.'/manage');
    }

    public function pageCreate($id, $chapterid)
    {
        $book = $this->bookService->getBook($id, true);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterid == $bchapter->id) $chapter = $bchapter;
        }
        if(!$chapter) abort(404);
        return view('admin.Book.Pages.create', compact('book', 'chapter'));
    }

    public function pageStore(Requests\Admin\CreateBookPageRequest $request, $id, $chapterid)
    {
        $this->bookService->pageCreate($request, $id, $chapterid);
        return redirect('/admin/book/'.$id.'/chapter/'.$chapterid.'/pages');
    }

    public function pageEdit($id, $chapterid, $pageid)
    {
        $book = $this->bookService->getBook($id, true);

        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterid == $bchapter->id) $chapter = $bchapter;
        }
        if(!$chapter) abort(404);
        $chapter->load('pages');
        $page = false;
        foreach ($chapter->pages as $cpage)
        {
            if($pageid == $cpage->id) $page = $cpage;
        }
        if(!$page) abort(404);
        $revisions = $this->bookService->getPostRevisionList($page);
        return view('admin.Book.Pages.edit', compact('book', 'chapter', 'page', 'revisions'));
    }

    public function pageUpdate(Requests\Admin\CreateBookPageRequest $request, $id, $chapterid, $pageid)
    {
        $this->bookService->pageUpdate($request, $id, $chapterid, $pageid);
        return redirect('/admin/book/'.$id.'/chapter/'.$chapterid.'/pages');
    }

    public function destroyPage($id, $chapter, $page)
    {
        $this->bookService->destroyPage($id, $chapter, $page);
        return redirect('/admin/book/'.$id.'/chapter/'.$chapter.'/pages');
    }

    public function showRevision($id, $chapterid, $pageid, $revision)
    {
        $book = $this->bookService->getBook($id, true);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterid == $bchapter->id) $chapter = $bchapter;
        }
        if(!$chapter) abort(404);
        $chapter->load('pages');
        $page = false;
        foreach ($chapter->pages as $cpage)
        {
            if($pageid == $cpage->id) $page = $cpage;
        }
        if(!$page) abort(404);
        $pageRevision = $this->bookService->getRevisionById($revision, $page->id);

        $revisions = $this->bookService->getPostRevisionList($page);

        return view('admin.Book.Pages.editRevision', compact('book', 'chapter', 'page', 'revisions', 'pageRevision'));
    }

    public function pagesSavePosition(Requests\Admin\UpdateBookPagePositionRequest $request, $id, $chapterid)
    {
        $this->bookService->pagesSavePosition($request, $id, $chapterid);
        return redirect('/admin/book/'.$id.'/chapter/'.$chapterid.'/pages');
    }

    public function manageSavePosition(Requests\Admin\UpdateBookPagePositionRequest $request, $id)
    {
        $this->bookService->manageSavePosition($request, $id);
        return redirect('/admin/book/'.$id.'/manage');
    }
}
