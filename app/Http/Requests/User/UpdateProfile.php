<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'sometimes|string',
            'gender' => 'sometimes|in:unknown,male,female',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '昵称',
            'gender' => '性别',
        ];
    }
}
