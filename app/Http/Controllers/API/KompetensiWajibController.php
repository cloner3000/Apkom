<?php

namespace Apkom\Http\Controllers\API;

use Apkom\KompetensiWajib;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Http\Requests\KompetensiWajibRequest;
use Apkom\Exports\KompetensiWajibExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class KompetensiWajibController extends Controller
{
    public function __construct(KompetensiWajib $kompetensiWajib){
        $this->middleware('auth:api');
        $this->kompetensiWajib = $kompetensiWajib;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isKaprodi');
        $kompetensiWajib = $this->kompetensiWajib->getData();
        return $kompetensiWajib;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KompetensiWajibRequest $request)
    {
        $this->authorize('isKaprodi');
        $kompetensiWajib = $this->kompetensiWajib->saveData($request);
        return $kompetensiWajib;
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
    public function update(KompetensiWajibRequest $request, $id)
    {
        $this->authorize('isKaprodi');
        $kompetensiWajib = $this->kompetensiWajib->saveData($request,$id);
        return $kompetensiWajib;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isKaprodi');
        $kompetensiWajib = $this->kompetensiWajib->deleteData($id);
        return $kompetensiWajib;
    }

    public function search(Request $request){
        $this->authorize('isKaprodi');
        if ($search = $request->q) {
            $kompetensiWajib = $this->kompetensiWajib->searchData($request->q);
        }else{
            $kompetensiWajib = $this->kompetensiWajib->getData();
        }
        return $kompetensiWajib;

    }

    public function export(Request $request)
    {
        $this->authorize('isKaprodi');
        if($request->type == 'KompetensiWajib.xlsx'){
            return Excel::download(new KompetensiWajibExport, 'KompetensiWajib.xlsx');
        }else{
            $kompetensiWajib =  $this->kompetensiWajib->getDataReport();
            $pdf = PDF::loadView('print.kompetensiWajib', ['kompetensiWajib' => $kompetensiWajib]);
            return $pdf->output();
        }

    }
}
