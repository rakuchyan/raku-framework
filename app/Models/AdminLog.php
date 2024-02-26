<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'user_id');
    }
}
