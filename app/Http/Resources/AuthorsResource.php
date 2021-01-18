<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;

class AuthorsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
//            'code' => 200,
//            'status' => 'OK',
//            'data' => [
                'id' => (string) $this->id,
                'type' => 'authors',
                'attributes' => [
                    'name' => $this->name,
                    'created_at' => date("Y-m-d H:i:s",strtotime($this->created_at)),
                    'updated_at' => date("Y-m-d H:i:s",strtotime($this->updated_at))
                ]
//            ]
        ];
    }
}
