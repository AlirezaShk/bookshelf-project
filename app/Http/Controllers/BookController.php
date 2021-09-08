<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use SoapBox\Formatter\Formatter;
use App\Exceptions\UndefinedBookAttrException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $records = Book::all();
        $ids = [];
        foreach($records as &$each) {
            array_push($ids, $each->id);
        }
        $keys = [
            'Name',
            // 'Release Date',
            'Author',
            'Actions'
        ];
        $export_types = config('app.allowed_export_filetypes');
        $id_list = 'id_list';
        $navlinks = $this->generateNavLinks('/books');
        return view('books.list', compact('records', 'ids', 'export_types', 'id_list', 'navlinks', 'keys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        $request->validate([
            'name' => 'required|string',
            'genre' => 'required|string',
            'author_id' => 'required|exists:App\Models\Author,id',
            'isbn' => 'required|unique:App\Models\Book',
            'release_date' => 'required|date_format:Y-m-d|before_or_equal:now',
            'olang' => 'required|string',
            'langs' => 'required|string',
            'descrip' => 'string|nullable',
        ]);
        //store
        Book::create($request->all());
        //redirect
        return redirect('/books');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('books.single', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        foreach($request->input('fields') as $k => $v) {

        //validate
            if(!isset($book->$k) AND 
                !($k == 'descrip' AND $book->$k == NULL)) 
                throw new UndefinedBookAttrException;

        //edit
            else 
                $book->$k = $v;
        }
        $book->update();
        return redirect('/book/'.$book->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book)
    {
        $book->delete();
        if($request->input('responseType') == 'json') {
            echo json_encode(["success"=>true]);
        }
    }

    public function export(Request $request, string $type)
    {
        // $conditions = array_map(function ($a) {
        //     return $a['id'];
        // }, $request->all());
        $id_array = explode(', ', $request->input('ids'));
        $books = Book::whereIn('id', $id_array)->get();
        $data = [];
        $i = 0;
        foreach($books as $book) {
            $data[$i] = $book->getAttributes();
            $data[$i]['author'] = Author::findOrFail(['id'=>$book['author_id']])->first()->getAttributes();
            unset($data[$i++]['author_id']);
        }
        $formatter = Formatter::make($data, Formatter::ARR);

        $type[0] = strtoupper($type[0]);
        
        if(method_exists($formatter, 'to'.$type)){
            $func = 'to'.$type;
            $data = $formatter->$func ();
        } else {
            throw new \InvalidArgumentException(
                'Formatter: only accepts [csv, json, xml, array] for exporting type but ' . $type . ' was provided.'
            );
        }

        $type[0] = strtolower($type[0]);
        $fileName = 'books' . '.' . $type;
        $headers = array(
            "Content-type"        => "text/".strtolower($type),
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return response()->stream(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, $data);
            fclose($file);
        }, 200, $headers);
    }
}
