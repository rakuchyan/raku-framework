<?php

namespace App\Http\Controllers;

use App\Enums\AdminUserActive;
use App\Enums\AdminUserType;
use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class CommonController extends Controller
{
    protected array $dict = [
        'admin_user_active' => AdminUserActive::class,
        'admin_user_type' => AdminUserType::class,
    ];

    public function getAllEnums(Request $request): JsonResponse
    {
        $locale = App::getLocale();

        $enums = Lang::get('enums', [], $locale);

        if (!is_array($enums)) {
            return $this->error(__('enum_data_not_found'));
        }

        $formattedEnums = [];
        foreach ($enums as $tag => $enum) {
            $formattedEnums[] = [
                'tag' => $tag,
                'enums' => collect($enum)->map(function ($value, $key) {
                    return ['key' => $key, 'value' => $value];
                })->toArray(),
            ];
        }

        return $this->success([
            'locale' => $locale,
            'enums' => $formattedEnums,
        ]);
    }

    public function getEnumsByKey(Request $request, string $tag): JsonResponse
    {
        if (!Lang::has('enums.' . $tag, App::getLocale())) {
            return $this->error(__('enum_type_not_found'));
        }

        $enums = Lang::get('enums.' . $tag, [], App::getLocale());

        $formattedEnums = [];
        foreach ($enums as $key => $value) {
            $formattedEnums[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return $this->success([
            'tag' => $tag,
            'locale' => App::getLocale(),
            'enums' => $formattedEnums,
        ]);
    }

    public function dictionaries(): JsonResponse
    {
        $res = [];

        foreach ($this->dict as $key => $model) {
            $list = [];
            foreach ($model::cases() as $value) {
                $list[] = ['key' => $value->value, 'label' => method_exists($value, 'desc') ? $value->desc() : $value->name];
            }
            $res[] = [
                'tag' => $key,
                'options' => $list
            ];
        }

        return $this->success($res);
    }

    public function upload(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => 'required|file|max:40960000',
            'file_name' => 'nullable|string|max:255',
        ], [
            'file.max' => '文件最大支持4G'
        ]);

        // 图片
        $file = Arr::get($data, 'file');
        /**
         * @var UploadedFile $file
         */
        $imagePath = Carbon::today()->format("Y/m/") . 'files';
        $src = $file->store($imagePath, ['disk' => 's3']);
        $attachment = new Attachment();
        $attachment->fill([
            'file_name' => Arr::get($data, 'file_name', $file->getClientOriginalName()),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'upload_time' => now(),
            'path' => $src,
        ])->save();

        return $this->success($attachment);
    }
}
