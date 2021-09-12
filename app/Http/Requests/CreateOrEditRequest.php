<?php

namespace App\Http\Requests;

use App\Rules\ISBN;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrEditRequest extends FormRequest
{
    public $bookRef;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->type == 'book'){
            return [
                'name' => 'required|string',
                'genre' =>'required|string',
                'author_id' => 'required|exists:App\Models\Author,id',
                'isbn' => ['required',Rule::unique('books')->ignore($this->bookRef), new ISBN],
                'release_date' => 'required|before_or_equal:now',
                'olang' => 'required|string',
                'langs' => 'array',
                'descrip' => 'string|nullable',
            ];
        } elseif($this->type == 'author') {
            return [
                'fname' => 'required|string',
                'lname' => 'required|string',
                'origin' => 'required|string',
                'langs' => 'required|array',
                'birth' => 'required|before_or_equal:now',
                'alive' => 'sometimes|string',
                'death' => 'exclude_if:alive,"yes"|nullable|before_or_equal:now|after:birth',
            ];
        }

    }

    public function prepareForValidation()
    {
        $this->type = substr(str_replace('api', '', $this->route()->getPrefix()), 1);
        if ($this->type === 'book') {
            $this->bookRef = $this->route()->parameter('book');
        }
    }
}
