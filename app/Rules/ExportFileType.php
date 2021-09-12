<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExportFileType implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        //Is the export file type eligible according to config? 
        return in_array(strtoupper($value), config('app.allowed_export_filetypes'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Specify a valid export file format.';
    }
}
