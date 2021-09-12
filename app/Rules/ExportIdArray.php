<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExportIdArray implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            $value = json_decode($value);
        //Was any Id provided?
        return count($value) !== 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No result was selected to export.';
    }
}
