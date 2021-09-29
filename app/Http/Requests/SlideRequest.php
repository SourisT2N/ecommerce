<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SlideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            "subject" => "required|string|min:5|max:255|unique:slides,subject,".$this->slide,
            "content" => "required|string|min:10|max:255",
            "url"   => "required|url",
            "image_display" => Rule::requiredIf(!$this->slide)."|image"
        ];
    }

    public function messages()
    {
        return [
            "subject.*" => ":attribute chỉ được từ 5 - 30 ký tự và chưa được khởi tạo",
            "content.*" => ":attribute chỉ được từ 10 - 30",
            "url.*" => ":attribute phải đúng định dạng",
            "image_display.*" => ":attribute có đuôi (jpg, jpeg, png, bmp, gif, svg, or webp)"
        ];
    }

    public function attributes()
    {
        return [
            "subject" => "Tiêu đề",
            "content" => "Nội dung",
            "url" => "Url",
            "image_display" => "Hình ảnh hiển thị"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()
        ->json(['error' => $error,'status' => 422],
        JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
