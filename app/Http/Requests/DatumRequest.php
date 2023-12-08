<?php

namespace App\Http\Requests;

use App\Services\DatumService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DatumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required',],
            'size' => ['required', 'int'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->isSizeLimitReached()) {
                    $validator->errors()->add(
                        key: 'size',
                        message: 'Size limit reached'
                    );
                }
            }
        ];
    }

    private function isSizeLimitReached(): bool
    {
        $datumService = app(DatumService::class);
        $datum = $datumService->get($this->input('id'));
        if (is_null($datum)) {
            return $datumService->cannotStore($this->input('size'));
        }
        return false;
    }
}
