<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use SoapBox\Formatter\Formatter;
use App\Exceptions\UndefinedBookAttrException;
use App\Http\Requests\ExportRequest;
use App\Http\Requests\CreateOrEditRequest;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $records = Book::all();
        //Keys Shown as headings of the results table
        $keys = [
            'Id',
            'Name',
            'Author',
            'Actions'
        ];
        $attrs = $records[0]->getAttributes();
        //Eligible export fields
        $export_fields = $this->getExportFields();
        //Eligible export file type
        $export_types = config('app.allowed_export_filetypes');
        [$preNavLinks, $postNavLinks, $extraNavLinks] = $this->generateNavLinks();
        $pageTitle = 'Books List';
        return view('books.list', compact('records', 'export_fields','export_types', 'preNavLinks', 'postNavLinks', 'extraNavLinks', 'keys', 'pageTitle'));
    }

    /**
     * Get the fields that match the column of the resource, when exporting data. 
     *
     * @return array
     * Each is an array with 2 keys: ['label', 'raw']
     * 'label' is for UI text
     * 'raw' are user attribute, names
     */

    public function getExportFields()
    {
        return [
            [
                'label' => 'Id',
                'raw' => 'id'
            ],
            [
                'label' => 'Title',
                'raw' => 'name'
            ],
            [
                'label' => 'Genre',
                'raw' => 'genre'
            ],
            [
                'label' => 'Author',
                'raw' => 'author'
            ],
            [
                'label' => 'ISBN',
                'raw' => 'isbn'
            ],
            [
                'label' => 'Release Date',
                'raw' => 'release_date'
            ],
            [
                'label' => 'Original Language',
                'raw' => 'olang'
            ],
            [
                'label' => 'Other Languages',
                'raw' => 'langs'
            ],
            [
                'label' => 'Description',
                'raw' => 'descrip'
            ],
            [
                'label' => 'Insertion Date',
                'raw' => 'created_at'
            ],
            [
                'label' => 'Last Update Date',
                'raw' => 'updated_at'
            ],
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $attr = Book::factory()->raw();
        $fields = [];
        foreach($attr as $k => $at) {
            //Generate an array of the properties of the input fields of the creation form
            $fields[$k] = [
                'label' => $this->formatLabelFromCols($k),
                'type' => $this->formatTypeFromCols($k),
                'other' => '',
                'change' => '',
                'required' => $this->formatRequiredFromCols($k),
            ];
            if ($k === 'langs' || $k === 'olang') {
                $fields[$k]['other'] = config('app.supporting_languages')['linked'];
            }
        }
        [$preNavLinks, $postNavLinks, $extraNavLinks] = $this->generateNavLinks();
        $pageTitle = 'Add New Book';

        return view('books.create', compact('preNavLinks', 'postNavLinks', 'extraNavLinks', 'fields', 'pageTitle'));
    }

    /**
     * Returns whether an input field will be marked as required in the 
     * create-form, based on the attribute key.
     *
     * @param string $col
     * An attribute key of a Book
     * 
     * @return bool
     * Is it a required field? (TRUE/FALSE)
     */
   
    private function formatRequiredFromCols(string $col)
    {
        switch($col) {
            case 'langs':
            case 'descrip':
                return FALSE;
            default:
                return TRUE;
        }
    }

    /**
     * Returns what the label of an input field would be in the 
     * create-form, based on the attribute key.
     *
     * @param string $col
     * An attribute key of a Book
     * 
     * @return string
     * Label of the input
     */

    private function formatLabelFromCols(string $col)
    {
        switch($col) {
            case 'name':
                $col = 'title';
                break;
            case 'descrip':
                $col = 'description';
                break;
            case 'olang':
                $col = 'original language';
                break;
            case 'langs':
                $col = 'other languages';
                break;
            case 'release_date':
                $col = 'Release Date';
                break;
        }
        return ucwords(str_replace("_", " ", $col));
    }

    /**
     * Returns what type an input field would be in the 
     * create-form, based on the attribute key.
     *
     * @param string $col
     * An attribute key of a Book
     * 
     * @return string
     * Type of the input
     */

    private function formatTypeFromCols(string $col)
    {
        switch($col) {
            case 'descrip':
                return 'textarea';
            case 'olang':
                return 'select';
            case 'langs':
                return 'multiselect';
            case 'release_date':
                return 'date';
            case 'author_id':
                return 'number';
            default:
                return  'text';
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateOrEditRequest  $request
     * 
     * @return \Illuminate\Http\Response
     * Redirect back to the details page of the newly created resource
     */

    public function store(CreateOrEditRequest $request)
    {
        //validate
        $attrs = $request->validated();
        $attrs['release_date'] = date('Y-m-d', strtotime($attrs['release_date']));
        //store
        $book = Book::create($attrs);
        //redirect
        return redirect(route('book.entry/edit-view', $book->id));
    }

    /**
     * Display a specifed resource
     *
     * @param  \App\Models\Book  $book
     * 
     * @return \Illuminate\Http\Response
     * Shows the details page of the specified resource
     */

    public function show(Book $book)
    {
        [$preNavLinks, $postNavLinks, $extraNavLinks] = $this->generateNavLinks();
        $pageTitle = 'Book: ' . $book->id;
        $author = $book->author;
        $au_attrs = $book->author->getAttributes();
        $au_attrs['name'] = $author->name;
        $author = $au_attrs;
        $book = $book->getAttributes();

        $fields = [];
        foreach($book as $k => $at) {
            if($k === 'death') {
                //Insert an Is Alive Field just before the obit (death date)
                $fields['alive'] = [
                    'label' => 'Are they currently alive?',
                    'type' => 'checkbox',
                    'other' => '[]',
                    'change' => 'checkNotDead',
                    'required' => FALSE,
                    'value' => 'yes',
                    'default' => ($at == FALSE) ? 'yes' : 'no'
                ];
            }
            //Generate an array of the properties of the input fields of the edit form
            $fields[$k] = [
                'label' => $this->formatLabelFromCols($k),
                'type' => $this->formatTypeFromCols($k),
                'other' => '[]',
                'change' => '',
                'required' => $this->formatRequiredFromCols($k),
                'default' => $book[$k]
            ];
            if(is_array($fields[$k]['default']))
                $fields[$k]['default'] = $fields[$k]['default'];
            if ($k === 'langs' || $k === 'olang') {
                $fields[$k]['other'] = config('app.supporting_languages')['linked'];
            }
        }
        $shownFirst = [
            'Title',
            'Genre',
            'Original Language',
            'Description',
        ];
        return view('books.single', compact('book', 'fields', 'shownFirst', 'author', 'pageTitle', 'preNavLinks', 'postNavLinks', 'extraNavLinks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreateOrEditRequest  $request
     * @param  \App\Models\Book  $book
     * 
     * @return \Illuminate\Http\Response | Array 
     * If the resource is being manipulated by an API, return a JSON
     * response with a success key for the response. 
     * Otherwise, redirects the request to the related details page.
     */

    public function update(CreateOrEditRequest $request, Book $book)
    {
        $attrs = $request->validated();
        $attrs['release_date'] = date('Y-m-d', strtotime($attrs['release_date']));
        $status = TRUE;
        try {
            $book->update([
                'name' => $attrs['name'],
                'genre' => $attrs['genre'],
                'author_id' => $attrs['author_id'],
                'isbn' => $attrs['isbn'],
                'release_date' => $attrs['release_date'],
                'olang' => $attrs['olang'],
                'langs' => $attrs['langs'],
                'descrip' => $attrs['descrip'],
            ]);
        } catch(\Exception $e) {
            $status = FALSE;
        }
        if($request->header('Content-Type') === 'application/json') {
            return ['success'=>$status];
        } else {
            return redirect(route('book.entry/edit-view', $book->id));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \App\Models\Book  $book
     * 
     * @return \Illuminate\Http\Response | Array 
     * If the resource is being manipulated by an API, return a JSON
     * response with a success key for the response. 
     */

    public function destroy(Request $request, Book $book)
    {
        $status = TRUE;
        try {
            $book->delete();
        } catch(\Exception $e) {
            $status = FALSE;
        }
        if($request->header('Content-Type') === 'application/json') {
            return ['success'=>$status];
        }
    }

    /**
     * Export a specified resource list.
     *
     * @param  \App\Http\Requests\ExportRequest  $request
     * 
     * @param  string  $type
     * Type of the file extension. The valid type list is in config(app.allowed_export_filetypes).
     * 
     * @return \Illuminate\Http\Response 
     * Returns a stream response which will download into an export file
     */

    public function export(ExportRequest $request, string $type)
    {
        //validate
        $request = $request->validated();
        //process
        $fields = $request['fields'];
        if(!is_array($fields))
            $fields = json_decode($fields);
        $id_array = $request['ids'];
        if(!is_array($id_array))
            $id_array = json_decode($id_array);
        $books = Book::whereIn('id', $id_array)->get();
        $data = [];
        $i = 0;
        foreach($books as $book) {
            foreach($fields as $field){
                $data[$i][$field] = $book->$field;
                if ($field === 'author') {
                    $data[$i][$field] = $data[$i][$field]->getAttributes();
                }
            }
            $i++;
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
        $fileName = 'books @ ' . date('Y-m-d H:i:s') . '.' . $type;
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

    /**
     * Return a randomly generated ISBN.
     *
     * @param  \App\Http\Requests\ExportRequest  $request
     * Must hold a `length` input to sepcify 10 or 13
     * 
     * @return JsonResponse 
     */

    public function exampleISBN(Request $request) {
        return ['isbn' => Book::ISBNGenerator($request->input('length'))];
    }
}
