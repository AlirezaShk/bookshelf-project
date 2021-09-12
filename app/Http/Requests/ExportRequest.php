<?php

namespace App\Http\Requests;

use App\Rules\ExportFileType;
use App\Rules\ExportFields;
use App\Rules\ExportIdArray;
use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    private $exportFields;
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
        return [
            'fields' => ['required', new ExportFields($this->exportFields)],
            'ids' => ['required', new ExportIdArray],
            'export_type' => ['required', new ExportFileType],
        ];
    }

    public function prepareForValidation()
    {
        $this->exportFields = array_map(function($a) {
                return $a['raw'];
            }, $this->route()->controller->getExportFields()
        );
        $this['export_type'] = $this->route()->parameter('type');
    }
}
