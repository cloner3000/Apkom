<?php

namespace Apkom\Http\Controllers\API;

use Apkom\Jurusan;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Http\Requests\JurusanRequest;
use Apkom\Exports\JurusanExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class JurusanController extends Controller
{
    public function __construct(Jurusan $jurusan){
        $this->middleware('auth:api');
        $this->jurusan = $jurusan;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isWarek');
        $jurusan = $this->jurusan->getData();
        return $jurusan;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JurusanRequest $request)
    {
        $this->authorize('isWarek');
        $jurusan = $this->jurusan->saveData($request);
        return $jurusan;
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
    public function update(JurusanRequest $request, $id)
    {
        $this->authorize('isWarek');
        $jurusan = $this->jurusan->saveData($request,$id);
        return $jurusan;
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
        $jurusan = $this->jurusan->deleteData($id);
        return $jurusan;
    }

    public function search(Request $request){
        $this->authorize('isWarek');
        if ($search = $request->q) {
            $jurusan = $this->jurusan->searchData($request->q);
        }else{
            $jurusan = $this->jurusan->getData();
        }
        return $jurusan;

    }

    public function export(Request $request)
    {
        $this->authorize('isWarek');
        if($request->type == 'Jurusan.xlsx'){
            return Excel::download(new JurusanExport, 'Jurusan.xlsx');
        }else{
            $jurusan =  $this->jurusan->getDataReport();
            $pdf = PDF::loadView('print.jurusan', ['jurusan' => $jurusan]);
            return $pdf->output();
        }

    }

    public function getJurusanData(){
        $this->authorize('isMahasiswa');
        $jurusan = $this->jurusan->getJurusanData();
        return $jurusan;
    }
}
