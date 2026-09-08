<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use HasUuids, SoftDeletes;

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}
