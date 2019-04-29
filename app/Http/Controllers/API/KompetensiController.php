<?php

namespace Apkom\Http\Controllers\API;

use Apkom\Kompetensi;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Http\Requests\KompetensiRequest;
use Apkom\Exports\KompetensiExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class KompetensiController extends Controller
{
    public function __construct(Kompetensi $kompetensi){
        $this->middleware('auth:api');
        $this->kompetensi = $kompetensi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isMahasiswa');
        $kompetensi = $this->kompetensi->getData();
        return $kompetensi;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KompetensiRequest $request)
    {
        $this->authorize('isMahasiswa');
        $kompetensi = $this->kompetensi->saveData($request);
        return $kompetensi;
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
    public function update(KompetensiRequest $request, $id)
    {
        $this->authorize('isMahasiswa');
        $kompetensi = $this->kompetensi->saveData($request,$id);
        return $kompetensi;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isMahasiswa');
        $kompetensi = $this->kompetensi->deleteData($id);
        return $kompetensi;
    }

    public function search(Request $request){
        $this->authorize('isMahasiswa');
        if ($search = $request->q) {
            $kompetensi = $this->kompetensi->searchData($request->q);
        }else{
            $kompetensi = $this->kompetensi->getData();
        }
        return $kompetensi;
    }

    public function export(Request $request)
    {
        $this->authorize('isMahasiswa');
        if($request->type == 'Kompetensi.xlsx'){
            return Excel::download(new KompetensiExport, 'Kompetensi.xlsx');
        }else{
            $kompetensi =  $this->kompetensi->getDataReport();
            $pdf = PDF::loadView('print.kompetensi', ['kompetensi' => $kompetensi]);
            return $pdf->output();
        }

    }
}
