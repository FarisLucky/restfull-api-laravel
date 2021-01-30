<?php

namespace App\Models;

use App\Models\Traits\HasDateTimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends AbstractApiModel
{
    use HasFactory, HasDateTimeFormat;

    protected $fillable = ['message'];

    public function type(): string
    {
        return "comments";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function users()
    {
        return $this->user();
    }

    public function books()
    {
        return $this->book();
    }

}
