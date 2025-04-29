<?php
/**
 * Author: raku <leli@mufan.design>
 */

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, SoftDeletes;

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected function castAttribute($key, $value)
    {
        if (array_key_exists($key, $this->getCasts()) && in_array($this->getCasts()[$key], ['date', 'datetime'])) {
            return is_null($value) ? null : parent::castAttribute($key, $value);
        }

        return parent::castAttribute($key, $value);
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
