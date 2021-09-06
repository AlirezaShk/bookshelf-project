<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Exceptions\UndefinedAuthorAttrException;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Author::cursorPaginate(15);
        return view('authors.list', compact('records'));
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
            'fname' => 'required|string',
            'lname' => 'required|string',
            'origin' => 'required|string',
            'langs' => 'required|string',
            'birth' => 'required|date_format:Y-m-d|before_or_equal:now',
            'death' => 'nullable|date_format:Y-m-d|before_or_equal:now|after:birth',
        ]);
        //store
        Author::create($request->all());
        //redirect
        return redirect('/authors');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return view('authors.single', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        foreach($request->input('fields') as $k => $v) {

        //validate
            if(!isset($author->$k) AND 
                !($k == 'death' AND $author->$k == NULL)) 
                throw new UndefinedAuthorAttrException;

        //edit
            else 
                $author->$k = $v;
        }
        $author->update();
        return redirect('/author/'.$author->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        $author->delete();
    }
}
