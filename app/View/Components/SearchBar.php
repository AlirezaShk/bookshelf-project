<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchBar extends Component
{
    public $exportTypes;
    public $fields;
    public $type;
    public $sessionFilters;
    public $allSearchFilters;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($exportTypes, $fields, $type)
    {
        $this->exportTypes = json_decode(htmlspecialchars_decode($exportTypes));
        $this->fields = json_decode(htmlspecialchars_decode($fields));
        $this->type = $type;
        //Extract Label, Short Code (Attribute Key) and Value of the 
        //applied filters that are stored in session by api.book.filter API.
        $className = "\App\Models\\" . ucfirst($type);
        [$filter_labels, $filter_codes] = [
            array_map(function($a) {
                    return $a['label'];
                }, $className::SEARCH_FILTERS
            ), 
            array_map(function($a) {
                    return $a['code'];
                }, $className::SEARCH_FILTERS
            ), 
        ];
        if(session($type.".filter") !== NULL) {
            $processedFilters = [];
            foreach(session($type.".filter") as $code_value_pair) {
                foreach($code_value_pair as $code => $value) {
                    $index = array_search($code, $filter_codes);
                    $processedFilters[] = [
                        'label' => $filter_labels[$index],
                        'code' => $filter_codes[$index],
                        'value' => $value,
                    ];
                }
            }
            $this->sessionFilters = $processedFilters;
        } else {
            $this->sessionFilters = [];
        }
        //Extract a list of Label and Short Code (Attribute Key) and of
        //the applied filters that are stored in session by 
        //api.book.filter API.
        $this->allSearchFilters = array_map(function($a) {
                return ['value' => $a['code'], 'label' => $a['label']];
            }, $className::SEARCH_FILTERS
        );
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
