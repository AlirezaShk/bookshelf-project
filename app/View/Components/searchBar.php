<?php

namespace App\View\Components;

use Illuminate\View\Component;

class searchBar extends Component
{
    public $exportTypes;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($exportTypes)
    {
        $this->exportTypes = $exportTypes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-bar', ['exportTypes' => $this->exportTypes]);
    }
}
