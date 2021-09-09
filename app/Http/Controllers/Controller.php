<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function generatePreNavLinks(string $currentRoute)
    {
        $navlinks = [
            [
                'url' => '/',
                'title' => 'Home'
            ]
        ];
        switch(get_class($this)) {
            case 'App\Http\Controllers\BookController':
                if(strpos($currentRoute, 'book/') !== FALSE)
                    array_push($navlinks, [
                        'url' => '/books',
                        'title' => 'Book Archive'
                    ]);
            break;
            case 'App\Http\Controllers\AuthorController':
                if(strpos($currentRoute, 'author/') !== FALSE)
                    array_push($navlinks, [
                        'url' => '/authors',
                        'title' => 'Author List'
                    ]);
            break;
        }
        return $navlinks;
    }

    protected function generatePostNavLinks(string $currentRoute)
    {
        $navlinks = [];
        switch(get_class($this)) {
            case 'App\Http\Controllers\BookController':
                if(strpos($currentRoute, 'books') !== FALSE)
                    array_push($navlinks, [
                        'url' => '/book/new',
                        'title' => 'Add New Book'
                    ]);
            break;
            case 'App\Http\Controllers\AuthorController':
                if(strpos($currentRoute, 'authors') !== FALSE)
                    array_push($navlinks, [
                        'url' => '/author/new',
                        'title' => 'Add New Author'
                    ]);
            break;
        }
        return $navlinks;
    }
}
