<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use SoapBox\Formatter\Formatter;
use App\Http\Requests\ExportRequest;
use App\Exceptions\UndefinedAuthorAttrException;
use App\Http\Requests\CreateOrEditRequest;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $authors = Author::all();
        $keys = [
            'Id',
            'Name',
            'Books',
            'Actions'
        ];
        $export_fields = $this->getExportFields();
        $export_types = config('app.allowed_export_filetypes');
        [$preNavLinks, $postNavLinks, $extraNavLinks] = $this->generateNavLinks();
        $pageTitle = 'Authors List';
        return view('authors.list', compact('authors', 'export_fields', 'export_types', 'preNavLinks', 'postNavLinks', 'extraNavLinks', 'keys', 'pageTitle'));
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
                'label' => 'Name',
                'raw' => 'name'
            ],
            [
                'label' => 'Books',
                'raw' => 'books'
            ],
            [
                'label' => 'Origin',
                'raw' => 'origin'
            ],
            [
                'label' => 'Languages',
                'raw' => 'langs'
            ],
            [
                'label' => 'Birth Date',
                'raw' => 'birth'
            ],
            [
                'label' => 'Obit',
                'raw' => 'death'
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
        $attr = Author::factory()->raw();
        $fields = [];
        foreach($attr as $k => $at) {
            if($k === 'death') {
                $fields['alive'] = [
                    'label' => 'Are they currently alive?',
                    'type' => 'checkbox',
                    'other' => '',
                    'change' => 'checkNotDead',
                    'required' => FALSE,
                    'value' => 'yes'
                ];
            }
            $fields[$k] = [
                'label' => $this->formatLabelFromCols($k),
                'type' => $this->formatTypeFromCols($k),
                'other' => '',
                'change' => '',
                'required' => $this->formatRequiredFromCols($k)
            ];
            if ($k === 'langs' || $k === 'olang') {
                $fields[$k]['other'] = config('app.supporting_languages')['linked'];
            }
        }
        [$preNavLinks, $postNavLinks, $extraNavLinks] = $this->generateNavLinks();
        $pageTitle = 'Add New Author';
        return view('authors.create', compact('preNavLinks', 'postNavLinks', 'extraNavLinks', 'fields', 'pageTitle'));
    }

    /**
     * Returns whether an input field will be marked as required in the 
     * create-form, based on the attribute key.
     *
     * @param string $col
     * An attribute key of an Author
     * 
     * @return bool
     * Is it a required field? (TRUE/FALSE)
     */
   
    private function formatRequiredFromCols($col)
    {
        switch($col) {
            case 'death':
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
     * An attribute key of an Author
     * 
     * @return string
     * Label of the input
     */

    private function formatLabelFromCols($col)
    {
        switch($col) {
            case 'fname':
                $col = 'First Name';
                break;
            case 'lname':
                $col = 'Last Name';
                break;
            case 'death':
                $col = 'Obit';
                break;
            case 'birth':
                $col = 'Birth Date';
                break;
            case 'langs':
                $col = 'Languages';
                break;
        }
        return ucwords(str_replace("_", " ", $col));
    }

    /**
     * Returns what type an input field would be in the 
     * create-form, based on the attribute key.
     *
     * @param string $col
     * An attribute key of an Author
     * 
     * @return string
     * Type of the input
     */

    private function formatTypeFromCols($col)
    {
        switch($col) {
            case 'id':
                return 'number';
            case 'langs':
                return 'multiselect';
            case 'birth':
            case 'death':
                return 'date';
            default:
                return 'text';
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
        //store
        $attrs = $request->validated();
        $attrs['birth'] = date('Y-m-d', strtotime($attrs['birth']));
        if (array_key_exists('alive', $attrs)) {
            $attrs['death'] = NULL;
            unset($attrs['alive']);
        } else {
            $attrs['death'] = date('Y-m-d', strtotime($attrs['death']));
        }
        $author = Author::create($attrs);
        //redirect
        return redirect(route('author.entry/edit-view', $author->id));
    }

    /**
     * Display a specifed resource
     *
     * @param  \App\Models\Author  $author
     * 
     * @return \Illuminate\Http\Response
     * Shows the details page of the specified resource
     */

    public function show(Author $author)
    {
        [$preNavLinks, $postNavLinks, $extraNavLinks] = $this->generateNavLinks();
        $pageTitle = 'Author: ' . $author->id;
        $books = $author->books;
        $attrs = $author->getAttributes();
        $author = ['Name'=>$author->name] + $attrs;
        $fields = [];
        foreach($author as $k => $at) {
            if($k === 'death') {
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
            $fields[$k] = [
                'label' => $this->formatLabelFromCols($k),
                'type' => $this->formatTypeFromCols($k),
                'other' => '[]',
                'change' => '',
                'required' => $this->formatRequiredFromCols($k),
                'default' => $author[$k]
            ];
            if(is_array($fields[$k]['default']))
                $fields[$k]['default'] = $fields[$k]['default'];
            if ($k === 'langs') {
                $fields[$k]['other'] = config('app.supporting_languages')['linked'];
            }
        }
        $shownFirst = [
            'Name',
            'Origin',
            'Languages',
        ];
        return view('authors.single', compact('author', 'fields', 'shownFirst', 'books', 'pageTitle', 'preNavLinks', 'postNavLinks', 'extraNavLinks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreateOrEditRequest  $request
     * @param  \App\Models\Author  $author
     * 
     * @return \Illuminate\Http\Response | Array 
     * If the resource is being manipulated by an API, return a JSON
     * response with a success key for the response. 
     * Otherwise, redirects the request to the related details page.
     */

    public function update(CreateOrEditRequest $request, Author $author)
    {
        $attrs = $request->validated();
        $attrs['birth'] = date('Y-m-d', strtotime($attrs['birth']));
        if (array_key_exists('alive', $attrs)) {
            $attrs['death'] = NULL;
            unset($attrs['alive']);
        } else {
            $attrs['death'] = date('Y-m-d', strtotime($attrs['death']));
        }

        $status = TRUE;
        try {
            $author->update([
                'fname' => $attrs['fname'],
                'lname' => $attrs['lname'],
                'origin' => $attrs['origin'],
                'langs' => $attrs['langs'],
                'birth' => $attrs['birth'],
                'death' => $attrs['death'],
            ]);
        } catch(\Exception $e) {
            $status = FALSE;
        }
        if($request->header('Content-Type') === 'application/json') {
            return ['success'=>$status];
        } else {
            return redirect('/author/a:'.$author->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \App\Models\Author  $author
     * 
     * @return \Illuminate\Http\Response | Array 
     * If the resource is being manipulated by an API, return a JSON
     * response with a success key for the response. 
     */

    public function destroy(Request $request, Author $author)
    {
        $status = TRUE;
        try {
            $author->delete();
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
        $authors = Author::whereIn('id', $id_array)->get();
        $data = [];
        $i = 0;
        foreach($authors as $author) {
            foreach($fields as $field){
                if ($field === 'name') {
                    $data[$i]['fname'] = $author->fname;
                    $data[$i]['lname'] = $author->lname;
                } else {
                    $data[$i][$field] = $author->$field;
                }
                if ($field === 'books' && count($data[$i][$field]) > 0) {
                    $books = [];
                    foreach($data[$i][$field] as $book)
                        $books[] = $book->getAttributes();
                    $data[$i][$field] = $books;
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
        $fileName = 'authors @ ' . date('Y-m-d H:i:s') . '.' . $type;
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
     * Return a randomly selected Author->id.
     *
     * @return JsonResponse 
     */

    public function exampleAuthorId()
    {
        return ['aid' => Author::inRandomOrder()->first()->id];
    }
}
