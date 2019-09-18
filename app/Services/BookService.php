<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : BookService.php
 **/

namespace App\Services;


use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookChapterPage;
use App\Models\BookChapterPageRevision;
use Illuminate\Support\Facades\Cache;

class BookService
{
    private function cacheClear()
    {
        Cache::tags('books')->flush();
    }

    public function getBooks()
    {
        $page = \Request::get('page', 1);

        if (Cache::tags(['books'])->has('books_page_'.$page)) {
            return Cache::tags(['books'])->get('books_page_'.$page);
        }

        $books = Book::with('chapters')->orderBy('title', 'ASC')->paginate(25);
        Cache::tags(['books'])->forever('books_page_'.$page, $books);
        return $books;
    }

    public function createBook($request)
    {
        $book = Book::create($request->all());
        $book->meta()->create($request->all());
        $this->cacheClear();
        flash('Book has been created.')->success();
        \Activitylogger::log('Admin - Created Book : '.$book->title, $book);
        return $book;
    }

    public function getBook($id, $with=false)
    {
        if($with)
        {
            $book = Book::with('chapters')->where('id', $id)->firstOrFail();
        } else {
            $book = Book::where('id', $id)->firstOrFail();
        }
        return $book;
    }

    public function getBookCached($id)
    {
        if (Cache::tags(['books'])->has('book_'.$id)) {
            return Cache::tags(['books'])->get('book_'.$id);
        }

        $book = Book::with(['chapters' => function ($query) {
            $query->with('pages');
        }])->where('slug', $id)->firstOrFail();
        Cache::tags(['books'])->forever('book_'.$id, $book);
        return $book;
    }

    public function updateBook($request, $id)
    {
        $book = $this->getBook($id);
        $book->update($request->all());
        $book->meta()->update($request->only('featured_image', 'description', 'keywords', 'type', 'robots'));
        $this->cacheClear();
        flash('Book has been updated.')->success();
        \Activitylogger::log('Admin - Updated Book : '.$book->title, $book);
        return $book;
    }

    public function bookDelete($id)
    {
        $book = $this->getBook($id);
        $book->delete();
        $this->cacheClear();
        flash('Book has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Book : '.$book->title, $book);
        return;
    }

    public function createChapter($request, $id)
    {
        $position = 0;
        $book = $this->getBook($id);
        $last = BookChapter::where('book_id', $book->id)->orderBy('position', 'DESC')->first();
        if($last) $position = $last->position;
        $request['position'] = $position +1;
        $book->chapters()->create($request->all());
        $this->cacheClear();
        flash('Chapter has been created.')->success();
        \Activitylogger::log('Admin - Created Chapter : '.$book->title.' : '.$request->title, $book);
    }

    public function updateChapter($request, $id, $chapterId)
    {
        $book = $this->getBook($id, true);
        $changed = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterId == $bchapter->id) {
                $bchapter->update($request->all());
                $changed = true;
            }
        }

        if(!$changed) abort(404);
        $this->cacheClear();
        flash('Chapter has been updated.')->success();
        \Activitylogger::log('Admin - Updated Chapter : '.$book->title.' : '.$request->title, $book);
    }

    public function chapterDelete($id, $chapterId)
    {
        $book = $this->getBook($id);
        $changed = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterId == $bchapter->id) {
                $bchapter->delete();
                $changed = true;
            }
        }
        $this->cacheClear();
        flash('Chapter has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted chapter : '.$book->title, $book);
        return;
    }

    public function pageCreate($request, $id, $chapterId)
    {
        $position = 0;
        $book = $this->getBook($id);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterId == $bchapter->id) {
                $chapter = $bchapter;
            }
        }

        if(!$chapter) abort(404);

        $last = BookChapterPage::where('book_chapters_id', $chapter->id)->orderBy('position', 'DESC')->first();
        if($last) $position = $last->position;

        $request['book_id'] = $book->id;
        $chapter->pages()->create([
            'book_id' => $book->id,
            'title' => $request->title,
            'content' => $request->content,
            'featured_image' => $request->featured_image,
            'public_view' => $request->public_view,
            'position' => $position + 1
        ]);
        $this->cacheClear();
        flash('Page has been created.')->success();
        \Activitylogger::log('Admin - Created page : '.$book->title. ' : '.$request->title, $book);
        return;
    }

    public function pageUpdate($request, $id, $chapterId, $pageid)
    {
        $book = $this->getBook($id);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterId == $bchapter->id) {
                $chapter = $bchapter;
            }
        }
        if(!$chapter) abort(404);
        $page = BookChapterPage::where('book_id', $book->id)->where('book_chapters_id', $chapter->id)->where('id', $pageid)->firstOrFail();

        $this->saveRevision($page);
        $page->update($request->all());
        $this->cacheClear();
        flash('Page has been updated.')->success();
        \Activitylogger::log('Admin - Updated page : '.$book->title. ' : '.$request->title, $book);
        return;
    }

    private function saveRevision($page)
    {
        $revision = new BookChapterPageRevision($page->toArray());
        $page->revisions()->save($revision);

        $count = BookChapterPageRevision::where('book_page_id', $page->id)->count();
        if($count > 5)
        {
            BookChapterPageRevision::where('book_page_id', $page->id)->orderBy('id', 'asc')->first()->delete();
        }
    }

    public function getPostRevisionList($page)
    {
        return BookChapterPageRevision::select(['id', 'created_at'])->where('book_page_id', $page->id)->orderBy('id','desc')->get();
    }

    public function getRevisionById($id, $pageId)
    {
        return BookChapterPageRevision::where('id', $id)->where('book_page_id', $pageId)->firstOrFail();
    }

    public function destroyPage($id, $chapterId, $pageid)
    {
        $book = $this->getBook($id);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterId == $bchapter->id) {
                $chapter = $bchapter;
            }
        }
        if(!$chapter) abort(404);
        $page = BookChapterPage::where('book_id', $book->id)->where('book_chapters_id', $chapter->id)->where('id', $pageid)->firstOrFail();

        $page->delete();
        $this->cacheClear();
        flash('Page has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted page : '.$book->title. ' : '.$page->title, $book);
        return;
    }

    public function pagesSavePosition($request, $id, $chapterid)
    {
        $book = $this->getBook($id, true);
        $chapter = false;
        foreach ($book->chapters as $bchapter)
        {
            if($chapterid == $bchapter->id) $chapter = $bchapter;
        }
        if(!$chapter) abort(404);

        $i=1;
        foreach ($request->position as $position)
        {
            BookChapterPage::where('id', $position)->where('book_chapters_id', $chapter->id)->where('book_id', $book->id)->update([
                'position' => $i
            ]);
            $i++;
        }
        flash('positions updated.')->success();
    }

    public function manageSavePosition($request, $id)
    {
        $book = $this->getBook($id, true);
        $i=1;
        foreach ($request->position as $position)
        {
            BookChapter::where('id', $position)->where('book_id', $book->id)->update([
                'position' => $i
            ]);
            $i++;
        }
        flash('positions updated.')->success();
    }

    public function buildLinks($book, $chapterId, $pageId=null)
    {
        $links['previous'] = false;
        $links['next'] = false;

        $prevChapter = '';
        $currentChapter = '';
        $nextChapter = '';
        $chapfound = false;

        $prevPage = '';
        $currentPage = '';
        $nextPage = '';
        $pagefound = false;

        if($book->chapters)
        {
            foreach ($book->chapters as $chapter)
            {
                if($chapter->pages)
                {
                    foreach ($chapter->pages as $page)
                    {
                        if($pagefound == true)
                        {
                            $nextPage = '/book/'.$book->slug.'/'.$chapter->slug.'/'.$page->slug;
                            $pagefound = false;
                            break;
                        }

                        if($pageId)
                        {
                            if($chapter->slug == $chapterId && $page->slug == $pageId)
                            {
                                $prevPage = $currentPage;
                                $pagefound = true;
                            }
                        } else {
                            if($chapter->slug == $chapterId)
                            {
                                $prevPage = $currentPage;
                                $nextPage = '/book/'.$book->slug.'/'.$chapter->slug.'/'.$page->slug;
                                break;
                            }
                        }

                        $currentPage = '/book/'.$book->slug.'/'.$chapter->slug.'/'.$page->slug;
                    }
                }
            }
        }

        $links['previous'] = $prevPage;
        $links['next'] = $nextPage;
        return $links;

    }

    public function setSession($book, $page, $chapter, $links)
    {
        $books = session('books', []);

        if($links['next'] == '')
        {
            //last page
            foreach ($books as $key => $viewedBook)
            {
                if($viewedBook['book'] == $book->slug) unset($books[$key]);
            }

        } else {

            foreach ($books as $key => $viewedBook)
            {
                if($viewedBook['book'] == $book->slug) unset($books[$key]);
            }

            $currentBook['book'] = $book->slug;
            $currentBook['page'] = $page->slug;
            $currentBook['chapter'] = $chapter->slug;
            array_push($books, $currentBook);
           // \Session::put('books', $books);
        }

        if(count($books) > 0)
        {
            session(['books' => $books]);
        } else {
            \Session::forget('books');
        }
    }

    public function markAsRead($book)
    {
        $books = session('books', []);
        foreach ($books as $key => $viewedBook)
        {
            if($viewedBook['book'] == $book) unset($books[$key]);
        }
        if(count($books) > 0)
        {
            session(['books' => $books]);
        } else {
            \Session::forget('books');
        }

        flash('Book marked as read')->success();
    }

}