<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route as RouteFacade;
use \Illuminate\Routing\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * Apply sticky search filter Request by storing in session
     * 
     * 
     * @param Request $request
     *
     * @return array
     * Returns a JSON response with a success key for the response
     */

    public function applyFilter(Request $request)
    {
        $prefix = str_replace('api/', '', $request->route()->getPrefix());
        $sParamName = $prefix.'.filter';
        if($request->session()->get($sParamName) !== NULL) {
            $a = $request->session()->get($sParamName);
        } else {
            $a = [];
        }
        array_push($a, [$request->input('filterType') => $request->input('filterData')]);
        session([$sParamName => $a]);
        return ['success'=>TRUE];
    }

    /**
     * Delete a sticky search filter Request by storing in session
     * 
     * 
     * @param Request $request
     *
     * @return array
     * Returns a JSON response with a success key for the response
     */

    public function deleteFilter(Request $request)
    {
        $prefix = str_replace('api/', '', $request->route()->getPrefix());
        $sParamName = $prefix.'.filter';
        $a = session($sParamName);
        unset($a[$request->input('key')]);
        $a = array_values($a);
        session([$sParamName => $a]);
        return ['success' => TRUE];
    }

    /**
     * Generates Navbar Links, previous to this page.
     *  
     * @param Route $currentRoute
     *
     * @return array
     */

    protected function generatePreNavLinks(Route $currentRoute)
    {
        $navlinks = [
            [
                'url' => '/',
                'title' => 'Home'
            ]
        ];
        if(!in_array($currentRoute->getActionMethod(), [
            'index'
        ])) {
            array_push($navlinks, [
                'url' => $currentRoute->getPrefix() . '/list',
                'title' => ucwords(substr($currentRoute->getPrefix(), 1)) . 's List'
            ]);
        }
        return $navlinks;
    }

    /**
     * Generates Navbar Links, after this page.
     *  
     * @param Route $currentRoute
     *
     * @return array
     */

    protected function generatePostNavLinks(Route $currentRoute)
    {
        $navlinks = [];

        if(!in_array($currentRoute->getActionMethod(), [
            'create', 'show'
        ])) {
            array_push($navlinks, [
                'url' => $currentRoute->getPrefix() . '/new',
                'title' => 'Add New ' . ucwords(substr($currentRoute->getPrefix(), 1))
            ]);
        }
        return $navlinks;
    }

    /**
     * Generates extra Navbar Links, pointing to an optional path than this page
     *  
     * @param Route $currentRoute
     *
     * @return array
     */

    protected function generateExtraNavLinks(Route $currentRoute)
    {
        $navlinks = [];
        switch($currentRoute->getName()) {
            case 'book.entry/edit-view':
                $navlinks[2] = [];
                $navlinks[2][] = [
                    'url' => route('author.entry/edit-view', $currentRoute->parameter('book')->author->id),
                    'title' => 'More From The Author'
                ];
                $navlinks[2][] = [
                    'url' => route('book.create-view'),
                    'title' => 'Add New Book'
                ];
            case 'book.create-view':
            case 'book.list':
                $navlinks[1] = [];
                $navlinks[1][] = [
                    'url' => route('author.list'),
                    'title' => 'Authors List'
                ];
                break;
            case 'author.entry/edit-view':
                $navlinks[2][] = [
                    'url' => route('author.create-view'),
                    'title' => 'Add New author'
                ];
            case 'author.create-view':
            case 'author.list':
                $navlinks[1] = [];
                $navlinks[1][] = [
                    'url' => route('book.list'),
                    'title' => 'Books List'
                ];
                break;

        }
        return $navlinks;
    }

    /**
     * 
     * This function generates these links, in relation to the current route.
     * It can be overriden via $routeName.
     * .
     * @param string $routeName | NULL
     * a string can be passed to generate
     * these navigation links for a different route, specified by its name
     * 
     * @return array
     * Generates an array, consisting of 3 arrays of navigation bar links:
     *      1- Previous Links
     *      2- Next Links
     *      3- Extra Links
     */

    protected function generateNavLinks(string $routeName = NULL)
    {
        if($routeName === NULL)
            $route = RouteFacade::getCurrentRoute();
        else
            $route = RouteFacade::getRoutes()->getByName($routeName);
        return [
            $this->generatePreNavLinks($route), 
            $this->generatePostNavLinks($route), 
            $this->generateExtraNavLinks($route)
        ];
    }
}
