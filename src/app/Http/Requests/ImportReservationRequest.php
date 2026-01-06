<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240', // 10MB
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'file.required' => 'ファイルを選択してください。',
            'file.file' => '有効なファイルをアップロードしてください。',
            'file.mimes' => 'Excelファイル(.xlsx または .xls)をアップロードしてください。',
            'file.max' => 'ファイルサイズは10MB以下にしてください。',
        ];
    }

}
