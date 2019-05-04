<?php

namespace Apkom\Http\Controllers\API;

use Apkom\BuktiKompetensiWajib;
use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Apkom\Http\Requests\BuktiKompetensiWajibRequest;

class BuktiKompetensiWajibController extends Controller
{
    public function __construct(BuktiKompetensiWajib $buktiKompetensiWajib){
        $this->middleware('auth:api');
        $this->buktiKompetensiWajib = $buktiKompetensiWajib;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isMahasiswa');
        $buktiKompetensiWajib = $this->buktiKompetensiWajib->getData();
        return $buktiKompetensiWajib;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuktiKompetensiWajibRequest $request)
    {
        //
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
    public function update(BuktiKompetensiWajibRequest $request, $id)
    {
        $this->authorize('isMahasiswa');
        $buktiKompetensiWajib = $this->buktiKompetensiWajib->saveData($request,$id);
        return $buktiKompetensiWajib;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request){
        $this->authorize('isMahasiswa');
        if ($search = $request->q) {
            $buktiKompetensiWajib = $this->buktiKompetensiWajib->searchData($request->q);
        }else{
            $buktiKompetensiWajib = $this->buktiKompetensiWajib->getData();
        }
        return $buktiKompetensiWajib;

    }
}
