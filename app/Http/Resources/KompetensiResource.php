<?php

namespace Apkom\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KompetensiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
