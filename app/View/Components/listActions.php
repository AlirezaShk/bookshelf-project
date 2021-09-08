<?php

namespace App\View\Components;

use Illuminate\View\Component;

class listActions extends Component
{
    public $type;
    public $record;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $record)
    {
        $this->type = $type;
        $this->record = $record;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.list-actions', ['type' => $this->type, 'record' => $this->record]);
    }
}
