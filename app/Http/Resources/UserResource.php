<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        switch ($this->jenisUser) {
            case 'dosen':
                $oid = $this->nip;
                break;
            case 'admin':
                $oid = $this->username;
                break;
            case 'mahasiswa':
                $oid = $this->nim;
                break;
        }

        return [
            'oid' => $oid,
            'nama' => $this->nama,
            'email' => $this->email,
            'prodi' => optional($this->prodi)->nama_prodi ?? null
        ];
    }
}
