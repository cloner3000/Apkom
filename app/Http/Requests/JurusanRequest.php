<?php

namespace Apkom\Http\Requests;

use Apkom\Jurusan;
use Illuminate\Foundation\Http\FormRequest;

class JurusanRequest extends FormRequest
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
                    'id_account' => 'required|integer|unique:jurusan',
                    'nama_jurusan' => 'required|string|max:191',
                    'program' => 'required|string|max:191',
                    'jenis_pendidikan' => 'required|string|max:191',
                    'fakultas' => 'required|string|max:191',
                    'gelar' => 'required|string|max:191',
                ];
            }
            case 'PUT':
            {
                $getJurusan = Jurusan::find($this->id);
                return [
                    'id_account' => 'required|integer|unique:jurusan,id_account,'.$getJurusan['id'],
                    'nama_jurusan' => 'required|string|max:191',
                    'program' => 'required|string|max:191',
                    'jenis_pendidikan' => 'required|string|max:191',
                    'fakultas' => 'required|string|max:191',
                    'gelar' => 'required|string|max:191',
                ];
            }
            default:break;
        }
    }
}
