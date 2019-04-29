<?php

namespace Apkom\Http\Controllers\API;

use Apkom\Mahasiswa;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Http\Requests\MahasiswaRequest;
use Apkom\Exports\MahasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class MahasiswaController extends Controller
{
    public function __construct(Mahasiswa $mahasiswa){
        $this->middleware('auth:api');
        $this->mahasiswa = $mahasiswa;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isWarek');
        $mahasiswa = $this->mahasiswa->getData();
        return $mahasiswa;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MahasiswaRequest $request)
    {
        $this->authorize('isMahasiswa');
        $mahasiswa = $this->mahasiswa->saveData($request);
        return $mahasiswa;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MahasiswaRequest $request, $id)
    {
        $this->authorize('isMahasiswa');
        $mahasiswa = $this->mahasiswa->saveData($request,$id);
        return $mahasiswa;
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
        $mahasiswa = $this->mahasiswa->deleteData($id);
        return $mahasiswa;
    }

    public function search(Request $request){
        $this->authorize('isWarek');
        if ($search = $request->q) {
            $mahasiswa = $this->mahasiswa->searchData($request->q);
        }else{
            $mahasiswa = $this->mahasiswa->getData();
        }
        return $mahasiswa;

    }

    public function export(Request $request)
    {
        $this->authorize('isWarek');
        if($request->type == 'Mahasiswa.xlsx'){
            return Excel::download(new MahasiswaExport, 'Mahasiswa.xlsx');
        }else{
            $mahasiswa =  $this->mahasiswa->getDataReport();
            $pdf = PDF::loadView('print.mahasiswa', ['mahasiswa' => $mahasiswa]);
            return $pdf->output();
        }

    }

    public function profile(){
        $this->authorize('isMahasiswa');
        $mahasiswa = $this->mahasiswa->getMahasiswaProfile();
        return $mahasiswa;
    }
}
