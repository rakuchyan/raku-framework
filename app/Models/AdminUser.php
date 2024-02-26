<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2024/2/26
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminUser extends Model
{
    /**
     * 日志
     * @Author raku
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AdminLog::class, 'user_id');
    }
}
