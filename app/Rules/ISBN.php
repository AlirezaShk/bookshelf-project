<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ISBN implements Rule
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
        $digits = str_split((string) $value, 1);
        $sum = 0;
        //Is the check digit of the validating ISBN code set correctly?
        if (count($digits) === 13) {
            //ISBN-13 check digit, check routine
            for($i = 0; $i < 12; $i++) {
                $sum += intval($digits[$i]) * [1, 3][$i % 2];
            }
            if((intval($digits[12]) + $sum) % 10 !== 0) return FALSE;
        } elseif (count($digits) === 10) {
            //ISBN-10 check digit, check routine
            $cnt = 10;
            do {
                $sum += intval(
                    $digits[10-$cnt] !== 'X' ?
                    $digits[10-$cnt] :
                    10
                ) * $cnt;
            } while(--$cnt > 0);
            if ($sum % 11 !== 0) return FALSE;
        } else {
            //Invalid ISBN length
            return FALSE;
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
        return 'The :attribute is not in a valid ISBN format.';
    }
}
