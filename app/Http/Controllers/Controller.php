<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function generateNavLinks(string $currentRoute)
    {
        $navlinks = [
            [
                'url' => '/',
                'title' => 'Home'
            ]
        ];
        switch(get_class($this)) {
            case 'BookController':
                if(strpos($currentRoute, 'book/') !== FALSE)
                    array_push($navlinks, [
                        'url' => '/books',
                        'title' => 'Home'
                    ]);
            break;
            case 'AuthorController':
                if(strpos($currentRoute, 'author/') !== FALSE)
                    array_push($navlinks, [
                        'url' => '/authors',
                        'title' => 'Home'
                    ]);
            break;
        }
        return $navlinks;
    }
}
