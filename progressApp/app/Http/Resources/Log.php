<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Log extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return[
            'log_id' => $this->log_id,
            'value' => $this->value,
            'activity_id' => $this->activity_id,
            'body_parameter_id' => $this->body_parameter_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
