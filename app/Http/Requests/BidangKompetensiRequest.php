<?php

namespace Apkom\Http\Requests;

USE Apkom\BidangKompetensi;
use Illuminate\Foundation\Http\FormRequest;

class BidangKompetensiRequest extends FormRequest
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
                    'nama_bidang' => 'required|string|unique:bidang_kompetensi',
                    'nama_bidang_en' => 'required|string'
                ];
            }
            case 'PUT':
            {
                $getBidangKompetensi = BidangKompetensi::find($this->id);
                return [
                    'nama_bidang' => 'required|string|unique:bidang_kompetensi,nama_bidang,'.$getBidangKompetensi['id'],
                    'nama_bidang_en' => 'required|string'
                ];
            }
            default:break;
        }
    }
}
