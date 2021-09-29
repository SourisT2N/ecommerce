<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BlogRequest extends FormRequest
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
            "name" => "required|string|min:10|unique:blogs,name,".$this->blog,
            "summary" => "required|min:10|string",
            "body" => "required|min:10|string",
            "image_display" => Rule::requiredIf(!$this->blog)."|image",
        ];
    }

    public function messages()
    {
        return [
            "name.*" => ":attribute phải từ 10 ký tự trở lên và chưa được khởi tạo",
            "summary.*" => ":attribute phải từ 10 ký tự trở lên",
            "body.*" => ":attribute phải từ 10 ký tự trở lên",
            "image_display.*" => ":attribute có đuôi (jpg, jpeg, png, bmp, gif, svg, or webp)",
        ];
    }

    public function attributes()
    {
        return [
            "name" => "Tên blog",
            "summary" => "Văn bản ngắn",
            "body" => "Nội dung",
            "image_display" => "Hình ảnh hiển thị",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()
        ->json(['error' => $error,'status' => 422]
        ,JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
