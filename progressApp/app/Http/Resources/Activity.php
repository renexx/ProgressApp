<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Activity extends JsonResource
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
      /*return [
           'activity_id' => $this->activity_id,
           'activity_name' => $this->activity_name,
           'user_id' => $this->user_id,
           'created_at' => $this->created_at,
           'updated_at' => $this->updated_at
       ];*/
    }
}
