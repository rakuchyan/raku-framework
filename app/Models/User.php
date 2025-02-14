<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property int $status
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'deleted_at',
        'token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at',
        'updated_at',
        'status' => UserStatusEnum::class,
    ];

    /**
     * Interact with the user's first name.
     *
     * @return Attribute
     */
    protected function firstName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['FirstN'],
            set: fn ($value) => ['FirstN' => $value], // <<< CHANGE HERE
        );
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * 添加时间
     * @param $query
     * @param $start
     * @param $end
     */
    public function scopeCreatedAtTimeRange($query, $start, $end)
    {
        if (!empty($start)) {
            $startDay = Carbon::parse($start, 'PRC')->utc()->startOfDay();
            $query->where('created_at', '>=', $startDay);
        }

        if (!empty($end)) {
            $endDay = Carbon::parse($end, 'PRC')->utc()->endOfDay();
            $query->where('created_at', '<=', $endDay);
        }

        return $query;
    }


    /**
     * queryBuilder
     *
     * @param Builder $builder
     * @param $start
     * @param $end
     * @return void
     */
    public function scopeCreatedAtBetween(Builder $builder, $start = null, $end = null): void
    {
        try {
            if ($start) {
                // $builder->where('created_at', '>=', Carbon::parse($start, 'PRC')->utc()->startOfDay());
                // 根据服务器时间调整
                $builder->where('created_at', '>=', Carbon::parse($start)->startOfDay());
            }
            if ($end) {
                $builder->where('created_at', '<=', Carbon::parse($end, 'PRC')->utc()->endOfDay());
            }
        } catch (\Throwable $exception) {
        }
    }
}
