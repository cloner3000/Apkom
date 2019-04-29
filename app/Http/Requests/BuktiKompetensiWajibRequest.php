<?php

namespace Apkom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuktiKompetensiWajibRequest extends FormRequest
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
        switch($this->method()){

            case 'POST':
            {
                return [
                    'id_mahasiswa' => 'required|integer',
                    'nama_kompetensi_wajib' => 'required|string|max:191',
                ];
            }
            case 'PUT':
            {
                return [
                    'bukti_wajib' => 'required',
                ];
            }
            default:break;
        }
    }
}
