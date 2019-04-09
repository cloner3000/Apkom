<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'photo'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getData(){
        $user = self::first()->paginate(5);
        return UserResource::collection($user);
    }

    public function searchData($search){
        $user = self::where(function($query) use ($search){
            $query->where('name','LIKE',"%$search%")
                    ->orWhere('email','LIKE',"%$search%");
        })->paginate(5);
        return $user;
    }

    public function saveData($request, $id=false){
        $user = new User;
        if($id) $user = self::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password) $user->password = Hash::make($request->password);
        $user->role = $request->role;
        if($user->save()){
            return ['message' => 'Save User Successfull'];
        }else{
            return ['message' => 'Save User Failed'];
        }
    }

    public function deleteData($id){
        $user = self::find($id);
        if($user->delete()){
            return ['message' => 'Delete User Successfull'];
        }else{
            return ['message' => 'Delete User Failed'];
        }
    }
}
