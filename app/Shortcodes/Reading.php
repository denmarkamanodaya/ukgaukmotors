<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : GaukTheme.php
 **/

namespace App\Shortcodes;

use App\Models\Book;
use Illuminate\Support\Facades\View;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Reading
{
    public static function currentBooks(ShortcodeInterface $s)
    {
        $books = session('books', []);
        $out = '';
        $counted = count($books);
        if($counted > 0)
        {
            $offset = 0;
            if($counted == 1) $offset = 4;
            if($counted == 2) $offset = 2;

            $out = '<div class="row mb-20"><div class="col-md-12 text-center"><h3 class="mb-20">Currently Reading</h3> ';
            $i=1;
            foreach ($books as $book)
            {
                if($i != 1) $offset = 0;
                if($currentBook = Book::where('slug', $book['book'])->first())
                {
                    $out .= View::make('Shortcodes.readingBook', compact('currentBook', 'book','offset'));
                }
                $i++;
            }
            $out .= '</div></div>';
        }

        return $out;
    }
}