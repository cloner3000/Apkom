<?php

namespace Apkom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KompetensiRequest extends FormRequest
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
                    'id_bidang' => 'required|integer',
                    'nama_kompetensi' => 'required|string|max:191',
                    'tgl_mulai' => 'required|date',
                    'tgl_selesai' => 'required|date',
                    'tingkat' => 'required|string|max:191',
                    'peran' => 'required|string|max:191',
                    'bukti' => 'required',
                    'kemampuan' => 'required|array'
                ];
            }
            case 'PUT':
            {
                return [
                    'id_bidang' => 'required|integer',
                    'nama_kompetensi' => 'required|string|max:191',
                    'tgl_mulai' => 'required|date',
                    'tgl_selesai' => 'required|date',
                    'tingkat' => 'required|string|max:191',
                    'peran' => 'required|string|max:191',
                    'bukti' => 'sometimes',
                    'kemampuan' => 'required|array'
                ];
            }
            default:break;
        }
    }
}
