<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractApiModel extends Model
{
    /**
     * @return string
     */
    abstract public function type(): string;

    /**
     * @return Collection
     */
    public function allowedAttributes(): Collection
    {
        return collect($this->attributes)->filter(function ($item, $key) {
            return !collect($this->hidden)->contains($key) && $key !== "id";
        })->merge([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
    }
}
