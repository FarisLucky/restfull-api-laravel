<?php

namespace App\Models;

use App\Models\Traits\HasDateTimeFormat;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Author extends AbstractApiModel
{
    use HasFactory, HasDateTimeFormat;

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function type(): string
    {
        return "authors";
    }

}
