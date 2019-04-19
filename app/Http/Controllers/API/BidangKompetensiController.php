<?php

namespace Apkom\Http\Controllers\API;

use Apkom\BidangKompetensi;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Http\Requests\BidangKompetensiRequest;
use Apkom\Exports\BidangKompetensiExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class BidangKompetensiController extends Controller
{
    public function __construct(BidangKompetensi $bidangKompetensi){
        $this->middleware('auth:api');
        $this->bidangKompetensi = $bidangKompetensi;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isWarek');
        $bidangKompetensi = $this->bidangKompetensi->getData();
        return $bidangKompetensi;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BidangKompetensiRequest $request)
    {
        $this->authorize('isWarek');
        $bidangKompetensi = $this->bidangKompetensi->saveData($request);
        return $bidangKompetensi;
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
    public function update(BidangKompetensiRequest $request, $id)
    {
        $this->authorize('isWarek');
        $bidangKompetensi = $this->bidangKompetensi->saveData($request,$id);
        return $bidangKompetensi;
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
        $bidangKompetensi = $this->bidangKompetensi->deleteData($id);
        return $bidangKompetensi;
    }

    public function search(Request $request){
        $this->authorize('isWarek');
        if ($search = $request->q) {
            $bidangKompetensi = $this->bidangKompetensi->searchData($request->q);
        }else{
            $bidangKompetensi = $this->bidangKompetensi->getData();
        }
        return $bidangKompetensi;

    }

    public function export(Request $request)
    {
        $this->authorize('isWarek');
        if($request->type == 'BidangKompetensi.xlsx'){
            return Excel::download(new BidangKompetensiExport, 'BidangKompetensi.xlsx');
        }else{
            $bidangKompetensi =  $this->bidangKompetensi->getDataReport();
            $pdf = PDF::loadView('print.bidangKompetensi', ['bidangKompetensi' => $bidangKompetensi]);
            return $pdf->output();
        }

    }
}
