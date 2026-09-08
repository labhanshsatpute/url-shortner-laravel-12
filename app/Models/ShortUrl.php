<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ShortUrlCodeGenerator;

class ShortUrl extends Model
{
    use HasUuids, SoftDeletes, ShortUrlCodeGenerator;
}
