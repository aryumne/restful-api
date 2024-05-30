<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EpresenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_user'   => $this->user->id,
            'nama_user' => $this->user->name,
            'type'      => $this->type,
            'tanggal'   => Carbon::parse($this->waktu)->format('Y-m-d'),
            'waktu'     => Carbon::parse($this->waktu)->format('H:i:s'),
        ];
    }
}
