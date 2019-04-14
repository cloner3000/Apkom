<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\User;

class UserRequest extends FormRequest
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
                    'name' => 'required|string|max:191',
                    'email' => 'required|string|email|max:191|unique:users',
                    'password' => 'required|string|min:8|max:191',
                    'role' => 'required|string|max:191'
         
                 ];
            }
            case 'PUT':
            {
                $user = User::find($this->id);
                return [
                    'name' => 'required|string|max:191',
                    'email' => 'required|string|email|max:191|unique:users,email,'.$user['id'],
                    'password' => 'sometimes|string|min:8|max:191',
                    'role' => 'required|max:191',
                    'photo' => 'sometimes|image'
         
                 ];
            }
            default:break; 
        }
    }
}
