<?php
namespace App\Filament\Admin\Resources\FocusSessionResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FocusSession;

/**
 * @property FocusSession $resource
 */
class FocusSessionTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
