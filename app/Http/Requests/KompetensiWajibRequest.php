<?php

namespace Apkom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KompetensiWajibRequest extends FormRequest
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
            'nama_kompetensi_wajib' => 'required|string|max:191'
        ];
    }
}
