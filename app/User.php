<?php

namespace Apkom;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Apkom\Http\Resources\UserResource;
use Intervention\Image\Facades\Image;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo'
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
        $users = self::latest()->paginate(8);
        return UserResource::collection($users);
    }

    public function getDataReport(){
        $users = self::all();
        return UserResource::collection($users);
    }

    public function searchData($search){
        $user = self::where('name','LIKE',"%$search%")
                    ->orWhere('email','LIKE',"%$search%")
                    ->paginate(8);            
        return UserResource::collection($user);
    }

    public function saveUserMahasiswa($request, $id=false){
        $user = new User;
        if($id){
            $user = self::find($id);
        }else{
            $user->password = Hash::make($request->password);
        } 
        $user->name = $request->name;
        $user->email = $request->email;
        if($user->save()){
            return $user->id;
        }else{
            return ['message' => 'Save User Failed'];
        }
    }

    public function saveData($request, $id=false){
        $user = new User;
        $userTemp = auth('api')->user();
        if($id) $user = self::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password) $user->password = Hash::make($request->password);
        $user->role = $request->role;
        if($request->photo != ''){
            if($request->photo != $userTemp->photo){
                $fileName = time().uniqid().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
                $img = Image::make($request->photo);
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('storage/data/profile/').$fileName);
                $user->photo = $fileName;
                if($userTemp->photo != 'user.png'){
                    $imageTemp = public_path('storage/data/profile/').$userTemp->photo;
                    if(file_exists($imageTemp)){
                        @unlink($imageTemp);
                    }
                }
            }
        }
        if($user->save()){
            return ['message' => 'Save User Successfull'];
        }else{
            return ['message' => 'Save User Failed'];
        }
    }

    public function deleteData($id){
        $user = self::find($id);
        if($user->delete()){
            $imageTemp = public_path('storage/data/profile/').$user->photo;
            if($user->photo != 'user.png' && file_exists($imageTemp)){
                @unlink($imageTemp);
            }
            return ['message' => 'Delete User Successfull'];
        }else{
            return ['message' => 'Delete User Failed'];
        }
    }

    public function profile(){
        $user = auth('api')->user();
        return $user;
    }

    public function getKaprodiData(){
        $users = self::where('role', 'Kaprodi')->select('id','name')->get();
        return UserResource::collection($users);
    }

    public function jurusan(){
      return $this->hasOne('Apkom\Jurusan', 'id_account', 'id')->select('id','nama_jurusan')->first();
    }

    public function mahasiswa(){
        return $this->hasOne('Apkom\Mahasiswa', 'id_account', 'id')->select('id','nama')->first();
    }
}
