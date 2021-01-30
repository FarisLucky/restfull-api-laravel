<?php

namespace App\Models;

use App\Models\Traits\HasDateTimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends AbstractApiModel
{
    use HasFactory, HasDateTimeFormat;

    protected $fillable = ["id",'title','description','publication_year'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return "books";
    }


}
