<?php

namespace Apkom\Http\Controllers;

use Illuminate\Http\Request;
use Apkom\Skpi;

class CheckSkpiController extends Controller
{

    public function checkSkpi(Request $request){
        $this->skpi = new Skpi;
        $skpi = $this->skpi->checkSkpi($request->id);
        return $skpi;
    }
}
