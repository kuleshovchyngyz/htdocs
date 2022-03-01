<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueQueryPerProject implements Rule
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
        print_r($attribute);
        print_r($value);
        
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('This query already exists in this project within this region');
    }
}
