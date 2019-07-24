<?php

namespace Apkom\Http\Controllers\API;

use Apkom\User;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Http\Requests\UserRequest;
use Apkom\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;



class UserController extends Controller
{
    public function __construct(User $user){
        $this->middleware('auth:api');
        $this->user = $user;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isWarek');
        $users =  $this->user->getData();
        return $users;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('isWarek');
        $user = $this->user->saveData($request);
        return $user;
    }

    public function storeUserMahasiswa(UserRequest $request){
        $this->authorize('isWarek');
        $user = $this->user->saveUserMahasiswa($request);
        return $user;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $this->authorize('isWarek');
        $user = $this->user->saveData($request,$id);
        return $user;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isWarek');
        $user = $this->user->deleteData($id);
        return $user;

    }

    public function search(Request $request){
        $this->authorize('isWarek');
        if ($search = $request->q) {
            $users = $this->user->searchData($request->q);
        }else{
            $users = $this->user->getData();
        }
        return $users;

    }

    public function export(Request $request)
    {
        $this->authorize('isWarek');
        if($request->type == 'Accounts.xlsx'){
            return Excel::download(new UsersExport, 'Accounts.xlsx');
        }else{
            $users =  $this->user->getDataReport();
            $pdf = PDF::loadView('print.users', ['users' => $users]);
            return $pdf->output();
        }

    }

    public function profile(){
        $user = $this->user->profile();
        return $user;

    }

    public function updateProfile(UserRequest $request){
        $userTemp = auth('api')->user();
        $user = $this->user->saveData($request, $userTemp->id);
        return $user;

    }

    public function getKaprodiData()
    {
        $this->authorize('isWarek');
        $users =  $this->user->getKaprodiData();
        return $users;

    }
}
