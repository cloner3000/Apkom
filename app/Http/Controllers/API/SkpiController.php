<?php

namespace Apkom\Http\Controllers\API;

use Apkom\Skpi;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Exports\SkpiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use PDF;

class SkpiController extends Controller
{
    public function __construct(Skpi $skpi){
        $this->middleware('auth:api');
        $this->skpi = $skpi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        if(Gate::check('isKaprodi')){
            $skpi = $this->skpi->getData(auth('api')->user()->id);
        }else{
            $skpi = $this->skpi->getData();    
        }
        return $skpi;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->authorize('isMahasiswa');
        $skpi = $this->skpi->saveData();
        return $skpi;
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
    public function update(Request $request, $id)
    {
        //
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
        $skpi = $this->skpi->deleteData($id);
        return $skpi;
    }

    public function search(Request $request){
        $this->authorize('isAdmin');
        if(Gate::check('isKaprodi')){
            if ($search = $request->q) {
                $skpi = $this->skpi->searchData($request->q, auth('api')->user()->id);
            }else{
                $skpi = $this->skpi->getData(auth('api')->user()->id);
            }
        }else{
            if ($search = $request->q) {
                $skpi = $this->skpi->searchData($request->q);
            }else{
                $skpi = $this->skpi->getData();
            }
        }
        return $skpi;
    }

    public function export(Request $request)
    {
        $this->authorize('isAdmin');
        if($request->type == 'Skpi.xlsx'){
            return Excel::download(new SkpiExport, 'Skpi.xlsx');
        }else{
            if(Gate::check('isKaprodi')){
                $skpi =  $this->skpi->getDataReport(auth('api')->user()->id);
            }else{
                $skpi =  $this->skpi->getDataReport();
            }       
            $pdf = PDF::loadView('print.dataSkpi', ['skpi' => $skpi]);
            return $pdf->output();
        }
    }

    public function publish($id)
    {
        $this->authorize('isKaprodi');
        $skpi = $this->skpi->publish($id);
        return $skpi;
    }

    public function checkStatus()
    {
        $this->authorize('isMahasiswa');
        $skpi = $this->skpi->checkStatus();
        return $skpi;
    }
}
