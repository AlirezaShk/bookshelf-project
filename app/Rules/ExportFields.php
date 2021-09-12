<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExportFields implements Rule
{
    private $export_fields;
    private $error_type;
    /**
     * Create a new rule instance.
     *
     * @param $export_fields
     * Rule should be provided the available export_fields based on the 
     * type of the resource.
     * 
     * @return void
     */
    public function __construct($export_fields)
    {
        $this->export_fields = $export_fields;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //Arrayfy
        if(!is_array($value))
            $fields = json_decode($value);
        else
            $fields = $value;

        //Does it have any fields? No => Message No. 0 
        if (count($fields) === 0) {
            $this->error_type = 0;
            return FALSE;
        } else {
            //Are all fields eligible? No => Message No. 1 
            foreach($fields as $field) {
                if (!in_array($field, $this->export_fields)) {
                    $this->error_type = 1;
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch($this->error_type) {
            case 0:
                return "Please specify at least one column for the results.";
            case 1:
                return "Specified column does not exist.";
        }
    }
}
