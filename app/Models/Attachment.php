<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['url'];

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::disk('s3')->url($this->getAttribute('path')),
        );
    }
}
