<?php

namespace Apkom\Http\Requests;

use Apkom\Mahasiswa;
use Illuminate\Foundation\Http\FormRequest;

class MahasiswaRequest extends FormRequest
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
                    'id_jurusan' => 'required|integer',
                    'npm' => 'required|integer|unique:mahasiswa',
                    'nama' => 'required|string|max:191',
                    'kota_lahir' => 'required|string|max:191',
                    'tgl_lahir' => 'required|date',
                    'no_ijazah' => 'required|string|unique:mahasiswa|max:191',
                    'tgl_masuk' => 'required|date',
                    'tgl_lulus' => 'required|date',
                    'ipk' => 'required|numeric',
                 ];
            }
            case 'PUT':
            {
                $getMahasiswa = Mahasiswa::find($this->id);
                return [
                    'id_jurusan' => 'required|integer',
                    'npm' => 'required|integer|unique:mahasiswa,npm,'.$getMahasiswa['id'],
                    'nama' => 'required|string|max:191',
                    'kota_lahir' => 'required|string|max:191',
                    'tgl_lahir' => 'required|date',
                    'no_ijazah' => 'required|string|max:191|unique:mahasiswa,no_ijazah,'.$getMahasiswa['id'],
                    'tgl_masuk' => 'required|date',
                    'tgl_lulus' => 'required|date',
                    'ipk' => 'required|numeric',
                 ];
            }
            default:break;
        }
    }
}
