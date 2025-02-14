<?php

namespace App\Models;

use App\Enums\AdminUserActive;
use App\Enums\AdminUserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

/**
 * 后台账号 三个端通过字段 user_type 区分
 * @property AdminUserType user_type
 */
class AdminUser extends Authenticate implements JWTSubject
{
    // use HasApiTokens, Notifiable;
    use HasFactory, SoftDeletes, HasRoles;

    protected $guard_name = 'admin';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'deleted_at',
    ];

    protected $casts = [
        'user_type' => AdminUserType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 商户日志
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AdminLog::class, 'merchant_id');
    }

    public function disable(AdminUser $user): JsonResponse
    {
        $user->active = $user->active == AdminUserActive::Disabled ? AdminUserActive::Activated : AdminUserActive::Disabled;
        $user->save();

        return $this->success();
    }
}
