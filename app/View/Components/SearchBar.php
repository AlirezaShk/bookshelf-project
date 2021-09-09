<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchBar extends Component
{
    public $exportTypes;
    public $fields;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($exportTypes, $fields)
    {
        $this->exportTypes = json_decode(htmlspecialchars_decode($exportTypes));
        $this->fields = json_decode(htmlspecialchars_decode($fields));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-bar');
    }
}
