<?php

namespace App\Http\Requests;

use App\Rules\CurrentPassword;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserUpdate extends FormRequest
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
            'name' => ['sometimes','string','regex:/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳýỵỷỹ\s|]{5,}$/'],
            'password_current' => ['sometimes',new CurrentPassword],
            'password_new' => Rule::requiredIf(request()->has('password_current')).'|between:5,255|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'name.*' => ':attribute không được chứa ký tự số hoặc ký tự đặc biệt',
            'password_new.*' => ':attribute phải từ 5 - 255 ký tự và phải khớp với nhau'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên người dùng',
            'password_new' => 'Mật khẩu mới'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['error' => $error,'status' => 422],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
