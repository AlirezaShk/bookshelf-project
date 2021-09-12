<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormField extends Component
{
    public $prefix;
    public $type;
    public $name;
    public $label;
    public $other;
    public $changeFunc;
    public $required;
    public $value;
    public $default;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($prefix, $type, $name, $label, $other, $changeFunc, $required, $value, $default = NULL)
    {
        $this->prefix = $prefix;
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->changeFunc = $changeFunc;
        $this->required = $required;
        $this->value = $value;
        if(is_array(json_decode(htmlspecialchars_decode($default))))
            $this->default = json_decode(htmlspecialchars_decode($default));
        else
            $this->default = $default;
        $this->other = json_decode(htmlspecialchars_decode($other));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-field');
    }
}
