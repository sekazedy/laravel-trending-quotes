<?php
    namespace App\Http\Controllers;

    use App\Author;
    use App\Quote;
    use Illuminate\Http\Request;

    class QuoteController extends Controller
    {
        public function getIndex($author = null)
        {
            if (!is_null($author))
            {
                $quote_author = Author::where('name', $author)->first();
                if ($quote_author)
                    $quotes = $quote_author->quotes()->orderBy('created_at', 'desc')->paginate(6);
            }
            else
                $quotes = Quote::orderBy('created_at', 'desc')->paginate(6);

            return view('index', ['quotes' => $quotes]);
        }

        public function postQuote(Request $request)
        {
            $this->validate($request,[
                'author'    => 'required|max:60|alpha',
                'quote'     => 'required|max:500'
            ]);

            $authorText = ucfirst($request['author']);
            $quoteText = $request['quote'];

            $author = Author::where('name', $authorText)->first();

            // Create new if not exists
            if (!$author) {
                $author = new Author();
                $author->name = $authorText;
                $author->save();
            }

            $quote = new Quote();
            $quote->quote = $quoteText;

            // Save relation between author and quote
            $author->quotes()->save($quote);

            return redirect()->route('index')->with([
                'success'   => 'Quote saved!'
            ]);
        }

        public function getDeleteQuote($quote_id)
        {
            /**
             * Check if quote is the only quote for its author,
             * and if True => delete author (to not mess up future filtering and etc.)
             */
            $quote = Quote::find($quote_id);
            $author_deleted = false;

            if (count($quote->author->quotes) === 1)
            {
                $quote->author->delete();
                $author_deleted = true;
            }

            $quote->delete();

            $msg = ($author_deleted ? 'Quote and author deleted!' : 'Quote deleted!');
            return redirect()->route('index')->with(['success' => $msg]);
        }
    }
?>